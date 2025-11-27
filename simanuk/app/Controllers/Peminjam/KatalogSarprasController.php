<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\DataMaster\KategoriModel;
use App\Models\DataMaster\LokasiModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\FotoAsetModel;
use App\Services\InventarisService;

class KatalogSarprasController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;
   protected $fotoAsetModel;

   protected $inventarisService;

   public function __construct()
   {
      $this->saranaModel    = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel   = new LokasiModel();
      $this->fotoAsetModel = new FotoAsetModel();

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

      $sarana = $this->inventarisService->getSaranaFiltered($filters, 8);
      $prasarana = $this->inventarisService->getPrasaranaFiltered($filters, 8);

      $data = [
         'title' => 'Katalog Sarpras',
         'actionUrl' => site_url('peminjam/sarpras/filter'),
         'sarana' => $sarana,
         'pager_sarana' => $this->inventarisService->getSaranaPager(),
         'prasarana' => $prasarana,
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
               'name' => 'Sarpras',
            ]
         ]
      ];


      return view('peminjam/sarpras_view', $data);
   }

   public function detail($kode)
   {
      // Coba cari sebagai sarana terlebih dahulu
      $sarana = $this->saranaModel->getSaranaForKatalog($kode);

      // Jika sarana ditemukan
      if ($sarana) {
         // Proses field JSON 'spesifikasi'
         if (!empty($sarana['spesifikasi'])) {
            $decodedSpesifikasi = @json_decode($sarana['spesifikasi'], true);
            $sarana['spesifikasi'] = (json_last_error() === JSON_ERROR_NONE) ? $decodedSpesifikasi : [];
         } else {
            $sarana['spesifikasi'] = [];
         }

         $fotoSarana = $this->fotoAsetModel->getBySarana($sarana['id_sarana']);

         $data = [
            'title' => 'Detail Sarana',
            'sarana' => $sarana,
            'fotoSarana' => $fotoSarana,
            'breadcrumbs' => [
               ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
               ['name' => 'Sarpras', 'url' => site_url('peminjam/sarpras')],
               ['name' => $sarana['nama_sarana']],
            ]
         ];

         return view('peminjam/detail_sarana_view', $data);
      }

      // Jika tidak ditemukan sebagai sarana, coba cari sebagai prasarana
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog($kode);

      $fotoPrasarana = $this->fotoAsetModel->getByPrasarana($prasarana['id_prasarana']);

      // Jika prasarana ditemukan
      if ($prasarana) {
         // Proses field JSON 'fasilitas'
         if (!empty($prasarana['fasilitas'])) {
            $decodedFasilitas = @json_decode($prasarana['fasilitas'], true);
            $prasarana['fasilitas'] = (json_last_error() === JSON_ERROR_NONE) ? $decodedFasilitas : [];
         } else {
            $prasarana['fasilitas'] = [];
         }

         $data = [
            'title' => 'Detail Prasarana',
            'prasarana' => $prasarana,
            'fotoPrasarana' => $fotoPrasarana,
            'breadcrumbs' => [
               ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
               ['name' => 'Sarpras', 'url' => site_url('peminjam/sarpras')],
               ['name' => $prasarana['nama_prasarana']],
            ]
         ];
         // TODO: Buat view 'peminjam/detail_prasarana_view' untuk menampilkan detail prasarana.
         return view('peminjam/detail_prasarana_view', $data);
      }

      // Jika sarana maupun prasarana tidak ditemukan
      throw new \CodeIgniter\Exceptions\PageNotFoundException('Sarpras dengan kode ' . $kode . ' tidak ditemukan');
   }
}
