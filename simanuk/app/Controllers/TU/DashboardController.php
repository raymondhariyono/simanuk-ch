<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\LaporanKerusakanModel; // Pastikan ini ada
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DashboardController extends BaseController
{
    protected $userModel;
    protected $peminjamanModel;
    protected $saranaModel;
    protected $prasaranaModel;
    protected $detailSaranaModel;
    protected $detailPrasaranaModel;
    protected $laporanModel; // Property baru

    public function __construct()
    {
        $this->userModel            = auth()->getProvider();
        // Pastikan SEMUA model di-new kan di sini
        $this->peminjamanModel      = new PeminjamanModel();
        $this->saranaModel          = new SaranaModel();
        $this->prasaranaModel       = new PrasaranaModel();
        $this->detailSaranaModel    = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
        $this->laporanModel         = new LaporanKerusakanModel();
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

        // KPI Laporan Kerusakan
        // Menghitung Aset Rusak (Kondisi selain Baik)
        $countRusak = $this->laporanModel
            ->where('status_laporan', 'Diproses')
            ->countAllResults();

        // KPI Total Aset
        $totalAset = $this->saranaModel->countAll() + $this->prasaranaModel->countAll();

        $stats = [
            'menunggu_verifikasi' => $countMenunggu,
            'sedang_dipinjam'     => $countDipinjam,
            'laporan_rusak'       => $countRusak,
            'total_aset'          => $totalAset
        ];

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
            'title'            => 'Dashboard',
            'user'             => $user,
            'stats'            => $stats,
            'pendingApprovals' => $pendingApprovals,
            'showSidebar'      => true,
        ];

        return view('tu/dashboard_view', $data);
    }

    public function downloadLaporan()
    {
        $bulan = $this->request->getGet('bulan');
        if (!$bulan) $bulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($bulan));


        $dataPeminjaman = $this->getDataLaporan('peminjaman', $bulan);
        $dataKerusakan  = $this->getDataLaporan('kerusakan', $bulan);
        $dataInventaris = $this->getDataLaporan('inventaris', $bulan);

        $data = [
            'judul'      => "Laporan Bulanan Sarana Prasarana",
            'periode'    => $namaBulan,
            'peminjaman' => $dataPeminjaman,
            'kerusakan'  => $dataKerusakan,
            'inventaris' => $dataInventaris
        ];

        $html = view('tu/laporan_pdf_view', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times-Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = "Laporan_TU_" . date('F_Y', strtotime($bulan)) . ".pdf";
        $dompdf->stream($filename, ["Attachment" => true]);
        exit();
    }
    private function getDataLaporan($tipe, $bulan)
    {
        $rows = [];
        $columns = [];
        $db = \Config\Database::connect();

        switch ($tipe) {
            case 'inventaris':
                $subQueryRusak = "(SELECT COALESCE(SUM(lk.jumlah), 0) 
                                   FROM laporan_kerusakan lk 
                                   WHERE lk.id_sarana = sarana.id_sarana 
                                   AND lk.tipe_aset = 'Sarana' 
                                   AND lk.status_laporan IN ('Diajukan', 'Diproses'))";

                $dataSarana = $this->saranaModel
                    ->select('sarana.kode_sarana, sarana.nama_sarana, kategori.nama_kategori, lokasi.nama_lokasi')
                    ->select('sarana.jumlah as total_stok')
                    ->select("$subQueryRusak as stok_rusak")
                    ->join('kategori', 'kategori.id_kategori = sarana.id_kategori')
                    ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi')
                    ->where("DATE_FORMAT(sarana.created_at, '%Y-%m') <=", $bulan)
                    ->findAll();

                foreach ($dataSarana as $item) {
                    $total = $item['total_stok'];
                    $rusak = $item['stok_rusak'];
                    $baik  = $total - $rusak;
                    $statusStok = "{$total} Unit ({$baik} Baik / {$rusak} Rusak)";

                    $rows[] = [
                        'kode'      => $item['kode_sarana'],
                        'nama'      => $item['nama_sarana'],
                        'kategori'  => $item['nama_kategori'],
                        'lokasi'    => $item['nama_lokasi'],
                        'stok_info' => $statusStok
                    ];
                }
                $columns = ['Kode', 'Nama Aset', 'Kategori', 'Lokasi', 'Kondisi / Stok'];
                break;

            case 'peminjaman':
                $builderSarana = $db->table('detail_peminjaman_sarana')
                    ->select('users.nama_lengkap, sarana.nama_sarana as nama_item, detail_peminjaman_sarana.foto_sebelum, detail_peminjaman_sarana.foto_sesudah, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, detail_peminjaman_sarana.kondisi_akhir')
                    ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_sarana.id_peminjaman')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
                    ->like('peminjaman.tgl_pinjam_dimulai', $bulan);

                $builderPrasarana = $db->table('detail_peminjaman_prasarana')
                    ->select('users.nama_lengkap, prasarana.nama_prasarana as nama_item, detail_peminjaman_prasarana.foto_sebelum, detail_peminjaman_prasarana.foto_sesudah, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, detail_peminjaman_prasarana.kondisi_akhir')
                    ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_prasarana.id_peminjaman')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
                    ->like('peminjaman.tgl_pinjam_dimulai', $bulan);

                $rows = $builderSarana->union($builderPrasarana)->get()->getResultArray();
                $columns = ['Peminjam', 'Barang', 'Tgl Pinjam', 'Foto Awal', 'Foto Akhir', 'Kondisi'];
                break;

            case 'kerusakan':
                $rows = $this->laporanModel
                    ->select('users.nama_lengkap, laporan_kerusakan.judul_laporan, laporan_kerusakan.tipe_aset, laporan_kerusakan.status_laporan, laporan_kerusakan.created_at, laporan_kerusakan.tindak_lanjut')
                    ->join('users', 'users.id = laporan_kerusakan.id_pelapor')
                    ->like('laporan_kerusakan.created_at', $bulan)
                    ->orderBy('laporan_kerusakan.created_at', 'ASC')
                    ->findAll();
                $columns = ['Pelapor', 'Masalah', 'Tipe Aset', 'Status', 'Tanggal', 'Tindak Lanjut'];
                break;
        }

        return ['rows' => $rows, 'columns' => $columns];
    }

    public function downloadExcel()
    {
        // 1. Ambil Filter Bulan
        $bulan = $this->request->getGet('bulan');
        if (!$bulan) $bulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($bulan));

        // Data 1: Riwayat Peminjaman
        $dataPeminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->like('tgl_pinjam_dimulai', $bulan)
            ->orderBy('tgl_pinjam_dimulai', 'DESC')
            ->findAll();

        // Data 2 & 3: Total Aset & Aset Rusak
        $saranaRaw = $this->saranaModel
            ->select('sarana.*, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi', 'left')
            ->findAll();

        $prasaranaRaw = $this->prasaranaModel
            ->select('prasarana.*, lokasi.nama_lokasi')
            ->join('lokasi', 'lokasi.id_lokasi = prasarana.id_lokasi', 'left')
            ->findAll();

        $totalAset = [];
        $asetRusak = [];

        // Normalisasi Sarana
        foreach ($saranaRaw as $item) {
            $row = [
                'kode'    => $item['kode_sarana'],
                'nama'    => $item['nama_sarana'],
                'jenis'   => 'Sarana',
                'lokasi'  => $item['nama_lokasi'] ?? '-',
                'kondisi' => $item['kondisi']
            ];
            $totalAset[] = $row;
            if ($item['kondisi'] !== 'Baik') $asetRusak[] = $row;
        }

        // Normalisasi Prasarana
        foreach ($prasaranaRaw as $item) {
            $row = [
                'kode'    => $item['kode_prasarana'],
                'nama'    => $item['nama_prasarana'],
                'jenis'   => 'Prasarana',
                'lokasi'  => $item['nama_lokasi'] ?? '-',
                'kondisi' => $item['kondisi']
            ];
            $totalAset[] = $row;
            if ($item['kondisi'] !== 'Baik') $asetRusak[] = $row;
        }

        // PROSES PEMBUATAN EXCEL
        $spreadsheet = new Spreadsheet();

        // SHEET 1: PEMINJAMAN
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Riwayat Peminjaman');

        // Header Kolom Sheet 1
        $headers1 = ['No', 'Peminjam', 'Organisasi', 'Kegiatan', 'Tgl Mulai', 'Tgl Selesai', 'Status'];
        $rows1 = [];
        $no = 1;
        foreach ($dataPeminjaman as $d) {
            $rows1[] = [
                $no++,
                $d['nama_lengkap'],
                $d['organisasi'],
                $d['kegiatan'],
                date('d/m/Y', strtotime($d['tgl_pinjam_dimulai'])),
                date('d/m/Y', strtotime($d['tgl_pinjam_selesai'])),
                $d['status_peminjaman_global']
            ];
        }
        $this->writeSheet($sheet1, $headers1, $rows1, "DATA PEMINJAMAN - $namaBulan");

        // SHEET 2: ASET RUSAK
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Aset Rusak');
        $headers2 = ['No', 'Kode Aset', 'Nama Aset', 'Jenis', 'Lokasi', 'Kondisi'];
        $rows2 = [];
        $no = 1;
        foreach ($asetRusak as $d) {
            $rows2[] = [$no++, $d['kode'], $d['nama'], $d['jenis'], $d['lokasi'], $d['kondisi']];
        }
        $this->writeSheet($sheet2, $headers2, $rows2, "DAFTAR ASET RUSAK (PERLU PERBAIKAN)");

        // SHEET 3: TOTAL ASET
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Total Aset');
        $headers3 = ['No', 'Kode Aset', 'Nama Aset', 'Jenis', 'Lokasi', 'Kondisi'];
        $rows3 = [];
        $no = 1;
        foreach ($totalAset as $d) {
            $rows3[] = [$no++, $d['kode'], $d['nama'], $d['jenis'], $d['lokasi'], $d['kondisi']];
        }
        $this->writeSheet($sheet3, $headers3, $rows3, "REKAPITULASI SELURUH ASET");

        // OUTPUT FILE
        $filename = 'Laporan_TU_' . date('F_Y', strtotime($bulan)) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function writeSheet($sheet, $headers, $data, $title)
    {
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:' . chr(64 + count($headers)) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $col++;
        }
        $lastCol = chr(ord($col) - 1);

        // Style Header
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A90E2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray($styleHeader);

        $rowNum = 4;
        foreach ($data as $row) {
            $col = 'A';
            foreach ($row as $cell) {
                $sheet->setCellValue($col . $rowNum, $cell);
                $col++;
            }
            $rowNum++;
        }

        if ($rowNum > 4) {
            $lastRow = $rowNum - 1;
            $styleData = [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ];
            $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray($styleData);
        }
        foreach (range('A', $lastCol) as $colID) {
            $sheet->getColumnDimension($colID)->setAutoSize(true);
        }
    }
}
