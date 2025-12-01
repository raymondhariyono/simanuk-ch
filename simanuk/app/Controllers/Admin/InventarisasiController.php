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

      // 2. Tentukan Tab Aktif (PENTING untuk UX saat pagination)
      // Default ke 'sarana', tapi jika user sedang klik page prasarana, pindah ke tab prasarana
      $activeTab = 'sarana';
      if ($this->request->getGet('tab') === 'prasarana' || $this->request->getGet('page_prasarana')) {
         $activeTab = 'prasarana';
      }

      $sarana = $this->inventarisService->getSaranaFiltered($filters, 8);
      $prasarana = $this->inventarisService->getPrasaranaFiltered($filters, 8);

      $data = [
         'title' => 'Katalog Sarpras',
         'actionUrl' => site_url('admin/inventaris'),
         'sarana' => $sarana, 
         'pager_sarana' => $this->inventarisService->getSaranaPager(),
         'prasarana' => $prasarana, 
         'pager_prasarana' => $this->inventarisService->getPrasaranaPager(),

         // Data untuk Dropdown Filter
         'kategoriList' => $this->kategoriModel->findAll(),
         'lokasiList'   => $this->lokasiModel->findAll(),

         // Kirim balik filter agar input tidak reset
         'filters' => $filters,
         'activeTab' => $activeTab,
         'showSidebar' => true, // flag untuk sidebar
      ];

      return view('admin/inventarisasi_view', $data);
   }
}
