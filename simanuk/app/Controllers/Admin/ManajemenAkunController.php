<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExtendedUserModel;

class ManajemenAkunController extends BaseController
{
   protected $userModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->userModel = auth()->getProvider();
      $this->userModel = new ExtendedUserModel;
   }

   public function index()
   {
      $users = $this->userModel->getAllUserWithRole();

      dd($users);

      $data = [
         'title' => 'Manajemen Akun',
         'users' => $users,
         'showSidebar' => true, // flag untuk sidebar
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Manajemen Akun',
            ]
         ]
      ];

      return view('admin/manajemen_akun_view', $data);
   }

   public function create() {}
   public function update() {}

   public function delete() {}
}
