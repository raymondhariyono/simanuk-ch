<?php

namespace App\Models\Peminjaman;

use CodeIgniter\Model;

class DetailPeminjamanSaranaModel extends Model
{
   protected $table            = 'detail_peminjaman_sarana';
   protected $primaryKey       = 'id_detail_sarana';
   protected $allowedFields    = [
      'id_peminjaman',
      'id_sarana',
      'jumlah',
      'foto_sebelum',
      'foto_sesudah',
      'kondisi_awal',
      'kondisi_akhir',
      'catatan'
   ];
   protected $useTimestamps    = true;
}
