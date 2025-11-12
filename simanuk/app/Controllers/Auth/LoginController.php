<?php

namespace App\Controllers\Auth;

use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;

class LoginController extends ShieldLogin
{
   protected $helpers = ['url', 'form'];

   // Gunakan view kustom
   protected $views = [
      'login' => 'auth/login',
   ];

   // Override redirect setelah login sukses
   protected function getLoginRedirect(): string
   {
      $user = auth()->user();

      if ($user->inGroup('Admin')) {
         return '/admin/dashboard';
      } elseif ($user->inGroup('TU')) {
         return '/tu/dashboard';
      } elseif ($user->inGroup('Peminjam')) {
         return '/peminjam/dashboard';
      } elseif ($user->inGroup('Pimpinan')) {
         return '/pimpinan/dashboard';
      }

      return '/'; // fallback
   }


   // Override redirect setelah logout
   protected function getLogoutRedirect(): string
   {
      return '/login';
   }
}
