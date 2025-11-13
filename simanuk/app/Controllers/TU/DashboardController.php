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

      $data = [
         'title' => 'Dashboard TU',
      ];

      return view('tu/dashboard_view', $data);
   }
}
