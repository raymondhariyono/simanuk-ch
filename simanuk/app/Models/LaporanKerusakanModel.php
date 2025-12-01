<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanKerusakanModel extends Model
{
   protected $table            = 'laporan_kerusakan';
   protected $primaryKey       = 'id_laporan';
   protected $useAutoIncrement = true;
   protected $allowedFields    = [
      'id_pelapor',
      'tipe_aset',
      'id_peminjaman', // FK nullable
      'id_sarana', // FK nullable
      'id_prasarana', // FK nullable
      'judul_laporan',
      'jumlah',
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

   // Method untuk mengambil data lengkap dengan join
   public function getLaporanLengkap()
   {
      return $this->select('laporan_kerusakan.*, users.username, users.nama_lengkap as nama_pelapor, auth_groups_users.group as role')
         // Join ke Users berdasarkan id_pelapor (bisa Admin atau User biasa)
         ->join('users', 'users.id = laporan_kerusakan.id_pelapor')
         // Join ke Auth Groups untuk tahu role pelapor (Admin/Mahasiswa)
         ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
         ->orderBy('laporan_kerusakan.created_at', 'DESC')
         ->findAll();
   }
}
