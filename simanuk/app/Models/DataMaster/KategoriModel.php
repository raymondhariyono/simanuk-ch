<?php

namespace App\Models\DataMaster;

use CodeIgniter\Model;

class KategoriModel extends Model
{
   protected $table = 'kategori';
   protected $primaryKey = 'id_kategori';
   protected $useAutoIncrement = true;
   protected $allowedFields = ['nama_kategori'];
}
