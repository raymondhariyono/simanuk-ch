<?php

namespace App\Controllers;

use App\Models\UserModel;

class Admin extends BaseController
{
   protected $userModel;

   public function __construct()
   {
      $this->userModel = new UserModel();
   }

   public function dashboard()
   {
      return redirect()->to('admin/dashboard');
   }

   public function info()
   {
      $data = [
         'title' => 'Dashboard Info',
      ];

      return view('admin/info', $data);
   }
}
