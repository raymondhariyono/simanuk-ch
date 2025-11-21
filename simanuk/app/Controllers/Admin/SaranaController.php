<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\KategoriModel;
use App\Models\LokasiModel;

class SaranaController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();
   }

   public function create()
   {
      $sarana = $this->saranaModel->getSaranaForKatalog();
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      $data = [
         'title' => 'Tambah Sarana Baru',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'showSidebar' => true,
         'kategori' => $this->kategoriModel->findAll(),
         'lokasi' => $this->lokasiModel->findAll(),
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Kelola Inventarisasi',
               'url' => site_url('admin/inventaris')
            ],
            [
               'name' => 'Tambah Sarana',
            ]
         ]
      ];
      return view('admin/inventaris/sarana/create_view', $data);
   }

   public function edit($id)
   {
      $sarana = $this->saranaModel->find($id);
      if (!$sarana) {
         throw new \CodeIgniter\Exceptions\PageNotFoundException('Sarana dengan ID ' . $id . ' tidak ditemukan.');
      }

      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      $data = [
         'title' => 'Edit Data Sarana',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'showSidebar' => true,
         'kategori' => $this->kategoriModel->findAll(),
         'lokasi' => $this->lokasiModel->findAll(),
         'breadcrumbs' => [
            [
               'name' => 'Beranda',
               'url' => site_url('admin/dashboard')
            ],
            [
               'name' => 'Kelola Inventarisasi',
               'url' => site_url('admin/inventaris')
            ],
            [
               'name' => 'Edit Sarana',
            ]
         ]
      ];
      return view('admin/inventaris/sarana/edit_view', $data);
   }

   public function save()
   {
      // validasi input untuk tiap field pada form tambah event
      // rules untuk validasi input
      $rules = [
         'nama_sarana' => [
            'rules' => 'required|is_unique[sarana.nama_sarana,id_sarana,{id_sarana}]',
            'errors' => [
               'required' => 'Nama sarana wajib diisi',
               'is_unique' => 'Sarana yang sama sudah terdaftar',
            ]
         ],
         'kode_sarana' => [
            'rules' => 'required|is_unique[sarana.kode_sarana,id_sarana,{id_sarana}]',
            'errors' => [
               'required' => 'Kode sarana harus diisi',
               'is_unique' => 'Kode sarana yang sama sudah terdaftar',
            ]
         ],
         'jumlah' => [
            'rules' => 'required|integer',
            'errors' => [
               'required' => 'Jumlah harus diisi',
               'integer' => 'Jumlah harus berupa angka',
            ]
         ],
         'kondisi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kondisi sarana harus diisi',
            ]
         ],
         'deskripsi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Deskripsi sarana harus diisi',
            ]
         ]
      ];

      // validasi input
      if (!$this->validate($rules)) {
         // pesan kesalahan disimpan 
         $validation = \Config\Services::validation();

         // input pengguna dan validasi yang didapat akan dikembalikan menjadi pesan
         return redirect()->back()->withInput()->with('validation', $validation);
      }

      // Ambil data spesifikasi dari form
      $spec_keys = $this->request->getPost('spesifikasi_key') ?? [];
      $spec_values = $this->request->getPost('spesifikasi_value') ?? [];

      $spesifikasi = [];
      foreach ($spec_keys as $index => $key) {
         // Pastikan kunci dan nilainya tidak kosong sebelum ditambahkan
         if (!empty($key) && isset($spec_values[$index]) && !empty($spec_values[$index])) {
            $spesifikasi[$key] = $spec_values[$index];
         }
      }

      $data = [
         'nama_sarana'        => $this->request->getPost('nama_sarana'),
         'kode_sarana'        => $this->request->getPost('kode_sarana'),
         'id_kategori'        => $this->request->getPost('id_kategori'),
         'id_lokasi'          => $this->request->getPost('id_lokasi'),
         'id_prasarana'       => $this->request->getPost('id_prasarana') ?: null, // bisa NULL
         'jumlah'             => $this->request->getPost('jumlah'),
         'kondisi'            => $this->request->getPost('kondisi'),
         'status_ketersediaan' => $this->request->getPost('status_ketersediaan'),
         'deskripsi'          => $this->request->getPost('deskripsi'),
         'spesifikasi'        => json_encode($spesifikasi),
      ];

      $this->saranaModel->save($data);

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data berhasil disimpan.');
   }

   public function update($id)
   {
      // Aturan validasi
      $rules = [
         'nama_sarana' => [
            // Abaikan pengecekan is_unique untuk record saat ini
            'rules' => "required|is_unique[sarana.nama_sarana,id_sarana,{$id}]",
            'errors' => [
               'required' => 'Nama sarana wajib diisi',
               'is_unique' => 'Sarana lain dengan nama yang sama sudah terdaftar',
            ]
         ],
         'kode_sarana' => [
            'rules' => "required|is_unique[sarana.kode_sarana,id_sarana,{$id}]",
            'errors' => [
               'required' => 'Kode sarana harus diisi',
               'is_unique' => 'Kode sarana lain yang sama sudah terdaftar',
            ]
         ],
         'jumlah' => [
            'rules' => 'required|integer',
            'errors' => [
               'required' => 'Jumlah harus diisi',
               'integer' => 'Jumlah harus berupa angka',
            ]
         ],
         'kondisi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kondisi sarana harus diisi',
            ]
         ],
         'deskripsi' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Deskripsi sarana harus diisi',
            ]
         ]
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Ambil data spesifikasi dari form
      $spec_keys = $this->request->getPost('spesifikasi_key') ?? [];
      $spec_values = $this->request->getPost('spesifikasi_value') ?? [];
      $spesifikasi = [];
      foreach ($spec_keys as $index => $key) {
         if (!empty($key) && isset($spec_values[$index]) && !empty($spec_values[$index])) {
            $spesifikasi[$key] = $spec_values[$index];
         }
      }

      $data = [
         'nama_sarana'        => $this->request->getPost('nama_sarana'),
         'kode_sarana'        => $this->request->getPost('kode_sarana'),
         'id_kategori'        => $this->request->getPost('id_kategori'),
         'id_lokasi'          => $this->request->getPost('id_lokasi'),
         'id_prasarana'       => $this->request->getPost('id_prasarana') ?: null,
         'jumlah'             => $this->request->getPost('jumlah'),
         'kondisi'            => $this->request->getPost('kondisi'),
         'status_ketersediaan' => $this->request->getPost('status_ketersediaan'),
         'deskripsi'          => $this->request->getPost('deskripsi'),
         'spesifikasi'        => json_encode($spesifikasi),
      ];

      $this->saranaModel->update($id, $data);

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data sarana berhasil diperbarui.');
   }

   public function delete($id)
   {
      // dd($this->saranaModel->getNamaSarana($id));
      $this->saranaModel->delete($id);
      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data ' . $this->saranaModel->getNamaSarana($id) . ' berhasil dihapus.');
   }
}