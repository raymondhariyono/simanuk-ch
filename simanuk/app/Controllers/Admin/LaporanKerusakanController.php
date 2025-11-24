<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class LaporanKerusakanController extends BaseController
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
         'title' => 'Kelola Laporan Kerusakan',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'showSidebar' => true, // flag untuk sidebar
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Laporan Kerusakan',
            ]
         ]
      ];

      return view('admin/kelola_laporan_kerusakan_view', $data);
   }

   public function create()
   {
      
   }
   public function update()
   {

   }
   
   public function delete()
   {

   }
}
