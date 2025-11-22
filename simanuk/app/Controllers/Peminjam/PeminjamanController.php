<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel; // Uncomment jika sudah menangani prasarana

class PeminjamanController extends BaseController
{
   protected $peminjamanModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;

   protected $saranaModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->peminjamanModel = new PeminjamanModel();
      $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
   }

   /**
    * Menampilkan Form Pengajuan Peminjaman
    */
   public function new()
   {
      $data = [
         'title' => 'Ajukan Peminjaman Baru',
         'sarana' => $this->saranaModel
            ->where('status_ketersediaan', 'Tersedia')
            ->where('jumlah >', 1)
            ->findAll(),
         // 'prasarana' => ... (jika ada)
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
            ['name' => 'Peminjaman', 'url' => site_url('peminjam/histori-peminjaman')],
            ['name' => 'Buat Pengajuan']
         ]
      ];

      return view('peminjam/peminjaman/create_view', $data);
   }

   /**
    * Proses Simpan Peminjaman (CREATE)
    */
   public function create()
   {
      // 1. Validasi Input Header
      if (!$this->validate([
         'kegiatan'           => 'required|min_length[3]',
         'tgl_pinjam_dimulai' => 'required|valid_date',
         'tgl_pinjam_selesai' => 'required|valid_date',
         // Validasi Array Item (Pastikan minimal ada 1 barang dipilih)
         'items.sarana'       => 'required',
         'items.jumlah'       => 'required',
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Validasi Logika Tanggal
      $tglMulai = $this->request->getPost('tgl_pinjam_dimulai');
      $tglSelesai = $this->request->getPost('tgl_pinjam_selesai');

      if (strtotime($tglSelesai) < strtotime($tglMulai)) {
         return redirect()->back()->withInput()->with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
      }

      // Hitung Durasi (Hari)
      $diff = strtotime($tglSelesai) - strtotime($tglMulai);
      $durasi = round($diff / (60 * 60 * 24)) + 1; // +1 agar hari H dihitung 1 hari

      // Ambil User ID
      $userId = auth()->user()->id;

      // 2. Database Transaction
      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // A. Insert Header Peminjaman
         $dataPeminjaman = [
            'id_peminjam'              => $userId,
            'kegiatan'                 => $this->request->getPost('kegiatan'),
            'tgl_pengajuan'            => date('Y-m-d H:i:s'),
            'tgl_pinjam_dimulai'       => $tglMulai,
            'tgl_pinjam_selesai'       => $tglSelesai,
            'durasi'                   => $durasi,
            'status_verifikasi'        => 'Pending',
            'status_persetujuan'       => 'Pending',
            'status_peminjaman_global' => 'Diajukan',
            'tipe_peminjaman'          => 'Peminjaman',
            'keterangan'               => $this->request->getPost('keterangan'),
         ];

         $this->peminjamanModel->insert($dataPeminjaman);
         $peminjamanId = $this->peminjamanModel->getInsertID();

         // B. Insert Detail Item (Looping)
         $itemsSarana = $this->request->getPost('items')['sarana']; // Array ID Sarana
         $itemsJumlah = $this->request->getPost('items')['jumlah']; // Array Jumlah

         foreach ($itemsSarana as $index => $idSarana) {
            $jumlahPinjam = $itemsJumlah[$index];

            // Validasi Stok Sederhana (Opsional: Bisa diperketat dengan cek tanggal)
            $sarana = $this->saranaModel->find($idSarana);
            if ($sarana['jumlah'] < $jumlahPinjam) {
               throw new \Exception("Stok untuk " . $sarana['nama_sarana'] . " tidak mencukupi.");
            }

            $this->detailSaranaModel->insert([
               'id_peminjaman' => $peminjamanId,
               'id_sarana'     => $idSarana,
               'jumlah'        => $jumlahPinjam,
               'kondisi_awal'  => 'Baik', // Default asumsi
            ]);
         }

         $db->transComplete();

         if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data peminjaman.');
         }

         return redirect()->to(site_url('peminjam/histori-peminjaman'))->with('message', 'Pengajuan peminjaman berhasil dibuat.');
      } catch (\Exception $e) {
         $db->transRollback();
         return redirect()->back()->withInput()->with('error', $e->getMessage());
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
      if ($peminjaman['status_peminjaman_global'] != 'Diajukan') {
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

      if ($peminjaman['status_peminjaman_global'] != 'Diajukan') {
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
}
