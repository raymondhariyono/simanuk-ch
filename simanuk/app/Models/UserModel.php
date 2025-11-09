<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
   protected $table = 'users';
   protected $primaryKey = 'id';

   protected $allowedFields = [
      'username',
      'nama_lengkap',
      'email',
      'no_hp',
      'id_role',
      'status',
      'active',
      'last_active',
   ];
}
