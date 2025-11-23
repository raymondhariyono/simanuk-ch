<?php

namespace App\Controllers\TU;

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
      // Mengambil data untuk ditampilkan (Logic sama dengan Admin)
      $sarana = $this->saranaModel->getSaranaForKatalog();
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      $data = [
         'title' => 'Kelola Laporan Kerusakan (TU)',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'showSidebar' => true,
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('tu/dashboard')
            ],
            [
               'name' => 'Laporan Kerusakan',
            ]
         ]
      ];

      // Mengarah ke view khusus TU
      return view('tu/kelola_laporan_kerusakan_view', $data);
   }

   // Method placeholder (disamakan dengan controller Admin saat ini)
   public function create() {}
   public function update() {}
   public function delete() {}
}