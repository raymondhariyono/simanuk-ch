<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use \App\Entities\User;

class ExtendedUserModel extends ShieldUserModel
{
   protected $table = 'users';
   protected $primaryKey = 'id';
   protected $returnType = User::class;

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
