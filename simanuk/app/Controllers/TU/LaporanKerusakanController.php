<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\LaporanKerusakanModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class LaporanKerusakanController extends BaseController
{
   protected $laporanModel;
   protected $saranaModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->laporanModel = new LaporanKerusakanModel();
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
   }

   public function index()
   {
      // Ambil semua laporan + Join ke User untuk info pelapor
      $laporan = $this->laporanModel
         ->select('laporan_kerusakan.*, users.nama_lengkap, users.organisasi, roles.nama_role') // <--- Tambah nama_role
         ->join('users', 'users.id = laporan_kerusakan.id_pelapor') // Join ke user
         ->join('roles', 'roles.id_role = users.id_role')             // Join ke role
         ->orderBy('created_at', 'DESC')
         ->findAll();

      // Pisahkan data untuk Tab Sarana & Prasarana
      $laporanSarana = [];
      $laporanPrasarana = [];

      foreach ($laporan as $row) {
         // Ambil detail nama aset
         if ($row['tipe_aset'] == 'Sarana') {
            $aset = $this->saranaModel->find($row['id_sarana']);
            $row['nama_aset'] = $aset['nama_sarana'] ?? 'Item / Sarana Terhapus';
            $row['kode_aset'] = $aset['kode_sarana'] ?? '-';
            $laporanSarana[] = $row;
         } else {
            $aset = $this->prasaranaModel->find($row['id_prasarana']);
            $row['nama_aset'] = $aset['nama_prasarana'] ?? 'Prasarana Terhapus';
            $row['kode_aset'] = $aset['kode_prasarana'] ?? '-';
            $laporanPrasarana[] = $row;
         }
      }

      $data = [
         'title' => 'Kelola Laporan Kerusakan',
         'laporanSarana' => $laporanSarana,
         'laporanPrasarana' => $laporanPrasarana,
         'showSidebar' => true,
      ];

      // Mengarah ke view khusus TU
      return view('tu/laporan/kelola_laporan_kerusakan_view', $data);
   }

   /**
    * Proses Update Status Laporan & Sinkronisasi Stok
    */
   public function updateStatus($idLaporan)
   {
      $laporan = $this->laporanModel->find($idLaporan);
      if (!$laporan) return redirect()->back()->with('error', 'Data tidak ditemukan.');

      $statusBaru    = $this->request->getPost('status_laporan');
      $tindakLanjut  = $this->request->getPost('tindak_lanjut');

      $statusLama    = $laporan['status_laporan'];
      $jumlahRusak   = $laporan['jumlah'] ?? 1;

      // Validasi Input
      if (!in_array($statusBaru, ['Diproses', 'Selesai', 'Ditolak'])) {
         return redirect()->back()->with('error', 'Status tidak valid.');
      }

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 1. Update Data Laporan
         $this->laporanModel->update($idLaporan, [
            'status_laporan' => $statusBaru,
            'tindak_lanjut'  => $tindakLanjut,
            'updated_at'     => date('Y-m-d H:i:s')
         ]);

         // 2. LOGIKA STOK SARANA (Hanya untuk Sarana)
         if ($laporan['tipe_aset'] == 'Sarana') {
            $sarana = $this->saranaModel->find($laporan['id_sarana']);
            $stokSekarang = $sarana['jumlah'];

            // A. Transisi: DIAJUKAN -> DIPROSES (Barang masuk bengkel/gudang rusak)
            // Kurangi stok tersedia
            if ($statusLama == 'Diajukan' && $statusBaru == 'Diproses') {
               $stokSekarang -= $jumlahRusak;
            }

            // B. Transisi: DIPROSES -> SELESAI (Barang selesai diperbaiki)
            // Kembalikan ke stok tersedia
            elseif ($statusLama == 'Diproses' && $statusBaru == 'Selesai') {
               $stokSekarang += $jumlahRusak;
            }

            // C. Transisi: DIPROSES -> DITOLAK (Barang dikembalikan ke stok tanpa perbaikan)
            elseif ($statusLama == 'Diproses' && $statusBaru == 'Ditolak') {
               $stokSekarang += $jumlahRusak;
            }

            // Pastikan stok tidak minus
            if ($stokSekarang < 0) $stokSekarang = 0;

            // Tentukan Status Ketersediaan Baru
            $statusKetersediaan = ($stokSekarang > 0) ? 'Tersedia' : 'Tidak Tersedia';

            // Update Master Sarana
            $this->saranaModel->update($laporan['id_sarana'], [
               'jumlah' => $stokSekarang,
               'status_ketersediaan' => $statusKetersediaan
            ]);
         }
         // 3. LOGIKA PRASARANA (Tetap Status Based karena jumlahnya 1)
         else {
            $statusAset = null;
            if ($statusBaru == 'Diproses') {
               $statusAset = 'Perawatan'; // Gunakan 'Perawatan' agar lebih spesifik
            } elseif ($statusBaru == 'Selesai' || $statusBaru == 'Ditolak') {
               $statusAset = 'Tersedia';
            }

            if ($statusAset) {
               $this->prasaranaModel->update($laporan['id_prasarana'], ['status_ketersediaan' => $statusAset]);
            }
         }

         $db->transComplete();

         if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal mengupdate status.');
         }

         return redirect()->back()->with('message', 'Status laporan diperbarui. Stok/Status aset telah disesuaikan.');
      } catch (\Exception $e) {
         $db->transRollback();
         return redirect()->back()->with('error', $e->getMessage());
      }
   }
}
