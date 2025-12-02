<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\LaporanKerusakanModel;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Services\PeminjamanService;
use Config\Database;

class PeminjamanController extends BaseController
{
   protected $peminjamanModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;

   protected $saranaModel;
   protected $prasaranaModel;
   protected $laporanModel;

   protected $peminjamanService;

   public function __construct()
   {
      $this->peminjamanModel = new PeminjamanModel();
      $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->laporanModel = new LaporanKerusakanModel();

      // inject service
      $this->peminjamanService = new PeminjamanService();
   }

   /**
    * Menampilkan Form Pengajuan Peminjaman
    */
   public function new()
   {
      // Jalankan pengecekan
      if ($blocked = $this->checkBlockStatus()) {
         return $blocked;
      }

      $data = [
         'title' => 'Ajukan Peminjaman Baru',
         'sarana' => $this->saranaModel->where('status_ketersediaan', 'Tersedia')->findAll(),
         'prasarana' => $this->prasaranaModel->whereIn('status_ketersediaan', ['Tersedia', 'Dipinjam'])->findAll(),
         'breadcrumbs' => [
            ['name' => 'Peminjaman', 'url' => site_url('peminjam/histori-peminjaman')],
            ['name' => 'Buat Pengajuan Sarana / Prasarana']
         ]
      ];

      return view('peminjam/peminjaman/create_view', $data);
   }

   /**
    * Proses Simpan Peminjaman (CREATE)
    */
   public function create()
   {
      // Jalankan pengecekan lagi
      if ($blocked = $this->checkBlockStatus()) {
         return $blocked;
      }

      // 1. Validasi Input Header
      if (!$this->validate([
         'kegiatan' => [
            'rules' => "required|min_length[5]",
            'errors' => [
               'required' => 'Nama sarana wajib diisi.',
               'min_length' => 'Nama kegiatan / acara minimal 5 huruf.',
            ]
         ],
         'tgl_pinjam_dimulai' => [
            'rules' => "required|valid_date",
            'errors' => [
               'required' => 'Tanggal mulainya peminjaman harus diisi.',
               'valid_date' => 'Tanggal tidak valid.',
            ]
         ],
         'tgl_pinjam_selesai' => [
            'rules' => "required|valid_date",
            'errors' => [
               'required' => 'Tanggal selesainya peminjaman harus diisi.',
               'valid_date' => 'Tanggal tidak valid.',
            ]
         ],
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      try {
         // 2. Panggil Service untuk eksekusi
         $this->peminjamanService->createSubmission(
            auth()->user()->id,
            $this->request->getPost()
         );

         return redirect()->to(site_url('peminjam/histori-peminjaman'))
            ->with('message', 'Pengajuan peminjaman berhasil dibuat.');
      } catch (\Throwable $e) {
         // Pastikan koneksi DB tersedia sebelum rollback
         $db = \Config\Database::connect();
         $db->transRollback();

         // 1. LOG ERROR ASLI (Untuk Developer)
         // Pesan ini akan masuk ke file log di folder /writable/logs/
         log_message('error', '[Peminjaman::create] Gagal menyimpan peminjaman. User ID: ' . auth()->user()->id . '. Error: ' . $e->getMessage());

         // 2. CEK JENIS ERROR (Opsional)
         // Jika error berasal dari validasi bisnis (yang kita throw manual), boleh ditampilkan
         // Contoh: throw new \Exception("Stok habis");
         // $pesanUser = 'Terjadi kesalahan sistem. Silakan hubungi admin.';
         $pesanUser = 'Pilih setidaknya peminjaman sarana atau prasarana.';

         // Deteksi jika ini error bisnis yang aman ditampilkan
         // (Anda bisa membuat Custom Exception class untuk membedakan)
         if ($e->getMessage() == "Tanggal selesai tidak valid." || strpos($e->getMessage(), 'Stok') !== false) {
            $pesanUser = $e->getMessage();
         }

         // 3. KEMBALIKAN PESAN AMAN KE USER
         return redirect()->back()->withInput()->with('error', $pesanUser);
      }
   }

   /**
    * Batalkan Peminjaman (DELETE)
    * Hanya bisa jika status masih 'Diajukan'
    */
   public function delete($id)
   {
      $peminjaman = $this->peminjamanModel->find($id);
      $userId = auth()->user()->id;

      if (!$peminjaman) {
         return redirect()->back()->with('error', 'Data tidak ditemukan.');
      }

      // Cek Kepemilikan
      if ($peminjaman['id_peminjam'] != $userId) {
         return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
      }

      // Cek Status
      if ($peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIAJUKAN) {
         return redirect()->back()->with('error', 'Peminjaman tidak dapat dibatalkan karena sudah diproses/berjalan.');
      }

      // Hapus (Detail akan terhapus otomatis jika diset CASCADE di DB)
      $this->peminjamanModel->delete($id);

      return redirect()->to(site_url('peminjam/histori-peminjaman'))->with('message', 'Pengajuan peminjaman berhasil dibatalkan.');
   }

   /**
    * Menghapus Item Spesifik dalam Peminjaman
    * @param string $tipe 'Sarana' atau 'Prasarana'
    * @param int $idDetail ID dari tabel detail
    */
   public function deleteItem($tipe, $idDetail)
   {
      $userId = auth()->user()->id;
      $peminjamanId = null;

      // 1. Tentukan Model & Ambil Data Detail
      if ($tipe === 'Sarana') {
         $detail = $this->detailSaranaModel->find($idDetail);
      } elseif ($tipe === 'Prasarana') {
         $detail = isset($this->detailPrasaranaModel) ? $this->detailPrasaranaModel->find($idDetail) : null;
      } else {
         return redirect()->back()->with('error', 'Tipe item tidak valid.');
      }

      if (!$detail) {
         return redirect()->back()->with('error', 'Item tidak ditemukan.');
      }

      $peminjamanId = $detail['id_peminjaman'];

      // 2. Validasi Kepemilikan & Status Lewat Header
      $peminjaman = $this->peminjamanModel->find($peminjamanId);

      if ($peminjaman['id_peminjam'] != $userId) {
         return redirect()->back()->with('error', 'Akses ditolak.');
      }

      if ($peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIAJUKAN) {
         return redirect()->back()->with('error', 'Tidak bisa membatalkan item karena status sudah diproses.');
      }

      // 3. Hapus Item Detail
      if ($tipe === 'Sarana') {
         $this->detailSaranaModel->delete($idDetail);
      } else {
         $this->detailPrasaranaModel->delete($idDetail);
      }

      // 4. Cek Apakah Transaksi Menjadi Kosong?
      // Hitung sisa item di kedua tabel detail untuk ID Peminjaman ini
      $sisaSarana = $this->detailSaranaModel->where('id_peminjaman', $peminjamanId)->countAllResults();
      // Asumsi Anda sudah load model prasarana
      $sisaPrasarana = isset($this->detailPrasaranaModel) ? $this->detailPrasaranaModel->where('id_peminjaman', $peminjamanId)->countAllResults() : 0;

      if (($sisaSarana + $sisaPrasarana) === 0) {
         // Jika kosong melompong, hapus Headernya juga
         $this->peminjamanModel->delete($peminjamanId);
         $msg = 'Seluruh peminjaman telah dibatalkan.';
      } else {
         $msg = 'Item berhasil dihapus dari daftar peminjaman.';
      }

      return redirect()->to(site_url('peminjam/histori-peminjaman'))->with('message', $msg);
   }

   /**
    * Upload Bukti SEBELUM (Saat Ambil)
    */
   public function uploadBuktiSebelum($tipe, $idDetail)
   {
      // 1. Validasi File
      if (!$this->validate([
         'foto_bukti' => [
            'rules' => 'uploaded[foto_bukti]|is_image[foto_bukti]|mime_in[foto_bukti,image/jpg,image/jpeg,image/png]|max_size[foto_bukti,2048]',
            'errors' => [
               'uploaded' => 'Foto bukti wajib diupload.',
               'is_image' => 'File harus berupa gambar.',
               'mime_in'  => 'Format foto harus JPG, JPEG, atau PNG.',
               'max_size' => 'Ukuran foto maksimal 2 MB.'
            ]
         ],
         'kondisi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kondisi awal sarana/prasarana harus diisi'
            ]
         ]
      ])) {
         return redirect()->back()->with('error', 'Gagal upload: ' . $this->validator->getError('foto_bukti'));
      }

      // 2. Proses Upload
      $file = $this->request->getFile('foto_bukti');
      $pathFoto = upload_file($file, 'uploads/peminjaman/bukti_sebelum');

      if (!$pathFoto) {
         return redirect()->back()->with('error', 'Gagal mengupload file.');
      }

      // 3. Update Database Detail
      $idPeminjaman = null;

      // update kondisi_akhir
      $kondisi = $this->request->getPost('kondisi');

      $dataUpdate = [
         'foto_sebelum' => $pathFoto,
         'kondisi_awal' => $kondisi,
      ];

      if ($tipe == 'Sarana') {
         $detail = $this->detailSaranaModel->find($idDetail);
         // dd($dataUpdate);
         $this->detailSaranaModel->update($idDetail, $dataUpdate);
         $idPeminjaman = $detail['id_peminjaman'];
      } else {
         // Logic Prasarana
         $detail = $this->detailPrasaranaModel->find($idDetail); // Pastikan model di-load
         // dd($dataUpdate);
         $this->detailPrasaranaModel->update($idDetail, $dataUpdate);
         $idPeminjaman = $detail['id_peminjaman'];
      }

      // 4. LOGIKA BARU: Cek Apakah SEMUA item sudah diupload foto 'sebelum'-nya?
      $peminjaman = $this->peminjamanModel->find($idPeminjaman);

      // Hitung total item dalam transaksi ini
      $totalSarana = $this->detailSaranaModel->where('id_peminjaman', $idPeminjaman)->countAllResults();
      $totalPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $idPeminjaman)->countAllResults();

      // Hitung item yang SUDAH upload foto sebelum
      $doneSarana = $this->detailSaranaModel
         ->where('id_peminjaman', $idPeminjaman)
         ->where('foto_sebelum !=', null) // Asumsi default null
         ->countAllResults();

      $donePrasarana = $this->detailPrasaranaModel
         ->where('id_peminjaman', $idPeminjaman)
         ->where('foto_sebelum !=', null)
         ->countAllResults();

      // Jika Total Item == Total yang Sudah Upload, baru update status Global
      if (($totalSarana + $totalPrasarana) == ($doneSarana + $donePrasarana)) {
         $this->peminjamanModel->update($idPeminjaman, [
            'status_peminjaman_global' => PeminjamanModel::STATUS_DIPINJAM
         ]);
      }
      // Jika belum semua, biarkan status tetap 'Disetujui'

      return redirect()->back()->with('message', 'Foto Bukti Sebelum Berhasil di Upload dan Sarana/Prasarana berhasil dipinjam.');
   }

   public function kembalikanItem($tipe, $idDetail)
   {
      // 1. Validasi Server Side (Strict)
      if (!$this->validate([
         'foto_bukti' => [
            'label' => 'Foto Bukti',
            'rules' => 'uploaded[foto_bukti]|is_image[foto_bukti]|mime_in[foto_bukti,image/jpg,image/jpeg,image/png]|max_size[foto_bukti,2048]',
            'errors' => [
               'uploaded' => 'Foto bukti wajib diupload.',
               'is_image' => 'File harus berupa gambar.',
               'mime_in'  => 'Format foto harus JPG, JPEG, atau PNG.',
               'max_size' => 'Ukuran foto maksimal 2 MB.'
            ]
         ],
         'kondisi' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('error', $this->validator->getError('foto_bukti')); // Ambil error spesifik foto
      }

      // 2. Upload Foto
      $file = $this->request->getFile('foto_bukti');
      $pathFoto = upload_file($file, 'uploads/peminjaman/bukti_akhir');

      if (!$pathFoto) {
         return redirect()->back()->with('error', 'Gagal mengupload foto ke server.');
      }

      // update kondisi_akhir
      $kondisi = $this->request->getPost('kondisi');

      // 3. Update Database Detail Sesuai Tipe
      $dataUpdate = [
         'foto_sesudah'  => $pathFoto,
         'kondisi_akhir' => $kondisi,
      ];

      // 3. Update Database
      // Kita hanya update detail item, tidak mengubah status header 'Selesai' (itu tugas Admin)
      if ($tipe == 'sarana') {
         $this->detailSaranaModel->update($idDetail, $dataUpdate);
      } else if ($tipe == 'prasarana') {
         $this->detailPrasaranaModel->update($idDetail, $dataUpdate);
      }

      return redirect()->back()->with('message', 'Bukti pengembalian berhasil dikirim.');
   }

   /**
    * Upload Bukti SESUDAH (Saat Kembali)
    */
   public function uploadBuktiSesudah($tipe, $idDetail)
   {
      // 1. Validasi
      if (!$this->validate([
         'foto_bukti' => [
            'label' => 'Foto Bukti',
            'rules' => 'uploaded[foto_bukti]|is_image[foto_bukti]|mime_in[foto_bukti,image/jpg,image/jpeg,image/png]|max_size[foto_bukti,2048]',
            'errors' => [
               'uploaded' => 'Foto bukti wajib diupload.',
               'is_image' => 'File harus berupa gambar.',
               'mime_in'  => 'Format foto harus JPG, JPEG, atau PNG.',
               'max_size' => 'Ukuran foto maksimal 2 MB.'
            ]
         ],
         'kondisi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kondisi awal sarana/prasarana harus diisi'
            ]
         ]
      ])) {
         return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
      }

      // 2. Upload Foto
      // 2. Proses Upload
      $file = $this->request->getFile('foto_bukti');
      $pathFoto = upload_file($file, 'uploads/peminjaman/bukti_akhir');

      if (!$pathFoto) {
         return redirect()->back()->with('error', 'Gagal mengupload file.');
      }

      // update kondisi_akhir
      $kondisi = $this->request->getPost('kondisi');
      $jumlahRusak = (int) $this->request->getPost('jumlah_rusak');

      // Variabel untuk Auto-Report
      $userId = auth()->user()->id;
      $idPeminjaman = null;
      $detailFinal = null; // Ini akan menyimpan data item yang RUSAK (untuk laporan)

      // 3. Update Database Detail (foto_sesudah & kondisi_akhir)
      // $idPeminjaman = null;
      if ($tipe == 'Sarana') {
         $detail = $this->detailSaranaModel->find($idDetail);
         $qtyAwal = (int) $detail['jumlah'];
         $idPeminjaman = $detail['id_peminjaman'];

         // Cek apakah perlu split? 
         // Syarat: Kondisi Rusak, Jumlah Valid, dan Jumlah Rusak < Jumlah Awal
         if ($kondisi != 'Baik' && $jumlahRusak > 0 && $jumlahRusak < $qtyAwal) {

            // 1. UPDATE Baris Lama (Menjadi Barang Baik/Sisa)
            $sisaBaik = $qtyAwal - $jumlahRusak;
            $this->detailSaranaModel->update($idDetail, [
               'jumlah'        => $sisaBaik,
               'kondisi_akhir' => 'Baik',
               // Foto sesudah opsional untuk yang baik, atau biarkan null
            ]);

            // 2. INSERT Baris Baru (Khusus Barang Rusak)
            $newDetailData = $detail; // Copy data lama
            unset($newDetailData['id_detail_sarana']); // Hapus ID lama agar auto-increment

            $newDetailData['jumlah']        = $jumlahRusak;
            $newDetailData['kondisi_akhir'] = $kondisi;
            $newDetailData['foto_sesudah']  = $pathFoto;

            // Simpan baris baru
            $this->detailSaranaModel->insert($newDetailData);

            // Ambil ID baris baru tersebut untuk referensi laporan (opsional)
            // Tapi untuk laporan, kita butuh data arraynya
            $detailFinal = $newDetailData;
            // Pastikan ID sarana terbawa
            $detailFinal['id_sarana'] = $detail['id_sarana'];
         } else {
            // SKENARIO NORMAL (Semua Baik ATAU Semua Rusak)
            $this->detailSaranaModel->update($idDetail, [
               'foto_sesudah'  => $pathFoto,
               'kondisi_akhir' => $kondisi,
            ]);

            // Data untuk laporan adalah data yang baru diupdate
            $detailFinal = $this->detailSaranaModel->find($idDetail);
         }
      } else {
         // --- LOGIKA PRASARANA (Tidak ada split karena Qty biasanya 1) ---
         $detail = $this->detailPrasaranaModel->find($idDetail);
         $this->detailPrasaranaModel->update($idDetail, [
            'foto_sesudah'  => $pathFoto,
            'kondisi_akhir' => $kondisi,
         ]);
         $idPeminjaman = $detail['id_peminjaman'];
         $detailFinal = $this->detailPrasaranaModel->find($idDetail);
         // Prasarana jumlah default 1 jika tidak ada kolom jumlah
         $detailFinal['jumlah'] = 1;
      }

      // --- LOGIKA OTOMATIS LAPOR KERUSAKAN ---
      // Jika kondisi bukan 'Baik', buat laporan otomatis
      if ($kondisi != 'Baik') {

         $laporanModel = $this->laporanModel;

         // Siapkan Data Laporan
         $dataLaporan = [
            'id_pelapor'          => $userId,
            'tipe_aset'           => ucfirst($tipe),
            // Judul dinamis: "Laporan Otomatis (5 Unit Rusak Berat)"
            'judul_laporan'       => "Laporan Otomatis Pengembalian ({$detailFinal['jumlah']} Unit $kondisi)",
            'deskripsi_kerusakan' => "Barang dikembalikan dengan status $kondisi sejumlah {$detailFinal['jumlah']} unit. Foto bukti terlampir pada detail pengembalian.",
            'bukti_foto'          => $pathFoto,
            'status_laporan'      => 'Diajukan',
            'id_sarana'           => ($tipe == 'Sarana') ? $detailFinal['id_sarana'] : null,
            'id_prasarana'        => ($tipe == 'Prasarana') ? $detailFinal['id_prasarana'] : null,
            'jumlah'              => $detailFinal['jumlah'] // Ini sekarang akurat (misal: 5)
         ];

         $laporanModel->save($dataLaporan);

         return redirect()->back()->with('message', "Pengembalian untuk sarana yang rusak diproses. Laporan kerusakan otomatis dibuat untuk {$detailFinal['jumlah']} unit yang rusak. Harap upload foto untuk sarana dengan kondisi baik");
      }

      // 4. Cek Apakah SEMUA barang dalam transaksi ini sudah dikembalikan?
      // Jika semua detail sudah punya 'foto_sesudah', maka update status Global
      if ($this->checkAllItemsReturned($idPeminjaman)) {
         // Opsional: Update status ke 'Selesai' otomatis, 
         // ATAU biarkan tetap 'Dipinjam' sampai Admin memverifikasi fisik barang (Recommended).
         // Di sini kita biarkan user menunggu verifikasi admin.
      }

      return redirect()->back()->with('message', 'Sarana / Prasarana berhasil dikembalikan. Menunggu verifikasi admin.');
   }

   // Helper function private
   private function checkAllItemsReturned($idPeminjaman)
   {
      // Hitung item yang foto_sesudah-nya masih NULL
      $countSarana = $this->detailSaranaModel
         ->where('id_peminjaman', $idPeminjaman)
         ->where('foto_sesudah', null)
         ->countAllResults();

      // ... hitung prasarana ...

      return ($countSarana == 0); // Return true jika semua sudah dikembalikan
   }

   // Helper: Cek apakah prasarana booked di rentang tanggal
   private function isPrasaranaBooked($idPrasarana, $start, $end)
   {
      // Cari di detail_peminjaman_prasarana yang terhubung ke peminjaman aktif
      // Logika: (StartA <= EndB) and (EndA >= StartB)
      $booked = $this->detailPrasaranaModel
         ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_prasarana.id_peminjaman')
         ->where('detail_peminjaman_prasarana.id_prasarana', $idPrasarana)
         ->whereIn('peminjaman.status_peminjaman_global', ['Disetujui', 'Dipinjam']) // Status aktif
         ->where('peminjaman.tgl_pinjam_dimulai <=', $end)
         ->where('peminjaman.tgl_pinjam_selesai >=', $start)
         ->countAllResults();

      return $booked > 0;
   }

   /**
    * Helper untuk mengecek apakah user kena blokir
    * @return \CodeIgniter\HTTP\RedirectResponse|null Kembali redirect jika diblokir, null jika aman.
    */
   private function checkBlockStatus()
   {
      $userId = auth()->user()->id;

      // Panggil method yang baru kita buat di Model
      $overdueCount = $this->peminjamanModel->hasOverdueLoans($userId);

      if ($overdueCount > 0) {
         // Pesan yang lebih informatif mengenai aturan 3 hari
         $pesan = "⛔ <b>AKUN ANDA DIBLOKIR SEMENTARA.</b><br><br>" .
            "Sistem mendeteksi ada <b>$overdueCount peminjaman</b> yang belum dikembalikan lebih dari <b>3 hari</b> setelah tanggal selesai.<br>" .
            "Sesuai kebijakan, Anda tidak dapat mengajukan pinjaman baru sampai barang tersebut dikembalikan.";

         return redirect()->to(site_url('peminjam/histori-peminjaman'))
            ->with('error', $pesan);
      }

      return null;
   }

   /**
    * API Endpoint untuk cek ketersediaan via AJAX
    */
   public function checkPrasaranaAvailability($idPrasarana)
   {
      // 1. Cek apakah request valid
      $start = $this->request->getGet('start');
      $end   = $this->request->getGet('end');

      if (!$start || !$end) {
         return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal tidak valid']);
      }

      // 2. Gunakan helper isPrasaranaBooked yang sudah ada
      // Helper ini melakukan query (StartA <= EndB) and (EndA >= StartB)
      if ($this->isPrasaranaBooked($idPrasarana, $start, $end)) {

         // Ambil detail peminjaman yang bentrok untuk pesan error
         $clash = $this->detailPrasaranaModel
            ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_prasarana.id_peminjaman')
            ->where('detail_peminjaman_prasarana.id_prasarana', $idPrasarana)
            ->whereIn('peminjaman.status_peminjaman_global', ['Disetujui', 'Dipinjam'])
            ->where('peminjaman.tgl_pinjam_dimulai <=', $end)
            ->where('peminjaman.tgl_pinjam_selesai >=', $start)
            ->first();

         $kegiatan = $clash['kegiatan'] ?? 'Kegiatan Lain';
         $tglMulai = date('d M', strtotime($clash['tgl_pinjam_dimulai']));
         $tglSelesai = date('d M', strtotime($clash['tgl_pinjam_selesai']));

         return $this->response->setJSON([
            'status' => 'booked',
            'message' => "Bentrok: $kegiatan ($tglMulai - $tglSelesai)"
         ]);
      }

      // 3. Jika tidak ada bentrok
      return $this->response->setJSON(['status' => 'available']);
   }
}
