<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use Dompdf\Dompdf;

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

      // 1. STATISTIK REAL (KPI)
      $countMenunggu = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->countAllResults();

      $countDipinjam = $this->peminjamanModel
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
         ->countAllResults();

      // KPI Laporan Kerusakan (Berdasarkan kondisi fisik Sarana)
      $countRusak = $this->saranaModel
         ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
         ->countAllResults();

      // KPI Total Aset
      $totalAset = $this->saranaModel->countAll() + $this->prasaranaModel->countAll();

      $stats = [
         'menunggu_verifikasi' => $countMenunggu,
         'sedang_dipinjam'     => $countDipinjam,
         'laporan_rusak'       => $countRusak,
         'total_aset'          => $totalAset
      ];

      // 2. DATA PEMINJAMAN PENDING (Untuk Tabel Dashboard)
      $rawPending = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->orderBy('tgl_pengajuan', 'ASC')
         ->limit(5)
         ->findAll();

      $pendingApprovals = [];
      foreach ($rawPending as $row) {
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
         'title'            => 'Dashboard TU',
         'user'             => $user,
         'stats'            => $stats,
         'pendingApprovals' => $pendingApprovals,
         'showSidebar'      => true,
      ];

      return view('tu/dashboard_view', $data);
   }

   /**
    * GENERATE LAPORAN PDF (Sesuai 4 KPI)
    */
   public function downloadLaporan()
   {
      $bulan = $this->request->getGet('bulan') ?? date('m');
      $tahun = $this->request->getGet('tahun') ?? date('Y');

      // 1. DATA PENGAJUAN (Filter Bulan Ini)
      $dataPengajuan = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
         ->where("MONTH(tgl_pengajuan)", $bulan)
         ->where("YEAR(tgl_pengajuan)", $tahun)
         ->findAll();

      // 2. DATA SEDANG DIPINJAM (Snapshot Saat Ini)
      $dataDipinjam = $this->peminjamanModel
         ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
         ->join('users', 'users.id = peminjaman.id_peminjam')
         ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
         ->findAll();

      // 3. DATA LAPORAN KERUSAKAN (Snapshot Aset Rusak)
      $dataRusak = $this->saranaModel
         ->select('sarana.*, lokasi.nama_lokasi')
         ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi', 'left')
         ->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])
         ->findAll();

      // 4. TOTAL ASET (Snapshot Semua Aset)
      // Gabungan Sarana & Prasarana
      $asetSarana = $this->saranaModel
         ->select('sarana.nama_sarana as nama, sarana.kode_sarana as kode, sarana.kondisi, "Sarana" as jenis')
         ->findAll();
      $asetPrasarana = $this->prasaranaModel
         ->select('prasarana.nama_prasarana as nama, prasarana.kode_prasarana as kode, "Baik" as kondisi, "Prasarana" as jenis')
         ->findAll();
      
      $totalAset = array_merge($asetSarana, $asetPrasarana);

      $data = [
         'title'         => 'Laporan Rekapitulasi Dashboard TU',
         'bulan'         => date('F', mktime(0, 0, 0, $bulan, 10)),
         'tahun'         => $tahun,
         'dataPengajuan' => $dataPengajuan,
         'dataDipinjam'  => $dataDipinjam,
         'dataRusak'     => $dataRusak,
         'totalAset'     => $totalAset,
         'kpi' => [
            'pengajuan' => count($dataPengajuan),
            'dipinjam'  => count($dataDipinjam),
            'rusak'     => count($dataRusak),
            'aset'      => count($totalAset)
         ]
      ];

      $html = view('tu/laporan_pdf_view', $data);

      $dompdf = new Dompdf();
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();

      $dompdf->stream('Laporan_Dashboard_TU_' . date('Ymd') . '.pdf', ["Attachment" => true]);
   }
}