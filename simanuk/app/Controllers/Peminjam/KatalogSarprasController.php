<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\FotoAsetModel;

class KatalogSarprasController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $fotoAsetModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->fotoAsetModel = new FotoAsetModel();
   }

   public function index()
   {
      $sarana = $this->saranaModel->getSaranaForKatalog();
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      foreach ($sarana as &$item) {
         // ambil foto berdasarkan id_sarana 
         $foto = $this->fotoAsetModel->where('id_sarana', $item['id_sarana'])->first();

         // jika ada, pakai url_foto, jika tidak pakai placeholder default
         $item['url_foto'] = $foto ? $foto['url_foto'] : null;
      }
      unset($item); // hapus referensi pointer

      foreach ($prasarana as &$item) {
         // Ambil 1 foto saja (first) berdasarkan id_prasarana
         $foto = $this->fotoAsetModel->where('id_prasarana', $item['id_prasarana'])->first();

         $item['url_foto'] = $foto ? $foto['url_foto'] : null;
      }
      unset($item);

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
