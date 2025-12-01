<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

use App\Models\DataMaster\KategoriModel;
use App\Models\DataMaster\LokasiModel;
use App\Models\FotoAsetModel;

use App\Models\LaporanKerusakanModel;

class SaranaController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;
   protected $fotoAsetModel;

   public function __construct()
   {
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel = new LokasiModel();
      $this->fotoAsetModel = new FotoAsetModel();
   }

   public function create()
   {
      $sarana = $this->saranaModel->getSaranaForKatalog();
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog();

      $data = [
         'title' => 'Tambah Sarana Baru',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'kategori' => $this->kategoriModel->findAll(),
         'lokasi' => $this->lokasiModel->findAll(),
         'breadcrumbs' => [
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

      $fotoSarana = $this->fotoAsetModel->getBySarana($sarana['id_sarana']);

      $data = [
         'title' => 'Edit Data Sarana',
         'sarana' => $sarana,
         'prasarana' => $prasarana,
         'fotoSarana' => $fotoSarana,
         'kategori' => $this->kategoriModel->findAll(),
         'lokasi' => $this->lokasiModel->findAll(),
         'breadcrumbs' => [
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
            'rules' => 'required|is_unique[sarana.nama_sarana]',
            'errors' => [
               'required' => 'Nama item / sarana wajib diisi',
               'is_unique' => 'Sarana yang sama sudah terdaftar',
            ]
         ],
         'kode_sarana' => [
            'rules' => 'required|is_unique[sarana.kode_sarana]',
            'errors' => [
               'required' => 'Kode sarana harus diisi',
               'is_unique' => 'Kode sarana yang sama sudah terdaftar',
            ]
         ],
         // fk
         'id_kategori' => [
            'rules' => 'required',
            'errors' => ['required' => 'Kategori wajib dipilih.']
         ],
         'id_lokasi' => [
            'rules' => 'required',
            'errors' => ['required' => 'Lokasi wajib dipilih.']
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
         ],
         'foto_aset' => [
            'label' => 'Foto Aset',
            'rules' => 'uploaded[foto_aset]|max_size[foto_aset,2048]|is_image[foto_aset]|mime_in[foto_aset,image/jpg,image/jpeg,image/png]',
            'errors' => [
               'uploaded' => 'Pilih setidaknya satu foto.',
               'max_size' => 'Ukuran foto terlalu besar (maks 2MB).',
               'is_image' => 'File yang diupload bukan gambar.',
            ]
         ]
      ];

      // validasi input
      if (!$this->validate($rules)) {
         // input pengguna dan validasi yang didapat akan dikembalikan menjadi pesan
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 2. Simpan Data Induk (Sarana)
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

         $this->saranaModel->insert($data);
         $id_sarana_baru = $this->saranaModel->getInsertID(); // Ambil ID yang baru dibuat

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
                  $file->move(FCPATH . 'uploads/sarana', $newName);

                  // Simpan path ke database
                  if ($fotoModel->save([
                     'id_sarana'    => $id_sarana_baru,
                     'id_prasarana' => null,
                     'url_foto'     => 'uploads/sarana/' . $newName,
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
         return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' ya');
      }

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
         // FK
         'id_kategori' => [
            'rules' => 'required',
            'errors' => ['required' => 'Kategori wajib dipilih.']
         ],
         'id_lokasi' => [
            'rules' => 'required',
            'errors' => ['required' => 'Lokasi wajib dipilih.']
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

      if (!$this->validate($rules)) {
         // input pengguna dan validasi yang didapat akan dikembalikan menjadi pesan
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

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 2. Simpan Data Induk (Sarana)
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
                  $file->move(FCPATH . 'uploads/sarana', $newName);

                  // Simpan path ke database
                  if ($fotoModel->save([
                     'id_sarana'    => $id,
                     'id_prasarana' => null,
                     'url_foto'     => 'uploads/sarana/' . $newName,
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

      // $this->saranaModel->update($id, $data);

      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data sarana berhasil diperbarui.');
   }

   public function delete($id)
   {
      // Pengecekan Integritas laporan
      $activeReport = model(LaporanKerusakanModel::class)
         ->where('id_sarana', $id)
         ->whereIn('status_laporan', ['Diajukan', 'Diproses'])
         ->first();

      if ($activeReport) {
         return redirect()->back()->with('error', 'Gagal menghapus! Sarana ini memiliki laporan kerusakan yang sedang diproses. Harap selesaikan laporan (ID: ' . $activeReport['id_laporan'] . ') terlebih dahulu.');
      }

      $fotoModel = $this->fotoAsetModel;

      // 1. Ambil daftar foto dari database SEBELUM menghapus data induk
      $fotos = $fotoModel->getBySarana($id);

      // 2. Hapus file fisik di server
      foreach ($fotos as $foto) {
         $path = FCPATH . $foto['url_foto'];
         if (is_file($path)) {
            unlink($path); // Hapus file gambar
         }
      }

      $this->saranaModel->delete($id);
      return redirect()->to(site_url('admin/inventaris'))->with('message', 'Data ' . $this->saranaModel->getNamaSarana($id) . ' berhasil dihapus.');
   }

   public function deleteFoto($idFoto)
   {
      $foto = $this->fotoAsetModel->find($idFoto);
      if ($foto) {
         unlink(FCPATH . $foto['url_foto']); // Hapus file
         $this->fotoAsetModel->delete($idFoto);  // Hapus DB
      }
      return redirect()->back(); // Kembali ke halaman edit
   }
}
