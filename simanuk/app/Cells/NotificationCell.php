<?php

namespace App\Cells;

use App\Models\Peminjaman\PeminjamanModel;

class NotificationCell
{
   public function renderBadge()
   {
      // 1. Cek apakah user login (Safety Check)
      if (!function_exists('auth') || !auth()->loggedIn()) {
         return '';
      }

      // 2. Hitung data 'Diajukan' langsung dari Database
      $model = new PeminjamanModel();
      // Menggunakan cache 60 detik agar tidak memberatkan database setiap reload (Opsional)
      // $count = cache()->remember('pending_count', 60, function() use ($model) {
      //     return $model->where('status_peminjaman_global', 'Diajukan')->countAllResults();
      // });

      // Tanpa cache (Realtime):
      $count = $model->where('status_peminjaman_global', 'Diajukan')->countAllResults();

      // 3. Jika 0, jangan tampilkan apa-apa
      if ($count === 0) {
         return '';
      }

      // 4. Return HTML Badge
      return '<span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full shadow-sm">' . $count . '</span>';
   }
}
