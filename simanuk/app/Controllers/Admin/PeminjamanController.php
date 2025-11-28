<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\Sarpras\SaranaModel;
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

      $this->peminjamanService = new PeminjamanService();
   }

   public function index()
   {
      // 1. Ambil semua data (Header + User info)
      // Urutkan: Diajukan paling atas (Prioritas), lalu tanggal terbaru
      $allData = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.username')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->orderBy("FIELD(status_peminjaman_global, 'Diajukan', 'Disetujui', 'Dipinjam', 'Selesai', 'Ditolak', 'Dibatalkan')")
         ->orderBy('created_at', 'DESC')
         ->findAll();

      // tab-tab dalam view
      $pending = [];  // Tab 1: Verifikasi Baru
      $active  = [];  // Tab 2: Sedang Berjalan (Disetujui/Dipinjam)
      $history = [];  // Tab 3: Riwayat

      foreach ($allData as $row) {
         $status = $row['status_peminjaman_global'];

         if ($status == 'Diajukan') {
            $pending[] = $row;
         } elseif (in_array($status, ['Disetujui', 'Dipinjam'])) {
            $active[] = $row;
         } else {
            $history[] = $row;
         }
      }

      // 1. JALANKAN AUTO CANCEL
      $canceledCount = $this->peminjamanService->autoCancelExpiredLoans();

      // Beri notifikasi flash message jika ada yang dibatalkan
      if ($canceledCount > 0) {
         session()->setFlashdata('info', "Sistem otomatis membatalkan $canceledCount pengajuan yang kedaluwarsa.");
      }

      // Ambil data peminjaman + Data User Peminjam
      // Kita urutkan agar status 'Diajukan' muncul paling atas
      $dataPeminjaman = $this->peminjamanModel
         ->select('peminjaman.*, users.username, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->orderBy("FIELD(status_peminjaman_global, 'Diajukan', 'Disetujui', 'Dipinjam', 'Selesai', 'Ditolak', 'Dibatalkan')")
         ->orderBy('created_at', 'DESC')
         ->findAll();

      $data = [
         'title' => 'Kelola Peminjaman',
         'peminjaman' => $dataPeminjaman,
         // tab-tab
         'pendingLoans' => $pending,
         'activeLoans'  => $active,
         'historyLoans' => $history,
         'showSidebar' => true,
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('admin/dashboard')],
            ['name' => 'Transaksi Peminjaman']
         ]
      ];

      return view('admin/peminjaman/index', $data);
   }

   /**
    * Menampilkan detail item yang dipinjam untuk diverifikasi
    */
   public function detail($id)
   {
      // 1. Ambil Header Peminjaman
      $peminjaman = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.kontak')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('peminjaman.id_peminjaman', $id)
         ->first();

      if (!$peminjaman) {
         return redirect()->to(site_url('admin/peminjaman'))->with('error', 'Data tidak ditemukan.');
      }

      // 2. Ambil Detail Barang (Sarana)
      $itemsSarana = $this->detailSaranaModel
         ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
         ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
         ->where('id_peminjaman', $id)
         ->findAll();

      // 3. Ambil Detail Ruangan (Prasarana)
      $itemsPrasarana = $this->detailPrasaranaModel
         ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
         ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
         ->where('id_peminjaman', $id)
         ->findAll();

      $data = [
         'title' => 'Detail Verifikasi',
         'peminjaman' => $peminjaman,
         'itemsSarana' => $itemsSarana,
         'itemsPrasarana' => $itemsPrasarana,
         'showSidebar' => true,
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('admin/dashboard')],
            ['name' => 'Peminjaman', 'url' => site_url('admin/peminjaman')],
            ['name' => 'Detail Verifikasi']
         ]
      ];

      return view('admin/peminjaman/detail', $data);
   }

   /**
    * Proses SETUJUI (Approve)
    * Mengurangi Stok Sarana & Update Status
    */
   public function approve($id)
   {
      $peminjaman = $this->peminjamanModel->find($id);

      if (!$peminjaman || $peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIAJUKAN) {
         return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
      }

      // Start Database Transaction
      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 1. Ambil Detail Item Sarana dan Prasarana
         $itemSarana = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
         $itemsPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();

         foreach ($itemSarana as $item) {
            // Ambil data sarana terkini (untuk cek stok real-time)
            $sarana = $this->saranaModel->find($item['id_sarana']);

            // Validasi Stok
            if ($sarana['jumlah'] < $item['jumlah']) {
               // Rollback & Error jika stok tiba-tiba habis (race condition)
               throw new \Exception("Stok '{$sarana['nama_sarana']}' tidak mencukupi. Tersedia: {$sarana['jumlah']}");
            }

            // logika utama: menghitung stok baru
            $newStok = $sarana['jumlah'] - $item['jumlah'];

            // update data stok
            $updateData = ['jumlah' => $newStok];

            // jika stok habis atau 0 (nol), artinya telah habis dipinjam, update status menjadi 'Dipinjam'
            if ($newStok <= 0) {
               $updateData['status_ketersediaan'] = 'Dipinjam';
            }

            $this->saranaModel->update($item['id_sarana'], $updateData);
         }

         foreach ($itemsPrasarana as $item) {
            // Set status master prasarana jadi 'Dipinjam' agar tampil merah di katalog
            $this->prasaranaModel->update($item['id_prasarana'], ['status_ketersediaan' => 'Dipinjam']);
         }

         // 2. Update Status Peminjaman
         $this->peminjamanModel->update($id, [
            'status_verifikasi'        => PeminjamanModel::STATUS_DISETUJUI,
            'status_persetujuan'       => PeminjamanModel::STATUS_DISETUJUI,
            'status_peminjaman_global' => PeminjamanModel::STATUS_DISETUJUI, // Siap diambil
            'id_admin_verifikator'     => auth()->user()->id // Catat siapa yang approve
         ]);

         $db->transComplete();

         if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses data. Silakan coba lagi.');
         }

         return redirect()->to(site_url('admin/peminjaman'))->with('message', 'Peminjaman berhasil disetujui. Stok telah diperbarui.');
      } catch (\Exception $e) {
         $db->transRollback();
         return redirect()->back()->with('error', $e->getMessage());
      }
   }

   /**
    * Proses TOLAK (Reject)
    */
   public function reject($id)
   {
      $peminjaman = $this->peminjamanModel->find($id);

      if (!$peminjaman || $peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIAJUKAN) {
         return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
      }

      $alasan = $this->request->getPost('alasan_tolak');

      // Update Status Menjadi Ditolak
      // Kita simpan alasan penolakan di kolom 'keterangan' atau field khusus jika ada
      // Di sini saya gabungkan ke kolom keterangan agar user bisa baca
      $keteranganBaru = $peminjaman['keterangan'] . " [DITOLAK: $alasan]";

      $this->peminjamanModel->update($id, [
         'status_verifikasi'        => PeminjamanModel::STATUS_DITOLAK,
         'status_persetujuan'       => PeminjamanModel::STATUS_DITOLAK,
         'status_peminjaman_global' => PeminjamanModel::STATUS_DITOLAK,
         'keterangan'               => $keteranganBaru,
         'id_admin_verifikator'     => auth()->user()->id
      ]);

      return redirect()->to(site_url('admin/peminjaman'))->with('message', 'Peminjaman telah ditolak.');
   }

   /**
    * Fitur untuk menolak foto bukti (Sebelum/Sesudah)
    * @param string $tipe 'sarana' atau 'prasarana'
    * @param string $jenisFoto 'sebelum' atau 'sesudah'
    * @param int $idDetail
    */
   public function tolakFoto($tipe, $jenisFoto, $idDetail)
   {
      $alasan = $this->request->getPost('alasan');
      if (empty($alasan)) {
         return redirect()->back()->with('error', 'Harap isi alasan penolakan foto.');
      }

      // Tentukan Model & Kolom
      $model = ($tipe == 'sarana') ? $this->detailSaranaModel : $this->detailPrasaranaModel;
      $kolomFoto = ($jenisFoto == 'sebelum') ? 'foto_sebelum' : 'foto_sesudah';

      // 1. Ambil Data Lama untuk Hapus File Fisik
      $item = $model->find($idDetail);
      $pathLama = $item[$kolomFoto];

      if ($pathLama && is_file(FCPATH . $pathLama)) {
         unlink(FCPATH . $pathLama);
      }

      // 2. Update Database: Kosongkan Foto & Isi Catatan
      $updateData = [
         $kolomFoto => null, // Reset foto jadi null
         'catatan_penolakan' => "Foto $jenisFoto DITOLAK: " . $alasan
      ];

      // Khusus jika menolak foto 'sebelum', status global mungkin perlu dikembalikan
      // Tapi agar simple, kita cukup reset fotonya saja. 
      // Logika di View User akan mendeteksi 'foto_sebelum' kosong -> Munculkan tombol upload.

      $model->update($idDetail, $updateData);

      return redirect()->back()->with('message', 'Foto berhasil ditolak. User diminta upload ulang.');
   }
}
