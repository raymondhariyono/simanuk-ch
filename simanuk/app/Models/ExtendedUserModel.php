<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel;

class ExtendedUserModel extends UserModel
{
   protected $allowedFields = [
      'email',
      'username',
      'password_hash',
      'active',
      'id_role',
      'nama_lengkap',
      'organisasi',
      'kontak',
      'created_at',
      'updated_at',
   ];
}
