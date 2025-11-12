<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
   protected $table            = 'roles';
   protected $primaryKey       = 'id_role';
   protected $useAutoIncrement = true;
   protected $returnType       = 'array';
   protected $useSoftDeletes   = false;
   protected $allowedFields    = ['nama_role'];

   protected bool $allowEmptyInserts = false;

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';
   protected $deletedField  = 'deleted_at';
}
