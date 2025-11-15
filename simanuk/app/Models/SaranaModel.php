<?php

namespace App\Models;

use CodeIgniter\Model;

class SaranaModel extends Model
{
   protected $table = 'sarana';
   protected $primaryKey = 'id_sarana';

   protected $useAutoIncrement = true;
   protected $returnType = 'array';

   protected $useSoftDeletes   = false; // Sesuaikan dengan kebutuhan (di migrasi tidak ada deleted_at)
   protected $protectFields    = true;

   protected $allowedFields    = [
      'id_prasarana',
      'id_kategori',
      'id_lokasi',
      'nama_sarana',
      'kode_sarana',
      'jumlah',
      'spesifikasi',
      'deskripsi',
      'kondisi',
      'status_ketersediaan'
   ];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';

   public function getSaranaForKatalog($id = null)
   {
      $builder = $this->select('sarana.*, kategori.nama_kategori, lokasi.nama_lokasi, lokasi.alamat, prasarana.nama_prasarana');

      // join tabel kategori
      $builder->join('kategori', 'kategori.id_kategori = sarana.id_kategori');

      // join tabel lokasi
      $builder->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi');

      // join kategori dan lokasi ke tabel prasarana
      // karena id_prasarana bisa null, gunakan LEFT JOIN
      $builder->join('prasarana', 'prasarana.id_prasarana = sarana.id_sarana', 'LEFT');

      // Jika ID diberikan, kembalikan satu baris (first)
      if ($id !== null) {
         return $builder->where('sarana.id_sarana', $id)->first();
      }

      // Jika tidak, kembalikan semua (findAll)
      return $builder->findAll();
   }

   public function getSaranaWithFieldKategori($kode_sarana = false)
   {
      if ($kode_sarana == false) {
         return $this->where;
      }
   }

   public function getInventarisById($id_event)
   {
      return $this->where(['id_event' => $id_event])->first();
   }
}
