<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DataMaster\KategoriModel;
use App\Models\DataMaster\LokasiModel;

class MasterDataController extends BaseController
{
   protected $kategoriModel;
   protected $lokasiModel;

   public function __construct()
   {
      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel   = new LokasiModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Kelola Data Master',
         'kategori' => $this->kategoriModel->findAll(),
         'lokasi' => $this->lokasiModel->findAll(),
         'showSidebar' => true,
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('admin/dashboard')],
            ['name' => 'Data Master']
         ]
      ];

      return view('admin/master_data/index', $data);
   }

   // --- CRUD KATEGORI ---

   public function storeKategori()
   {
      if (!$this->validate(['nama_kategori' => 'required|min_length[3]|is_unique[kategori.nama_kategori]'])) {
         return redirect()->back()->withInput()->with('error_kategori', $this->validator->getErrors());
      }

      $this->kategoriModel->save([
         'nama_kategori' => $this->request->getPost('nama_kategori')
      ]);

      return redirect()->back()->with('message', 'Kategori berhasil ditambahkan.');
   }

   public function deleteKategori($id)
   {
      // Opsional: Cek dulu apakah kategori ini dipakai di Sarana/Prasarana
      // Jika dipakai, jangan hapus (Best Practice Integrity)

      $this->kategoriModel->delete($id);
      return redirect()->back()->with('message', 'Kategori berhasil dihapus.');
   }

   // --- CRUD LOKASI ---

   public function storeLokasi()
   {
      if (!$this->validate([
         'nama_lokasi' => 'required|min_length[3]|is_unique[lokasi.nama_lokasi]',
         'alamat'      => 'permit_empty'
      ])) {
         return redirect()->back()->withInput()->with('error_lokasi', $this->validator->getErrors());
      }

      $this->lokasiModel->save([
         'nama_lokasi' => $this->request->getPost('nama_lokasi'),
         'alamat'      => $this->request->getPost('alamat')
      ]);

      return redirect()->back()->with('message', 'Lokasi berhasil ditambahkan.');
   }

   public function deleteLokasi($id)
   {
      $this->lokasiModel->delete($id);
      return redirect()->back()->with('message', 'Lokasi berhasil dihapus.');
   }
}
