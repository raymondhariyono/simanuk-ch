<?php

namespace App\Models\Peminjaman;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
   protected $table            = 'peminjaman';
   protected $primaryKey       = 'id_peminjaman';
   protected $useAutoIncrement = true;
   protected $allowedFields    = [
      'id_peminjam',
      'id_admin_verifikator',
      'id_tu_approver',
      'kegiatan',
      'tgl_pengajuan',
      'tgl_pinjam_dimulai',
      'tgl_pinjam_selesai',
      'durasi',
      'status_verifikasi',
      'status_persetujuan',
      'status_peminjaman_global',
      'tipe_peminjaman',
      'keterangan'
   ];
   protected $useTimestamps    = true; // created_at, updated_at

   // Helper untuk mengambil history per user
   public function getHistoryByUser($userId)
   {
      return $this->where('id_peminjam', $userId)
         ->orderBy('created_at', 'DESC')
         ->findAll();
   }
}
