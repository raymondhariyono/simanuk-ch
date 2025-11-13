<?php

namespace App\Controllers\Pimpinan;

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
      // $userData = $this->userModel->getUserWithRole($user->id);

      $data = [
         'title' => 'Dashboard Pimpinan',
      ];

      return view('pimpinan/dashboard_view', $data);
   }
}
