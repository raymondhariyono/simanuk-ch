<?php

namespace App\Cells;

use App\Services\PeminjamanService;

class NotificationCell
{
   public function renderBadge()
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
}
