<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
   public function index()
   {
      $data = [
         'title' => 'Dashboard Peminjam',
      ];
      return view('peminjam/dashboard_view', $data);
   }
}
