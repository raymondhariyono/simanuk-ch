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

   public function detail($kode_sarana)
   {
      // cek apakah terdapat sarana & prasarana dari kodenya
      $sarana = $this->saranaModel->getSaranaForKatalog($kode_sarana);

      if (!$sarana) {
         throw new \CodeIgniter\Exceptions\PageNotFoundException('Sarana dengan kode ' . $kode_sarana . ' tidak ditemukan');
      }
      // $prasarana = $this->prasaranaModel->getPrasaranaForKatalog($kode_prasarana);
      // if (!$prasarana) {
      //    throw new \CodeIgniter\Exceptions\PageNotFoundException('Prasarana dengan kode ' . $kode_prasarana . ' tidak ditemukan');
      // }

      $data = [
         'title' => 'Detail Sarpras',
         'sarana' => $sarana,
         // 'prasarana' => $prasarana,
         'showSidebar' => true, // flag untuk sidebar
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Sarpras',
            ],
            [
               'name' => $sarana['nama_sarana'],
            ],
         ]
      ];

      return view('peminjam/detail_sarpras_view', $data);
   }
}
