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

   /**
    * Mengecek apakah user memiliki peminjaman yang terlambat dikembalikan.
    * Syarat Terlambat:
    * 1. Status Global = 'Dipinjam' (Artinya barang masih di user)
    * 2. Tanggal Selesai < Waktu Sekarang (Sudah lewat tenggat)
    * * @param int $userId
    * @return int Jumlah transaksi yang overdue
    */
   public function hasOverdueLoans(int $userId): int
   {
      return $this->where('id_peminjam', $userId)
         ->where('status_peminjaman_global', self::STATUS_DIPINJAM) // Pastikan pakai konstanta 'Dipinjam'
         ->where('tgl_pinjam_selesai <', date('Y-m-d H:i:s'))
         ->countAllResults();
   }
}
