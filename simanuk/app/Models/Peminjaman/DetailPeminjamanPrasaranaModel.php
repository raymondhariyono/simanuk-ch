<?php

namespace App\Models\Peminjaman;

use CodeIgniter\Model;

class DetailPeminjamanPrasaranaModel extends Model
{
   protected $table            = 'detail_peminjaman_prasarana';
   protected $primaryKey       = 'id_detail_prasarana';
   protected $allowedFields    = [
      'id_peminjaman',
      'id_prasarana',
      'foto_sebelum',
      'foto_sesudah',
      'kondisi_awal',
      'kondisi_akhir',
      'catatan',
      'catatan_penolakan'
   ];
   protected $useTimestamps    = true;
}
