<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class DashboardController extends BaseController
{
   protected $userModel;
   protected $peminjamanModel;
   protected $saranaModel;
   protected $prasaranaModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;

   public function __construct()
   {
      $this->userModel            = auth()->getProvider();
      $this->peminjamanModel      = new PeminjamanModel();
      $this->saranaModel          = new SaranaModel();
      $this->prasaranaModel       = new PrasaranaModel();
      $this->detailSaranaModel    = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
   }

   public function index()
   {
      $user = auth()->user();

      // 1. STATISTIK REAL
      $countMenunggu = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->countAllResults();

      $countDipinjam = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
         ->countAllResults();

      // Asumsi: Laporan kerusakan diambil dari Sarana yang kondisinya tidak 'Baik'
      // (Atau bisa disesuaikan jika nanti ada tabel khusus laporan_kerusakan)
      $countRusak = $this->saranaModel
         ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
         ->countAllResults();

      $totalAset = $this->saranaModel->countAll() + $this->prasaranaModel->countAll();

      $stats = [
         'menunggu_verifikasi' => $countMenunggu,
         'sedang_dipinjam'     => $countDipinjam,
         'laporan_rusak'       => $countRusak,
         'total_aset'          => $totalAset
      ];

      // 2. DATA PEMINJAMAN PENDING (REAL)
      // Ambil 5 pengajuan terbaru
      $rawPending = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->orderBy('tgl_pengajuan', 'ASC') // Yang paling lama menunggu di atas
         ->limit(5)
         ->findAll();

      $pendingApprovals = [];

      foreach ($rawPending as $row) {
         // Ambil nama barang (sarana)
         $itemsSarana = $this->detailSaranaModel
            ->select('sarana.nama_sarana')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $row['id_peminjaman'])
            ->findAll();

         // Ambil nama ruangan (prasarana)
         $itemsPrasarana = $this->detailPrasaranaModel
            ->select('prasarana.nama_prasarana')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $row['id_peminjaman'])
            ->findAll();

         // Gabungkan nama item menjadi string
         $itemNames = array_map(fn($i) => $i['nama_sarana'], $itemsSarana);
         $roomNames = array_map(fn($i) => $i['nama_prasarana'], $itemsPrasarana);
         $allItemNames = array_merge($itemNames, $roomNames);

         $barangStr = empty($allItemNames)
            ? 'Tidak ada item'
            : implode(', ', array_slice($allItemNames, 0, 2)); // Ambil 2 nama pertama

         if (count($allItemNames) > 2) {
            $barangStr .= '... (+' . (count($allItemNames) - 2) . ' lainnya)';
         }

         $pendingApprovals[] = [
            'id'         => $row['id_peminjaman'],
            'peminjam'   => $row['organisasi'] ? $row['organisasi'] . ' (' . $row['nama_lengkap'] . ')' : $row['nama_lengkap'],
            'barang'     => $barangStr,
            'tgl_ajukan' => date('d M Y', strtotime($row['tgl_pengajuan'])),
            'kegiatan'   => $row['kegiatan'],
            'status'     => 'Menunggu Verifikasi'
         ];
      }

      $data = [
         'title'            => 'Dashboard TU',
         'user'             => $user,
         'stats'            => $stats,
         'pendingApprovals' => $pendingApprovals,
         'showSidebar'      => true,
      ];

      return view('tu/dashboard_view', $data);
   }
}