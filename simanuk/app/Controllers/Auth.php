<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Shield\Config\Auth as ShieldAuth;
use CodeIgniter\Controller;

class Auth extends Controller 
{
   /**
    * URL to redirect to after a successful login.
    */
   public string $loginRedirect = '/';

   /**
    * URL to redirect to after a successful logout.
    */
   public string $logoutRedirect = '/login';

   public function redirect()
   {
      echo "Hello World";
      // return view('auth/redirect');
   }
}
