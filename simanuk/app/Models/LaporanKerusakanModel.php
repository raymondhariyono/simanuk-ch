<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanKerusakanModel extends Model
{
   protected $table            = 'laporan_kerusakan';
   protected $primaryKey       = 'id_laporan';
   protected $useAutoIncrement = true;
   protected $allowedFields    = [
      'id_peminjam',
      'tipe_aset',
      'id_sarana', // FK nullable
      'id_prasarana', // FK nullable
      'judul_laporan',
      'deskripsi_kerusakan',
      'bukti_foto',
      'status_laporan',
      'tindak_lanjut'
   ];
   protected $useTimestamps    = true;

   // Helper untuk mengambil riwayat lengkap user
   public function getRiwayatUser($userId)
   {
      // Kita gunakan Query Builder manual untuk join kondisional (agak tricky tapi efisien)
      // Atau kita ambil raw data lalu map di controller (Eager Loading style)
      // Untuk simpelnya, kita ambil data dasar dulu:
      return $this->where('id_peminjam', $userId)
         ->orderBy('created_at', 'DESC')
         ->findAll();
   }
}
