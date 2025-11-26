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

   /**
    * Scope Filter untuk pencarian dan penyaringan data Prasarana
    */
   public function filter(array $params)
   {
      // 1. Filter Keyword (Search)
      if (!empty($params['keyword'])) {
         $keyword = $params['keyword'];
         $this->groupStart()
            ->like('nama_prasarana', $keyword)
            ->orLike('kode_prasarana', $keyword)
            ->groupEnd();
      }

      // 2. Filter Kategori
      if (!empty($params['kategori']) && $params['kategori'] != 'Semua') {
         $this->where('prasarana.id_kategori', $params['kategori']);
      }

      // 3. Filter Lokasi
      if (!empty($params['lokasi']) && $params['lokasi'] != 'Semua') {
         $this->where('prasarana.id_lokasi', $params['lokasi']);
      }

      // Join Tabel Referensi
      $this->select('prasarana.*, kategori.nama_kategori, lokasi.nama_lokasi');
      $this->join('kategori', 'kategori.id_kategori = prasarana.id_kategori', 'left');
      $this->join('lokasi', 'lokasi.id_lokasi = prasarana.id_lokasi', 'left');

      return $this;
   }

   // ...
   public function filterHistory(array $params, int $userId)
   {
      // Base Query: Hanya milik user login
      $this->where('id_peminjam', $userId);

      // 1. Filter Tab (Aktif vs Riwayat)
      // Logika ini dipindahkan dari Controller ke Model agar rapi
      if (isset($params['tab']) && $params['tab'] === 'riwayat') {
         $this->whereIn('status_peminjaman_global', ['Selesai', 'Ditolak', 'Dibatalkan']);
      } else {
         // Default: Aktif
         $this->whereIn('status_peminjaman_global', ['Diajukan', 'Disetujui', 'Dipinjam']);
      }

      // 2. Filter Keyword (Cari Kegiatan)
      if (!empty($params['keyword'])) {
         $this->groupStart()
            ->like('kegiatan', $params['keyword'])
            ->orLike('keterangan', $params['keyword'])
            ->groupEnd();
      }

      // 3. Filter Tanggal (Opsional)
      if (!empty($params['tanggal'])) {
         $this->where('DATE(tgl_pinjam_dimulai)', $params['tanggal']);
      }

      return $this->orderBy('created_at', 'DESC');
   }
}
