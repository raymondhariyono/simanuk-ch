<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\KategoriModel;
use App\Models\LokasiModel;

class PrasaranaController extends BaseController
{
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;

   public function __construct()
   {
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();
   }

   public function create()
   {
      $data = [
         'title' => 'Tambah Prasarana Baru',
         'prasarana' => $this->prasaranaModel->getPrasaranaForKatalog(),
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
               'name' => 'Tambah Prasarana',
            ]
         ]
      ];
      return view('admin/inventaris/prasarana/create_view', $data);
   }

   public function edit($id)
   {
      $prasarana = $this->prasaranaModel->find($id);
      if (!$prasarana) {
         throw new \CodeIgniter\Exceptions\PageNotFoundException('Prasarana dengan ID ' . $id . ' tidak ditemukan.');
      }

      $data = [
         'title' => 'Edit Data Prasarana',
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
               'name' => 'Edit Prasarana',
            ]
         ]
      ];

      return view('admin/inventaris/prasarana/edit_view', $data);
   }

   public function save()
   {
      // validasi input untuk tiap field pada form tambah event
      // rules untuk validasi input
      $rules = [
         'nama_prasarana' => [
            'rules' => 'required|is_unique[prasarana.nama_prasarana]',
            'errors' => [
               'required' => 'Nama prasarana wajib diisi',
               'is_unique' => 'Prasarana yang sama sudah terdaftar',
            ]
         ],
         'kode_prasarana' => [
            'rules' => 'required|is_unique[prasarana.kode_prasarana]',
            'errors' => [
               'required' => 'Kode prasarana harus diisi',
               'is_unique' => 'Kode prasarana yang sama sudah terdaftar',
            ]
         ],
         'luas_ruangan' => [
            'rules' => 'required|integer',
            'errors' => [
               'required' => 'Luas ruangan harus diisi (cm)',
               'integer' => 'Luas ruangan harus berupa angka (cm)',
            ]
         ],
         'kapasitas_orang' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kapasitas orang dalam ruangan harus diisi',
            ]
         ],
         'jenis_ruangan' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Kapasitas orang dalam ruangan harus diisi',
            ]
         ],
         'lantai' => [
            'rules' => 'required|integer',
            'errors' => [
               'required' => 'Lantai prasarana harus diisi',
               'integer' => 'Lantai prasarana harus berupa angka',
            ]
         ],
         'tata_letak' => [
            'rules' => 'required',
            'errors' => [
               'required' => 'Lantai prasarana harus diisi',
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

      // Ambil data fasilitas sebagai array dari input dengan nama "fasilitas[]".
      $fasilitas = $this->request->getPost('fasilitas') ?? [];
      // Filter untuk menghapus nilai fasilitas yang kosong dan reset index array.
      $fasilitas = array_values(array_filter($fasilitas, fn ($value) => trim($value) !== ''));

      $data = [
         'nama_prasarana'        => $this->request->getPost('nama_prasarana'),
         'kode_prasarana'        => $this->request->getPost('kode_prasarana'),
         'id_kategori'        => $this->request->getPost('id_kategori'),
         'id_lokasi'          => $this->request->getPost('id_lokasi'),
         'luas_ruangan'             => $this->request->getPost('luas_ruangan'),
         'kapasitas_orang'            => $this->request->getPost('kapasitas_orang'),
         'jenis_ruangan'            => $this->request->getPost('jenis_ruangan'),
         'lantai'            => $this->request->getPost('lantai'),
         'tata_letak'            => $this->request->getPost('tata_letak'),
         'status_ketersediaan' => $this->request->getPost('status_ketersediaan'),
         'deskripsi'          => $this->request->getPost('deskripsi'),
         'fasilitas'        => json_encode($fasilitas),
      ];

      // dd($data);

      $this->prasaranaModel->save($data);

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data berhasil disimpan.');
   }

   public function update($id)
   {
      // Aturan validasi
      $rules = [
         'nama_prasarana' => [
            'rules' => "required|is_unique[prasarana.nama_prasarana,id_prasarana,{$id}]",
            'errors' => [
               'required' => 'Nama prasarana wajib diisi',
               'is_unique' => 'Prasarana lain dengan nama yang sama sudah terdaftar',
            ]
         ],
         'kode_prasarana' => [
            'rules' => "required|is_unique[prasarana.kode_prasarana,id_prasarana,{$id}]",
            'errors' => [
               'required' => 'Kode prasarana harus diisi',
               'is_unique' => 'Kode prasarana lain yang sama sudah terdaftar',
            ]
         ],
         'luas_ruangan' => ['rules' => 'required|integer'],
         'kapasitas_orang' => ['rules' => 'required|integer'],
         'jenis_ruangan' => ['rules' => 'required'],
         'lantai' => ['rules' => 'required|integer'],
         'tata_letak' => ['rules' => 'required'],
         'deskripsi' => ['rules' => 'required'],
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Ambil data fasilitas sebagai array dari input dengan nama "fasilitas[]".
      $fasilitas = $this->request->getPost('fasilitas') ?? [];
      // Filter untuk menghapus nilai fasilitas yang kosong dan reset index array.
      $fasilitas = array_values(array_filter($fasilitas, fn ($value) => trim($value) !== ''));

      $data = [
         'nama_prasarana'        => $this->request->getPost('nama_prasarana'),
         'kode_prasarana'        => $this->request->getPost('kode_prasarana'),
         'id_kategori'           => $this->request->getPost('id_kategori'),
         'id_lokasi'             => $this->request->getPost('id_lokasi'),
         'luas_ruangan'          => $this->request->getPost('luas_ruangan'),
         'kapasitas_orang'       => $this->request->getPost('kapasitas_orang'),
         'jenis_ruangan'         => $this->request->getPost('jenis_ruangan'),
         'lantai'                => $this->request->getPost('lantai'),
         'tata_letak'            => $this->request->getPost('tata_letak'),
         'status_ketersediaan'   => $this->request->getPost('status_ketersediaan'),
         'deskripsi'             => $this->request->getPost('deskripsi'),
         'fasilitas'             => json_encode($fasilitas),
      ];

      $this->prasaranaModel->update($id, $data);

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data prasarana berhasil diperbarui.');
   }

   public function delete($id)
   {
      $this->prasaranaModel->delete($id);
      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data berhasil dihapus.');
   }
}
