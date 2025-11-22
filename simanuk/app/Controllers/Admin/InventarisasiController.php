<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\KategoriModel;
use App\Models\LokasiModel;

class InventarisasiController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();
   }

   public function index()
   {
      $sarana = $this->saranaModel->getSaranaForKatalog();
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      $data = [
         'title' => 'Katalog Sarpras',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'showSidebar' => true, // flag untuk sidebar
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Kelola Inventarisasi',
            ]
         ]
      ];

      return view('admin/inventarisasi_view', $data);
   }
}
