<?php

declare(strict_types=1);

namespace Config;

use CodeIgniter\Shield\Config\Auth as ShieldAuth;

class Auth extends ShieldAuth
{
   /**
    * URL to redirect to after a successful login.
    */
   public string $loginRedirect = '/auth/redirect';

   /**
    * URL to redirect to after a successful logout.
    */
   public string $logoutRedirect = '/login';
}
