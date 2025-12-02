<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LaporanKerusakanModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class LaporanKerusakanController extends BaseController
{
   protected $laporanModel;
   protected $saranaModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->laporanModel   = new LaporanKerusakanModel();
      $this->saranaModel    = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
   }

   /**
    * Menampilkan Daftar Laporan (Unified View dengan Tab)
    */
   public function index()
   {
      // Ambil semua laporan + Join ke User untuk info pelapor
      $laporan = $this->laporanModel
         ->select('laporan_kerusakan.*, users.nama_lengkap, users.organisasi, roles.nama_role') // <--- Tambah nama_role
         ->join('users', 'users.id = laporan_kerusakan.id_pelapor') // Join ke user
         ->join('roles', 'roles.id_role = users.id_role')             // Join ke role
         ->orderBy('created_at', 'DESC')
         ->findAll();
      
      // Pisahkan data untuk Tab Sarana & Prasarana
      $laporanSarana = [];
      $laporanPrasarana = [];

      foreach ($laporan as $row) {
         // Ambil detail nama aset
         if ($row['tipe_aset'] == 'Sarana') {
            $aset = $this->saranaModel->find($row['id_sarana']);
            $row['nama_aset'] = $aset['nama_sarana'] ?? 'Item / Sarana Terhapus';
            $row['kode_aset'] = $aset['kode_sarana'] ?? '-';
            $laporanSarana[] = $row;
         } else {
            $aset = $this->prasaranaModel->find($row['id_prasarana']);
            $row['nama_aset'] = $aset['nama_prasarana'] ?? 'Prasarana Terhapus';
            $row['kode_aset'] = $aset['kode_prasarana'] ?? '-';
            $laporanPrasarana[] = $row;
         }
      }

      $data = [
         'title' => 'Kelola Laporan Kerusakan',
         'laporanSarana' => $laporanSarana,
         'laporanPrasarana' => $laporanPrasarana,
         'showSidebar' => true,
      ];

      // dd($data['laporanSarana']);

      return view('admin/laporan/kelola_laporan_kerusakan_view', $data);
   }

   /**
    * Proses Update Status Laporan & Sinkronisasi Stok
    */
   public function updateStatus($idLaporan)
   {
      $laporan = $this->laporanModel->find($idLaporan);
      if (!$laporan) return redirect()->back()->with('error', 'Data tidak ditemukan.');

      $statusBaru    = $this->request->getPost('status_laporan');
      $tindakLanjut  = $this->request->getPost('tindak_lanjut');

      // Validasi Input
      if (!in_array($statusBaru, ['Diproses', 'Selesai', 'Ditolak'])) {
         return redirect()->back()->with('error', 'Status tidak valid.');
      }

      // 1. Update Laporan Kerusakan
      $this->laporanModel->update($idLaporan, [
         'status_laporan' => $statusBaru,
         'tindak_lanjut'  => $tindakLanjut,
         'updated_at'     => date('Y-m-d H:i:s')
      ]);

      // 2. SINKRONISASI STATUS MASTER DATA (Best Practice Integrity)
      // Jika 'Diproses' -> Set aset jadi 'Tidak Tersedia' (Maintenance)
      // Jika 'Selesai'  -> Set aset jadi 'Tersedia' (Fixed)

      $statusAset = null;
      if ($statusBaru == 'Diproses') {
         $statusAset = 'Tidak Tersedia'; // Atau 'Dalam Perbaikan' jika ada enum-nya
      } elseif ($statusBaru == 'Selesai') {
         $statusAset = 'Tersedia';
      }

      if ($statusAset) {
         if ($laporan['tipe_aset'] == 'Sarana') {
            $this->saranaModel->update($laporan['id_sarana'], ['status_ketersediaan' => $statusAset]);
         } else {
            $this->prasaranaModel->update($laporan['id_prasarana'], ['status_ketersediaan' => $statusAset]);
         }
      }

      return redirect()->back()->with('message', "Laporan diperbarui. Status aset disesuaikan menjadi '$statusAset'.");
   }

   /**
    * Form Laporan Internal (Admin melaporkan kerusakan tanpa peminjaman)
    */
   public function new()
   {
      $data = [
         'title' => 'Buat Laporan Internal',
         // Gunakan Service Inventaris atau Model langsung
         'saranaList' => $this->saranaModel->findAll(),
         'prasaranaList' => $this->prasaranaModel->findAll(),
         'showSidebar' => true,
         'breadcrumbs' => [
            ['name' => 'Beranda', 'url' => site_url('admin/dashboard')],
            ['name' => 'Laporan Kerusakan', 'url' => site_url('admin/laporan-kerusakan')],
            ['name' => 'Lapor Internal']
         ]
      ];
      return view('admin/laporan/create_internal_view', $data);
   }

   /**
    * Proses Simpan Laporan Internal
    */
   public function create()
   {
      if (!$this->validate([
         'tipe_aset' => 'required',
         'judul_laporan' => 'required|min_length[5]',
         'bukti_foto' => 'uploaded[bukti_foto]|is_image[bukti_foto]|max_size[bukti_foto,4096]', // Max 4MB
      ])) {
         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
      }

      // Upload Foto (Gunakan Helper yang sudah ada)
      $pathFoto = upload_file($this->request->getFile('bukti_foto'), 'uploads/laporan_kerusakan');

      $data = [
         'id_pelapor'          => auth()->user()->id, // ID Admin yang login
         'id_peminjaman'       => null,               // NULL karena laporan internal
         'tipe_aset'           => $this->request->getPost('tipe_aset'),
         'judul_laporan'       => $this->request->getPost('judul_laporan'),
         'deskripsi_kerusakan' => $this->request->getPost('deskripsi'),
         'bukti_foto'          => $pathFoto,
         'status_laporan'      => 'Diajukan', // Default Diajukan, nanti admin 'Proses' sendiri
         'jumlah'              => $this->request->getPost('jumlah') ?? 1,
      ];

      // Mapping ID Aset
      if ($data['tipe_aset'] == 'Sarana') {
         $data['id_sarana'] = $this->request->getPost('id_sarana');
         $data['id_prasarana'] = null;
      } else {
         $data['id_sarana'] = null;
         $data['id_prasarana'] = $this->request->getPost('id_prasarana');
      }

      // Validasi ID Aset Wajib Diisi
      if (empty($data['id_sarana']) && empty($data['id_prasarana'])) {
         return redirect()->back()->withInput()->with('error', 'Silakan pilih aset yang rusak.');
      }

      $this->laporanModel->save($data);

      return redirect()->to(site_url('admin/laporan-kerusakan'))->with('message', 'Laporan internal berhasil dibuat.');
   }
}
