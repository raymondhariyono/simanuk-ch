<?php

namespace App\Models\Sarpras;

use CodeIgniter\Model;

class PrasaranaModel extends Model
{
   protected $table = 'prasarana';
   protected $primaryKey = 'id_prasarana';

   protected $useAutoIncrement = true;
   protected $returnType = 'array';

   protected $useSoftDeletes   = false; // Sesuaikan dengan kebutuhan (di migrasi tidak ada deleted_at)
   protected $protectFields    = true;

   protected $allowedFields    = [
      'id_kategori',    // FK
      'id_lokasi',      // FK
      'nama_prasarana',
      'kode_prasarana',
      'luas_ruangan',
      'kapasitas_orang',
      'jenis_ruangan',
      'fasilitas',
      'lantai',
      'tata_letak',
      'deskripsi',
      'status_ketersediaan'
   ];

   // Dates
   protected $useTimestamps = true;
   protected $dateFormat    = 'datetime';
   protected $createdField  = 'created_at';
   protected $updatedField  = 'updated_at';

   // DEFINISI KONSTANTA KETERSEDIAAN PRASARANA
   public const STATUS_TERSEDIA       = 'Tersedia';
   public const STATUS_DIPINJAM       = 'Dipinjam';
   public const STATUS_RENOVASI       = 'Renovasi';
   public const STATUS_TIDAK_TERSEDIA = 'Tidak Tersedia';

   public function getPrasaranaForKatalog($kode_prasarana = false)
   {
      $builder = $this->select('prasarana.*, kategori.nama_kategori, lokasi.nama_lokasi, lokasi.alamat, prasarana.nama_prasarana');

      // join tabel kategori
      $builder->join('kategori', 'kategori.id_kategori = prasarana.id_kategori');

      // join tabel lokasi
      $builder->join('lokasi', 'lokasi.id_lokasi = prasarana.id_lokasi');

      // // join kategori dan lokasi ke tabel praprasarana
      // // karena id_praprasarana bisa null, gunakan LEFT JOIN
      // $builder->join('prasarana', 'prasarana.id_prasarana = prasarana.id_prasarana', 'LEFT');

      // Jika ID diberikan, kembalikan satu baris (first)
      if ($kode_prasarana !== false) {
         return $builder->where('prasarana.kode_prasarana', $kode_prasarana)->first();
      }

      // Jika tidak, kembalikan semua (findAll)
      return $builder->findAll();
   }
}
