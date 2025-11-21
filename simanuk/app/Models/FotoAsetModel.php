<?php

namespace App\Models;

use CodeIgniter\Model;

class FotoAsetModel extends Model
{
   protected $table            = 'foto_aset';
   protected $primaryKey       = 'id_foto';
   protected $useAutoIncrement = true;
   protected $allowedFields    = [
      'id_sarana',
      'id_prasarana',
      'url_foto',
      'deskripsi'
   ];

   protected $useTimestamps    = true;
   protected $createdField = 'created_at';
   protected $updatedField     = 'updated_at';

   public function getAllSarana()
   {
      return $this->findAll();
   }

   // Helper untuk mengambil foto berdasarkan sarana
   public function getBySarana($id_sarana)
   {
      return $this->where('id_sarana', $id_sarana)->findAll();
   }

   // Helper untuk mengambil foto berdasarkan prasarana
   public function getByPrasarana($id_prasarana)
   {
      return $this->where('id_prasarana', $id_prasarana)->findAll();
   }
}
