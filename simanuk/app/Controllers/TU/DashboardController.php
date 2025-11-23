<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = auth()->getProvider();
   }

   public function index()
   {
      $user = auth()->user();

      // Data Dummy Statistik TU
      $stats = [
         'menunggu_verifikasi' => 5, // Peminjaman baru yang butuh persetujuan
         'sedang_dipinjam'     => 12, // Barang yang sedang di luar
         'laporan_rusak'       => 3,  // Laporan kerusakan baru
         'total_aset'          => 145 // Total item inventaris
      ];

      // Data Dummy: Peminjaman yang butuh verifikasi segera
      $pendingApprovals = [
         [
            'id' => 1,
            'peminjam' => 'BEM Fakultas Teknik',
            'barang'   => 'Sound System Portable',
            'tgl_ajukan' => '28 Mei 2024',
            'kegiatan' => 'Rapat Pleno',
            'status'   => 'Menunggu Verifikasi'
         ],
         [
            'id'=> 2,
            'peminjam' => 'Himpunan Mahasiswa Sipil',
            'barang'   => 'Aula Gedung B',
            'tgl_ajukan' => '27 Mei 2024',
            'kegiatan' => 'Seminar Nasional',
            'status'   => 'Menunggu Verifikasi'
         ],
         [
            'id'=> 3,
            'peminjam' => 'Robotics Club',
            'barang'   => 'Lab Komputer Dasar',
            'tgl_ajukan' => '26 Mei 2024',
            'kegiatan' => 'Pelatihan Arduino',
            'status'   => 'Menunggu Persetujuan'
         ],
      ];

      $data = [
         'title'            => 'Dashboard TU',
         'user'             => $user,
         'stats'            => $stats,
         'pendingApprovals' => $pendingApprovals,
         'showSidebar'      => true, 
      ];

      return view('tu/dashboard_view', $data);
   }
}     