<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\DataMaster\KategoriModel;
use App\Models\DataMaster\LokasiModel;
use App\Services\InventarisService;

class InventarisasiController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;

   protected $inventarisService;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();

      $this->inventarisService = new InventarisService();
   }

   public function index()
   {
      // 1. Ambil Parameter GET (Query String)
      $filters = [
         'keyword'  => $this->request->getGet('keyword'),
         'kategori' => $this->request->getGet('kategori'),
         'lokasi'   => $this->request->getGet('lokasi'),
      ];

      $sarana = $this->inventarisService->getSaranaFiltered($filters, 3);
      $prasarana = $this->inventarisService->getPrasaranaFiltered($filters, 3);

      $data = [
         'title' => 'Katalog Sarpras',
         'actionUrl' => site_url('admin/inventaris'),
         'sarana' => $sarana, // 8 per page
         'pager_sarana' => $this->inventarisService->getSaranaPager(),
         'prasarana' => $prasarana, // 8 per page
         'pager_prasarana' => $this->inventarisService->getPrasaranaPager(),

         // Data untuk Dropdown Filter
         'kategoriList' => $this->kategoriModel->findAll(),
         'lokasiList'   => $this->lokasiModel->findAll(),

         // Kirim balik filter agar input tidak reset
         'filters' => $filters,
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
