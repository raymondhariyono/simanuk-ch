<?php

namespace App\Cells;

use App\Models\LaporanKerusakanModel;
use App\Models\Peminjaman\PeminjamanModel;
use App\Services\PeminjamanService;

class NotificationCell
{
   protected $peminjamanModel;
   protected $laporanModel;

   public function __construct()
   {
      $this->peminjamanModel = new PeminjamanModel();
      $this->laporanModel = new LaporanKerusakanModel();
   }

   public function renderBadgePeminjaman()
   {
      if (!function_exists('auth') || !auth()->loggedIn()) {
         return '';
      }

      // Panggil Service
      $service = new PeminjamanService();
      $count = $service->countPendingLoans();

      if ($count === 0) {
         return '';
      }

      return '<span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full shadow-sm">' . $count . '</span>';
   }

   /**
    * Badge untuk Menu Pengembalian
    * Menghitung jumlah item yang SEDANG DIPINJAM (status: 'Dipinjam')
    * Karena ini adalah item yang harus diproses pengembaliannya nanti.
    */
   public function badgePengembalian()
   {
      // Logika: Menghitung transaksi yang statusnya masih 'Dipinjam' (Belum kembali)
      // Anda bisa menyesuaikan ini jika ingin menghitung yang 'Overdue' saja
      $count = $this->peminjamanModel
         ->where('status_peminjaman_global', 'Dipinjam')
         ->countAllResults();

      return $this->renderBadge($count, 'blue'); // Warna biru untuk peminjaman aktif
   }

   /**
    * Badge untuk Menu Laporan Kerusakan
    * Menghitung laporan dengan status 'Diajukan'
    */
   public function badgeLaporan()
   {
      $count = $this->laporanModel
         ->where('status_laporan', 'Diajukan')
         ->countAllResults();

      return $this->renderBadge($count, 'red'); // Warna merah untuk kerusakan (urgent)
   }

   // Helper sederhana untuk render HTML badge (agar tidak duplikasi kode)
   private function renderBadge($count, $color = 'red')
   {
      if ($count <= 0) {
         return '';
      }

      // Tentukan kelas warna Tailwind
      $colorClass = ($color === 'blue')
         ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
         : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';

      return sprintf(
         '<span class="inline-flex items-center justify-center px-2 py-0.5 ms-3 text-xs font-medium %s rounded-full">%d</span>',
         $colorClass,
         $count
      );
   }
}
