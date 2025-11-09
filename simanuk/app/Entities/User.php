<?php

namespace App\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{
   protected $attributes = [
      'username'     => null,
      'nama_lengkap' => null,
      'email'        => null,
      'no_hp'        => null,
      'id_role'      => null,
      'status'       => null,
      'active'       => true,
   ];

   protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
