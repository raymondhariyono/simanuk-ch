<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\ExtendedUserModel;

class DashboardController extends BaseController
{
   protected $peminjamanModel;
   protected $saranaModel;
   protected $prasaranaModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;
   protected $userModel;

   public function __construct()
   {
      $this->peminjamanModel      = new PeminjamanModel();
      $this->saranaModel          = new SaranaModel();
      $this->prasaranaModel       = new PrasaranaModel();
      $this->detailSaranaModel    = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->userModel            = model(ExtendedUserModel::class);
   }

   public function index()
   {
      $user = auth()->user();

      $countMenunggu = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->countAllResults();

      // Menghitung yang sedang dipinjam
      $countDipinjam = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
         ->countAllResults();

      // Menghitung Aset Rusak (Kondisi selain Baik)
      $countRusak = $this->saranaModel
         ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
         ->countAllResults();

      // Menghitung Total User (Khusus Admin)
      $countUser = $this->userModel->countAllResults();

      $stats = [
         'menunggu_verifikasi' => $countMenunggu,
         'sedang_dipinjam'     => $countDipinjam,
         'laporan_rusak'       => $countRusak,
         'total_user'          => $countUser
      ];

      // 2. DATA TABEL (Peminjaman Terbaru / Pending)
      $rawPending = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->orderBy('tgl_pengajuan', 'ASC')
         ->limit(5)
         ->findAll();

      $pendingApprovals = [];
      foreach ($rawPending as $row) {
         // Ambil nama item untuk ditampilkan ringkas
         $itemsSarana = $this->detailSaranaModel
            ->select('sarana.nama_sarana')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $row['id_peminjaman'])
            ->findAll();

         $itemsPrasarana = $this->detailPrasaranaModel
            ->select('prasarana.nama_prasarana')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $row['id_peminjaman'])
            ->findAll();

         $itemNames = array_map(fn($i) => $i['nama_sarana'], $itemsSarana);
         $roomNames = array_map(fn($i) => $i['nama_prasarana'], $itemsPrasarana);
         $allItemNames = array_merge($itemNames, $roomNames);

         $barangStr = empty($allItemNames) ? 'Tidak ada item' : implode(', ', array_slice($allItemNames, 0, 2));
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
         'title'            => 'Dashboard Admin',
         'user'             => $user,
         'stats'            => $stats,
         'pendingApprovals' => $pendingApprovals,
         'showSidebar'      => true,
      ];

      return view('admin/dashboard_view', $data);
   }
}