<?php

namespace App\Services;

use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\FotoAsetModel;
use App\Models\LaporanKerusakanModel;

class InventarisService
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $fotoAsetModel;

   protected $laporanModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
      $this->fotoAsetModel = new FotoAsetModel();
      $this->laporanModel = new LaporanKerusakanModel();
   }

   public function getSaranaFiltered(array $filters, int $perPage = 8)
   {
      // 1. Ambil Data Ter-Paginate
      $saranaList = $this->saranaModel->filter($filters)->paginate($perPage, 'sarana');

      $laporan = $this->laporanModel;

      // 2. Attach Foto (Logic dipindah ke sini)
      // Kita loop data yang sudah dipaginate
      if (!empty($saranaList)) {
         foreach ($saranaList as &$item) {
            // ambil foto
            $foto = $this->fotoAsetModel->where('id_sarana', $item['id_sarana'])->first();
            $item['url_foto'] = $foto ? $foto['url_foto'] : null;

            // hitung jumlah sarana yang diperbaiki (Status: Diproses)
            $rusak = $laporan
               ->where('id_sarana', $item['id_sarana'])
               ->where('status_laporan', 'Diproses')
               ->selectSum('jumlah')
               ->first();

            $item['stok_rusak'] = $rusak['jumlah'] ?? 0;
         }
      }

      return $saranaList;
   }

   public function getPrasaranaFiltered(array $filters, int $perPage = 8)
   {
      // 1. Ambil Data Ter-Paginate
      $prasaranaList = $this->prasaranaModel->filter($filters)->paginate($perPage, 'prasarana');

      // 2. Attach Foto
      if (!empty($prasaranaList)) {
         foreach ($prasaranaList as &$item) {
            $foto = $this->fotoAsetModel
               ->where('id_prasarana', $item['id_prasarana'])
               ->first();

            $item['url_foto'] = $foto ? $foto['url_foto'] : null;
         }
      }

      return $prasaranaList;
   }

   public function getSaranaPager()
   {
      return $this->saranaModel->pager;
   }
   public function getPrasaranaPager()
   {
      return $this->prasaranaModel->pager;
   }
}
