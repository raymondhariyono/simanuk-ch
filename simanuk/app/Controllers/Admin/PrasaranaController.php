<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\KategoriModel;
use App\Models\LokasiModel;
use App\Models\FotoAsetModel;

class PrasaranaController extends BaseController
{
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;
   protected $fotoAsetModel;

   public function __construct()
   {
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();
      $this->fotoAsetModel = new FotoAsetModel();
   }

   public function create()
   {
      $data = [
         'title' => 'Tambah Prasarana Baru',
         'prasarana' => $this->prasaranaModel->getPrasaranaForKatalog(),
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

      $fotoPrasarana = $this->fotoAsetModel->getByPrasarana($prasarana['id_prasarana']);

      $data = [
         'title' => 'Edit Data Prasarana',
         'prasarana' => $prasarana,
         'fotoPrasarana' => $fotoPrasarana,
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
         ],
         'foto_aset' => [
            'label' => 'Foto Aset',
            'rules' => 'permit_empty|uploaded[foto_aset]|max_size[foto_aset,2048]|is_image[foto_aset]|mime_in[foto_aset,image/jpg,image/jpeg,image/png]',
            'errors' => [
               'uploaded' => 'Pilih setidaknya satu foto.',
               'max_size' => 'Ukuran foto terlalu besar (maks 2MB).',
               'is_image' => 'File yang diupload bukan gambar.',
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
      $fasilitas = array_values(array_filter($fasilitas, fn($value) => trim($value) !== ''));

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 2. Simpan Data Induk (Prasarana)
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

         $this->prasaranaModel->insert($data);
         $id_prasarana_baru = $this->prasaranaModel->getInsertID(); // Ambil ID yang baru dibuat

         // 3. Proses Upload Foto
         $files = $this->request->getFileMultiple('foto_aset');
         $fotoModel = new FotoAsetModel();

         // dd($files);

         if ($files) {
            foreach ($files as $file) {
               if ($file->isValid() && !$file->hasMoved()) {
                  // Generate nama unik
                  $newName = $file->getRandomName();
                  // Simpan ke folder public/uploads/sarana
                  $file->move(FCPATH . 'uploads/prasarana', $newName);

                  // Simpan path ke database
                  if ($fotoModel->save([
                     'id_sarana' => null,
                     'id_prasarana'    => $id_prasarana_baru,
                     'url_foto'     => 'uploads/prasarana/' . $newName,
                     'deskripsi'    => $file->getClientName()
                  ]) === false) {
                     // Trigger rollback manual atau throw exception agar ditangkap catch
                     throw new \Exception('Gagal menyimpan foto: ' . implode(', ', $fotoModel->errors()));
                  }
               }
            }
         }

         // KOMIT TRANSAKSI (Simpan Permanen)
         $db->transComplete();

         if ($db->transStatus() === false) {
            // Jika ada error database, otomatis rollback
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data transaksi.');
         }

         return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data Sarana dan Foto berhasil disimpan.');
      } catch (\Exception $e) {
         // Tangkap error tak terduga
         return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
      }

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
         'foto_aset' => [
            'label' => 'Foto Aset',
            'rules' => 'permit_empty|uploaded[foto_aset]|max_size[foto_aset,2048]|is_image[foto_aset]|mime_in[foto_aset,image/jpg,image/jpeg,image/png]',
            'errors' => [
               'uploaded' => 'Pilih setidaknya satu foto.',
               'max_size' => 'Ukuran foto terlalu besar (maks 2MB).',
               'is_image' => 'File yang diupload bukan gambar.',
            ]
         ]
      ];

      if (!$this->validate($rules)) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Ambil data fasilitas sebagai array dari input dengan nama "fasilitas[]".
      $fasilitas = $this->request->getPost('fasilitas') ?? [];
      // Filter untuk menghapus nilai fasilitas yang kosong dan reset index array.
      $fasilitas = array_values(array_filter($fasilitas, fn($value) => trim($value) !== ''));

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 2. Simpan Data Induk (Sarana)
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

         // 3. Proses Upload Foto
         $files = $this->request->getFileMultiple('foto_aset');
         $fotoModel = new FotoAsetModel();

         // dd($files);

         if ($files) {
            foreach ($files as $file) {
               if ($file->isValid() && !$file->hasMoved()) {
                  // Generate nama unik
                  $newName = $file->getRandomName();
                  // Simpan ke folder public/uploads/sarana
                  $file->move(FCPATH . 'uploads/prasarana', $newName);

                  // Simpan path ke database
                  if ($fotoModel->save([
                     'id_sarana' => null,
                     'id_prasarana'    => $id,
                     'url_foto'     => 'uploads/prasarana/' . $newName,
                     'deskripsi'    => $file->getClientName()
                  ]) === false) {
                     // Trigger rollback manual atau throw exception agar ditangkap catch
                     throw new \Exception('Gagal menyimpan foto: ' . implode(', ', $fotoModel->errors()));
                  }
               }
            }
         }

         // KOMIT TRANSAKSI (Simpan Permanen)
         $db->transComplete();

         if ($db->transStatus() === false) {
            // Jika ada error database, otomatis rollback
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data transaksi.');
         }

         return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data Sarana dan Foto berhasil disimpan.');
      } catch (\Exception $e) {
         // Tangkap error tak terduga
         return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
      }

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data prasarana berhasil diperbarui.');
   }

   public function delete($id)
   {
      $fotoModel = $this->fotoAsetModel;

      // 1. Ambil daftar foto dari database SEBELUM menghapus data induk
      $fotos = $fotoModel->getByPrasarana($id);

      // 2. Hapus file fisik di server
      foreach ($fotos as $foto) {
         $path = FCPATH . $foto['url_foto'];
         if (is_file($path)) {
            unlink($path); // Hapus file gambar
         }
      }

      $this->prasaranaModel->delete($id);
      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data berhasil dihapus.');
   }
}
