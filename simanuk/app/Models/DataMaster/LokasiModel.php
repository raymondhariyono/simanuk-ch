<?php

namespace App\Models\DataMaster;

use CodeIgniter\Model;

class LokasiModel extends Model
{
   protected $table = 'lokasi';
   protected $primaryKey = 'id_lokasi';
   protected $useAutoIncrement = true;
   protected $allowedFields = ['nama_lokasi', 'alamat'];
}
