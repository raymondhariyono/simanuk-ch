<?php

namespace App\Controllers\Admin;

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
      // dd($user->id);
      // $userData = $this->userModel->getUserWithRole($user->id);

      $data = [
         'title' => 'Dashboard Admin',
      ];

      return view('admin/dashboard_view', $data);
   }
}
