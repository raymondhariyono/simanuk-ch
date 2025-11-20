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

         $data = [
            'title' => 'Detail Sarana',
            'sarana' => $sarana,
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
