<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Services\PeminjamanService;


class PeminjamanController extends BaseController
{
   protected $peminjamanModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;

   protected $saranaModel;
   protected $prasaranaModel;

   protected $peminjamanService;

   public function __construct()
   {
      $this->peminjamanModel = new PeminjamanModel();
      $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

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
         'prasarana' => $this->prasaranaModel->where('status_ketersediaan', 'Tersedia')->findAll(),
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
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
            'rules' => 'uploaded[foto_bukti]|max_size[foto_bukti,2048]|is_image[foto_bukti]',
            'errors' => [
               'uploaded' => 'Foto wajib diupload.',
               'max_size' => 'Ukuran foto terlalu besar (Max 2MB).',
               'is_image' => 'File harus berupa gambar.'
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

      if ($tipe == 'Sarana') {
         $detail = $this->detailSaranaModel->find($idDetail);
         $this->detailSaranaModel->update($idDetail, ['foto_sebelum' => $pathFoto]);
         $idPeminjaman = $detail['id_peminjaman'];
      } else {
         // Logic Prasarana
         $detail = $this->detailPrasaranaModel->find($idDetail); // Pastikan model di-load
         $this->detailPrasaranaModel->update($idDetail, ['foto_sebelum' => $pathFoto]);
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

      return redirect()->back()->with('message', 'Foto Bukti Sebelum Berhasil di Upload dan Barang berhasil diambil.');
   }

   public function kembalikanItem($tipe, $idDetail)
   {
      // 1. Validasi
      if (!$this->validate([
         'foto_sesudah'  => 'uploaded[foto_sesudah]|is_image[foto_sesudah]|max_size[foto_sesudah,2048]',
         'kondisi_akhir' => 'required'
      ])) {
         return redirect()->back()->withInput()->with('error', 'Foto wajib diupload & valid.');
      }

      // 2. Upload Foto
      $file = $this->request->getFile('foto_sesudah');
      $newName = $file->getRandomName();
      $file->move(FCPATH . 'uploads/peminjaman/bukti_akhir', $newName);
      $pathFoto = 'uploads/peminjaman/bukti_akhir/' . $newName;

      // 3. Update Database
      // Kita hanya update detail item, tidak mengubah status header 'Selesai' (itu tugas Admin)
      if ($tipe == 'sarana') {
         $this->detailSaranaModel->update($idDetail, [
            'foto_sesudah'  => $pathFoto,
            'kondisi_akhir' => $this->request->getPost('kondisi_akhir')
         ]);
      } else if ($tipe == 'prasarana') {
         $this->detailPrasaranaModel->update($idDetail, [
            'foto_sesudah'  => $pathFoto,
            'kondisi_akhir' => $this->request->getPost('kondisi_akhir')
         ]);
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
         'foto_bukti' => 'uploaded[foto_bukti]|is_image[foto_bukti]|max_size[foto_bukti,2048]',
         'kondisi_akhir' => 'required'
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

      // 3. Update Database Detail (foto_sesudah & kondisi_akhir)
      $idPeminjaman = null;
      if ($tipe == 'Sarana') {
         $detail = $this->detailSaranaModel->find($idDetail);
         $this->detailSaranaModel->update($idDetail, [
            'foto_sesudah' => $pathFoto,
            'kondisi_akhir' => $this->request->getPost('kondisi_akhir')
         ]);
         $idPeminjaman = $detail['id_peminjaman'];
      } else {
         $detail = $this->detailPrasaranaModel->find($idDetail);
         $this->detailPrasaranaModel->update($idDetail, [
            'foto_sesudah' => $pathFoto,
            'kondisi_akhir' => $this->request->getPost('kondisi_akhir')
         ]);
         $idPeminjaman = $detail['id_peminjaman'];
      }

      // 4. Cek Apakah SEMUA barang dalam transaksi ini sudah dikembalikan?
      // Jika semua detail sudah punya 'foto_sesudah', maka update status Global
      if ($this->checkAllItemsReturned($idPeminjaman)) {
         // Opsional: Update status ke 'Selesai' otomatis, 
         // ATAU biarkan tetap 'Dipinjam' sampai Admin memverifikasi fisik barang (Recommended).
         // Di sini kita biarkan user menunggu verifikasi admin.
      }

      return redirect()->back()->with('message', 'Sarana berhasil dikembalikan. Menunggu verifikasi admin.');
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
}
