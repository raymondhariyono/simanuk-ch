<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class PengembalianController extends BaseController
{
   protected $peminjamanModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;
   protected $saranaModel;
   protected $prasaranaModel;

   public function __construct()
   {
      $this->peminjamanModel = new PeminjamanModel();
      $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->saranaModel = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();
   }

   /**
    * Menampilkan Daftar Barang yang SEDANG DIPINJAM
    */
   public function index()
   {
      // Ambil data yang statusnya 'Dipinjam'
      // Kita perlu tahu mana yang "Urgent" (User sudah upload bukti kembali)
      // Logika sorting: Prioritaskan yang sudah ada foto pengembalian di detailnya (Logic agak kompleks, kita simple-kan dulu by Date)

      $peminjaman = $this->peminjamanModel
         ->select('peminjaman.*, users.username, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
         ->orderBy('tgl_pinjam_selesai', 'ASC') // deadline yang paling dekat berada di atas
         ->findAll();

      $data = [
         'title' => 'Verifikasi Pengembalian',
         'peminjaman' => $peminjaman,
         'showSidebar' => true,
      ];

      return view('admin/pengembalian/index', $data);
   }

   /**
    * Halaman Detail Verifikasi Fisik
    */
   public function detail($id)
   {
      $peminjaman = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.kontak')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('id_peminjaman', $id)
         ->first();

      if (!$peminjaman) return redirect()->back();

      // Ambil Detail Item
      $itemsSarana = $this->detailSaranaModel
         ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
         ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
         ->where('id_peminjaman', $id)
         ->findAll();

      $itemsPrasarana = $this->detailPrasaranaModel
         ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
         ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
         ->where('id_peminjaman', $id)
         ->findAll();

      $data = [
         'title' => 'Verifikasi Fisik Barang',
         'peminjaman' => $peminjaman,
         'itemsSarana' => $itemsSarana,
         'itemsPrasarana' => $itemsPrasarana,
         'breadcrumbs' => [
            ['name' => 'Transaksi Pengembalian', 'url' => site_url('admin/pengembalian')],
            ['name' => 'Verifikasi Pengembalian'],
         ]
      ];

      return view('admin/pengembalian/detail', $data);
   }

   /**
    * PROSES UTAMA: Selesai & Restock
    */
   public function prosesSelesai($id)
   {
      $peminjaman = $this->peminjamanModel->find($id);

      if (!$peminjaman || $peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIPINJAM) {
         return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
      }

      $db = \Config\Database::connect();
      $db->transStart();

      try {
         // 1. RESTOCK SARANA (Barang)
         $itemsSarana = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
         // $itemsPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();

         foreach ($itemsSarana as $item) {
            $sarana = $this->saranaModel->find($item['id_sarana']);

            // Rumus: Stok Lama + Jumlah Dikembalikan
            $stokBaru = $sarana['jumlah'] + $item['jumlah'];

            // Update Stok & Status Availability
            $updateData = ['jumlah' => $stokBaru];
            // jika stok > 0, maka tersedia
            if ($stokBaru > 0) {
               $updateData['status_ketersediaan'] = 'Tersedia';
            }

            $this->saranaModel->update($item['id_sarana'], $updateData);
         }

         // foreach ($itemsPrasarana as $item) {
         //    // Untuk ruangan, cukup set status jadi Tersedia kembali
         //    $this->prasaranaModel->update($item['id_prasarana'], [
         //       'status_ketersediaan' => 'Tersedia'
         //    ]);
         // }

         // 3. UPDATE STATUS GLOBAL -> SELESAI
         $this->peminjamanModel->update($id, [
            'status_peminjaman_global' => PeminjamanModel::STATUS_SELESAI,
            // Opsional: Catat tanggal pengembalian aktual admin
            'updated_at' => date('Y-m-d H:i:s') 
         ]);

         $db->transComplete();

         if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian.');
         }

         return redirect()->to(site_url('admin/pengembalian'))->with('message', 'Pengembalian berhasil diverifikasi. Stok telah dikembalikan.');
      } catch (\Exception $e) {
         $db->transRollback();
         return redirect()->back()->with('error', $e->getMessage());
      }
   }
}
