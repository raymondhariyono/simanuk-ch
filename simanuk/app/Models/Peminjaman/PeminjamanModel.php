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

   // KONSTANTA STATUS PEMINJAMAN GLOBAL
   public const STATUS_DIAJUKAN   = 'Diajukan';
   public const STATUS_DISETUJUI  = 'Disetujui';
   public const STATUS_DIPINJAM   = 'Dipinjam';
   public const STATUS_SELESAI    = 'Selesai';
   public const STATUS_DITOLAK    = 'Ditolak';
   public const STATUS_DIBATALKAN = 'Dibatalkan';

   // Helper untuk mengambil history per user
   public function getHistoryByUser($userId)
   {
      return $this->where('id_peminjam', $userId)
         ->orderBy('created_at', 'DESC')
         ->findAll();
   }
}
