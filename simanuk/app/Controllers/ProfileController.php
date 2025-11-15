<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ProfileController extends BaseController
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
         'title' => 'Manajemen Akun Pengguna',
         'showSidebar' => false, // Flag untuk menyembunyikan sidebar
         'user' => $user,
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Profil Pengguna',
            ]
         ]
      ];

      return view('profile/profile_view', $data);
   }
}
