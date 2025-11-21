<?php

namespace App\Models\Sarpras;

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

   public function getSaranaForKatalog($kode_sarana = false)
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
      if ($kode_sarana !== false) {
         return $builder->where('sarana.kode_sarana', $kode_sarana)->first();
      }

      // Jika tidak, kembalikan semua (findAll)
      return $builder->findAll();
   }

   public function getNamaSarana($id_sarana)
   {
      return $this->select('nama_sarana')->where('id_sarana', $id_sarana)->first();
   }
}
