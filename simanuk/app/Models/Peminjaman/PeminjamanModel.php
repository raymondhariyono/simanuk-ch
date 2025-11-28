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
      // Hitung batas waktu toleransi: Waktu Sekarang dikurangi 3 Hari
      // Artinya: Kita mencari peminjaman yang 'tgl_pinjam_selesai'-nya 
      // lebih kecil (lebih lampau) daripada 3 hari yang lalu.
      $toleranceLimit = date('Y-m-d H:i:s', strtotime('-3 days'));

      return $this->where('id_peminjam', $userId)
         ->where('status_peminjaman_global', self::STATUS_DIPINJAM) // Pastikan konstanta 'Dipinjam'
         ->where('tgl_pinjam_selesai <', $toleranceLimit) // Logika Grace Period
         ->countAllResults();
   }

   /**
    * Mengambil jadwal peminjaman aset tertentu dalam bulan tertentu.
    * * @param int $idAset ID dari Sarana atau Prasarana
    * @param string $jenisAset 'sarana' atau 'prasarana'
    * @param int $bulan
    * @param int $tahun
    * @return array List tanggal yang sudah di-booking
    */
   public function getAssetSchedule(int $idAset, string $jenisAset, int $bulan, int $tahun): array
   {
      // Tentukan tabel detail dan foreign key berdasarkan jenis aset
      $detailTable = ($jenisAset === 'sarana') ? 'detail_peminjaman_sarana' : 'detail_peminjaman_prasarana';
      $fkColumn    = ($jenisAset === 'sarana') ? 'id_sarana' : 'id_prasarana';

      // Query Join: Peminjaman -> Detail -> Filter ID Aset & Status & Bulan
      $query = $this->select('peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai')
         ->join($detailTable, "$detailTable.id_peminjaman = peminjaman.id_peminjaman")
         ->where("$detailTable.$fkColumn", $idAset)
         // Hanya ambil status yang sudah "booking" (Disetujui atau Sedang Dipinjam)
         ->whereIn('peminjaman.status_peminjaman_global', ['Disetujui', 'Dipinjam'])
         // Filter agar query tidak terlalu berat, ambil yang beririsan dengan bulan ini
         ->groupStart()
         ->where("MONTH(peminjaman.tgl_pinjam_dimulai)", $bulan)
         ->where("YEAR(peminjaman.tgl_pinjam_dimulai)", $tahun)
         ->orWhere("MONTH(peminjaman.tgl_pinjam_selesai)", $bulan)
         ->where("YEAR(peminjaman.tgl_pinjam_selesai)", $tahun)
         ->groupEnd()
         ->findAll();

      // Mapping hasil ke array tanggal (expand range tanggal)
      // Hasil: ['2025-11-01' => true, '2025-11-02' => true, ...]
      $bookedDates = [];

      foreach ($query as $row) {
         $start = strtotime($row['tgl_pinjam_dimulai']);
         $end   = strtotime($row['tgl_pinjam_selesai']);

         // Loop dari tanggal mulai sampai selesai untuk menandai kalender
         while ($start <= $end) {
            // Hanya masukkan tanggal yang sesuai bulan/tahun yang diminta
            if (date('n', $start) == $bulan && date('Y', $start) == $tahun) {
               $bookedDates[date('Y-m-d', $start)] = true;
            }
            $start = strtotime('+1 day', $start);
         }
      }

      return $bookedDates;
   }
}
