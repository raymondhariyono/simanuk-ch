<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\InventarisModel;

class KatalogController extends BaseController
{
   protected $inventarisModel;

   public function __construct()
   {
      $this->inventarisModel = new InventarisModel();
   }

   public function index()
   {
      // $barang = $this->inventarisModel->getInventaris();

      $data = [
         'title' => 'Katalog Inventarisasi',
         // 'barang' => $barang,
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
