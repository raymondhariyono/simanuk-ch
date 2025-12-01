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
         ->select('laporan_kerusakan.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = laporan_kerusakan.id_peminjam')
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
         'title' => 'Kelola Laporan Kerusakan (TU)',
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

      // Validasi Input
      if (!in_array($statusBaru, ['Diproses', 'Selesai', 'Ditolak'])) {
         return redirect()->back()->with('error', 'Status tidak valid.');
      }

      // 1. Update Laporan Kerusakan
      $this->laporanModel->update($idLaporan, [
         'status_laporan' => $statusBaru,
         'tindak_lanjut'  => $tindakLanjut,
         'updated_at'     => date('Y-m-d H:i:s')
      ]);

      // 2. SINKRONISASI STATUS MASTER DATA (Best Practice Integrity)
      // Jika 'Diproses' -> Set aset jadi 'Tidak Tersedia' (Maintenance)
      // Jika 'Selesai'  -> Set aset jadi 'Tersedia' (Fixed)

      $statusAset = null;
      if ($statusBaru == 'Diproses') {
         $statusAset = 'Tidak Tersedia'; // Atau 'Dalam Perbaikan' jika ada enum-nya
      } elseif ($statusBaru == 'Selesai') {
         $statusAset = 'Tersedia';
      }

      if ($statusAset) {
         if ($laporan['tipe_aset'] == 'Sarana') {
            $this->saranaModel->update($laporan['id_sarana'], ['status_ketersediaan' => $statusAset]);
         } else {
            $this->prasaranaModel->update($laporan['id_prasarana'], ['status_ketersediaan' => $statusAset]);
         }
      }

      return redirect()->back()->with('message', "Laporan diperbarui. Status aset disesuaikan menjadi '$statusAset'.");
   }
}
