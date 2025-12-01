<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\DataMaster\KategoriModel;
use App\Models\DataMaster\LokasiModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\FotoAsetModel;
use App\Models\Peminjaman\PeminjamanModel;
use App\Services\InventarisService;

class KatalogSarprasController extends BaseController
{
   protected $saranaModel;
   protected $prasaranaModel;

   protected $kategoriModel;
   protected $lokasiModel;
   protected $fotoAsetModel;

   protected $peminjamanModel;

   protected $inventarisService;

   public function __construct()
   {
      $this->saranaModel    = new SaranaModel();
      $this->prasaranaModel = new PrasaranaModel();

      $this->kategoriModel = new KategoriModel();
      $this->lokasiModel   = new LokasiModel();
      $this->fotoAsetModel = new FotoAsetModel();

      $this->peminjamanModel = new PeminjamanModel();

      $this->inventarisService = new InventarisService();
   }

   public function index()
   {
      // 1. Ambil Parameter GET (Query String)
      $filters = [
         'keyword'  => $this->request->getGet('keyword'),
         'kategori' => $this->request->getGet('kategori'),
         'lokasi'   => $this->request->getGet('lokasi'),
      ];

      $sarana = $this->inventarisService->getSaranaFiltered($filters, 8);
      $prasarana = $this->inventarisService->getPrasaranaFiltered($filters, 8);

      $data = [
         'title' => 'Katalog Sarpras',
         'actionUrl' => site_url('peminjam/sarpras/filter'),
         'sarana' => $sarana,
         'pager_sarana' => $this->inventarisService->getSaranaPager(),
         'prasarana' => $prasarana,
         'pager_prasarana' => $this->inventarisService->getPrasaranaPager(),

         // Data untuk Dropdown Filter
         'kategoriList' => $this->kategoriModel->findAll(),
         'lokasiList'   => $this->lokasiModel->findAll(),
         // Kirim balik filter agar input tidak reset
         'filters' => $filters,
         'showSidebar' => true, // flag untuk sidebar
      ];


      return view('peminjam/sarpras_view', $data);
   }

   public function detail($kode)
   {
      // 1. Siapkan Variabel Kalender (Ambil dari URL atau Default)
      $request = service('request');
      $bulan   = $request->getGet('bulan') ? (int)$request->getGet('bulan') : date('n');
      $tahun   = $request->getGet('tahun') ? (int)$request->getGet('tahun') : date('Y');

      // Validasi navigasi bulan
      if ($bulan < 1) {
         $bulan = 12;
         $tahun--;
      }
      if ($bulan > 12) {
         $bulan = 1;
         $tahun++;
      }

      // Logika Kalender Dasar
      $jumlahHari   = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
      $hariPertama  = date('N', strtotime("$tahun-$bulan-01")); // 1 (Senin) - 7 (Minggu)
      $paddingAwal  = $hariPertama - 1;
      $namaBulan    = date('F', mktime(0, 0, 0, $bulan, 10));

      // Siapkan data kalender untuk dikirim ke view
      $calendarData = [
         'bulan'       => $bulan,
         'tahun'       => $tahun,
         'namaBulan'   => $namaBulan,
         'jumlahHari'  => $jumlahHari,
         'paddingAwal' => $paddingAwal,
         'bookedDates' => [] // Nanti diisi setelah tahu jenis asetnya
      ];

      // Coba cari sebagai sarana terlebih dahulu
      $sarana = $this->saranaModel->getSaranaForKatalog($kode);

      // Jika sarana ditemukan
      if ($sarana) {
         // Proses field JSON 'spesifikasi'
         if (!empty($sarana['spesifikasi'])) {
            $decodedSpesifikasi = @json_decode($sarana['spesifikasi'], true);
            $sarana['spesifikasi'] = (json_last_error() === JSON_ERROR_NONE) ? $decodedSpesifikasi : [];
         } else {
            $sarana['spesifikasi'] = [];
         }

         $fotoSarana = $this->fotoAsetModel->getBySarana($sarana['id_sarana']);

         // AMBIL JADWAL BOOKING KHUSUS SARANA INI
         $calendarData['bookedDates'] = $this->peminjamanModel->getAssetSchedule(
            $sarana['id_sarana'],
            'sarana',
            $bulan,
            $tahun
         );

         $data = [
            'title' => 'Detail Sarana',
            'sarana' => $sarana,
            'fotoSarana' => $fotoSarana,
            'calendar' => $calendarData,
            'showSidebar' => false,
            'breadcrumbs' => [
               ['name' => 'Sarpras', 'url' => site_url('peminjam/sarpras')],
               ['name' => $sarana['nama_sarana']],
            ]
         ];

         return view('peminjam/detail_sarana_view', $data);
      }

      // Jika tidak ditemukan sebagai sarana, coba cari sebagai prasarana
      $prasarana = $this->prasaranaModel->getPrasaranaForKatalog($kode);

      $fotoPrasarana = $this->fotoAsetModel->getByPrasarana($prasarana['id_prasarana']);

      // Jika prasarana ditemukan
      if ($prasarana) {
         // Proses field JSON 'fasilitas'
         if (!empty($prasarana['fasilitas'])) {
            $decodedFasilitas = @json_decode($prasarana['fasilitas'], true);
            $prasarana['fasilitas'] = (json_last_error() === JSON_ERROR_NONE) ? $decodedFasilitas : [];
         } else {
            $prasarana['fasilitas'] = [];
         }

         // AMBIL JADWAL BOOKING KHUSUS PRASARANA INI
         $calendarData['bookedDates'] = $this->peminjamanModel->getAssetSchedule(
            $prasarana['id_prasarana'],
            'prasarana',
            $bulan,
            $tahun
         );

         $data = [
            'title' => 'Detail Prasarana',
            'prasarana' => $prasarana,
            'fotoPrasarana' => $fotoPrasarana,
            'calendar' => $calendarData,
            'showSidebar' => false,
            'breadcrumbs' => [
               ['name' => 'Sarpras', 'url' => site_url('peminjam/sarpras')],
               ['name' => $prasarana['nama_prasarana']],
            ]
         ];
         // TODO: Buat view 'peminjam/detail_prasarana_view' untuk menampilkan detail prasarana.
         return view('peminjam/detail_prasarana_view', $data);
      }

      // Jika sarana maupun prasarana tidak ditemukan
      throw new \CodeIgniter\Exceptions\PageNotFoundException('Sarpras dengan kode ' . $kode . ' tidak ditemukan');
   }
}
