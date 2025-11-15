<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\SaranaModel;

class KatalogController extends BaseController
{
   protected $saranaModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
   }

   public function index()
   {
      $sarana = $this->saranaModel->getSaranaForKatalog();

      $data = [
         'title' => 'Katalog Inventarisasi',
         'sarana' => $sarana,
         'showSidebar' => true, // flag untuk sidebar
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Katalog Inventarisasi',
            ]
         ]
      ];

      return view('peminjam/katalog_view', $data);
   }
}
