<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class SarprasController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
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
               'name' => 'Sarpras',
            ]
         ]
      ];

      return view('peminjam/sarpras_view', $data);
   }
}
