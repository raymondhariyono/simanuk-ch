<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\GroupModel;
use \App\Models\ExtendedUserModel;

class DashboardController extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = new ExtendedUserModel();
   }

   public function index()
   {
      $user = auth()->user();
      $userData = $this->userModel->getUserWithRole($user->id);

      $data = [
         'title' => 'Dashboard Admin',
      ];

      return view('admin/dashboard_view', $data);
   }
}
