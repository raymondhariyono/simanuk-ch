<?php

namespace App\Controllers\Pimpinan;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\LaporanKerusakanModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanController extends BaseController
{
    protected $peminjamanModel;
    protected $saranaModel;
    protected $prasaranaModel;
    protected $laporanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->saranaModel = new SaranaModel();
        $this->prasaranaModel = new PrasaranaModel();
        $this->laporanModel = new LaporanKerusakanModel();
    }

    /**
     * Halaman Utama (Web View): List Jenis Laporan per Bulan
     */
    public function index()
    {
        $filterBulan = $this->request->getGet('bulan');
        if (!$filterBulan) $filterBulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($filterBulan));

        // Hitung Ringkasan Data
        $jmlPeminjaman = $this->peminjamanModel->like('tgl_pinjam_dimulai', $filterBulan)->countAllResults();
        $jmlKerusakan = $this->laporanModel->like('created_at', $filterBulan)->countAllResults();
        $jmlSarana = $this->saranaModel->where("DATE_FORMAT(created_at, '%Y-%m') <=", $filterBulan)->countAllResults();
        $jmlPrasarana = $this->prasaranaModel->where("DATE_FORMAT(created_at, '%Y-%m') <=", $filterBulan)->countAllResults();

        // Daftar Laporan untuk Web View
        $daftarLaporan = [
            [
                'judul'      => "Laporan Peminjaman ($namaBulan)",
                'jenis'      => 'Peminjaman',
                'ringkasan'  => "$jmlPeminjaman Transaksi",
                'tipe_data'  => 'peminjaman',
            ],
            [
                'judul'      => "Laporan Kerusakan ($namaBulan)",
                'jenis'      => 'Kerusakan',
                'ringkasan'  => "$jmlKerusakan Laporan Masuk",
                'tipe_data'  => 'kerusakan',
            ],
            [
                'judul'      => "Laporan Inventaris Aset (Posisi $namaBulan)",
                'jenis'      => 'Inventaris',
                'ringkasan'  => ($jmlSarana + $jmlPrasarana) . " Item Terdata",
                'tipe_data'  => 'inventaris',
            ]
        ];

        $data = [
            'title'       => 'Laporan Sistem',
            'showSidebar' => true,
            'laporan'     => $daftarLaporan,
            'filterBulan' => $filterBulan,
        ];

        return view('pimpinan/laporan/index', $data);
    }

    /**
     * Halaman Detail (Web View): Untuk melihat detail di browser tanpa download
     */
    public function detail()
    {
        $tipe  = $this->request->getGet('tipe');
        $bulan = $this->request->getGet('bulan');
        $judul = $this->request->getGet('judul');

        if (!$bulan) $bulan = date('Y-m');

        $result = $this->getDataLaporan($tipe, $bulan);

        $data = [
            'title'         => 'Detail Laporan',
            'showSidebar'   => true,
            'judul_laporan' => $judul ?? 'Detail Laporan',
            'periode'       => date('F Y', strtotime($bulan)),
            'rows'          => $result['rows'],
            'columns'       => $result['columns'],
            'filterBulan'   => $bulan,
            'breadcrumbs'   => [
                ['name' => 'Laporan', 'url' => site_url('pimpinan/lihat-laporan') . "?bulan=$bulan"],
                ['name' => 'Detail']
            ]
        ];

        return view('pimpinan/laporan/detail', $data);
    }

    /**
     * LOGIKA DOWNLOAD PDF (REVISI)
     * Menggabungkan semua laporan menjadi satu file PDF yang langsung terdownload
     */
    public function cetak()
    {
        $bulan = $this->request->getGet('bulan');
        if (!$bulan) $bulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($bulan));

        // 1. Ambil Semua Data
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

        // 2. Render View ke HTML String
        $html = view('pimpinan/laporan/cetak_pdf', $data);

        // 3. Konfigurasi Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Agar bisa load gambar/css external jika ada
        $options->set('defaultFont', 'Times-Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Setup ukuran kertas (A4, Landscape agar tabel muat)
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // 4. Output Langsung Download (Attachment => true)
        // Nama file: Laporan_Sarpras_Bulan_Tahun.pdf
        $filename = "Laporan_Sarpras_" . date('F_Y', strtotime($bulan)) . ".pdf";

        $dompdf->stream($filename, ["Attachment" => true]);
        exit();
    }

    /**
     * Helper Private Query Data (Tidak Berubah)
     */
    private function getDataLaporan($tipe, $bulan)
    {
        $rows = [];
        $columns = [];

        switch ($tipe) {
            case 'inventaris':
                $rows = $this->saranaModel
                    ->select('sarana.kode_sarana, sarana.nama_sarana, kategori.nama_kategori, lokasi.nama_lokasi, sarana.kondisi, sarana.jumlah, sarana.status_ketersediaan')
                    ->join('kategori', 'kategori.id_kategori = sarana.id_kategori')
                    ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi')
                    ->where("DATE_FORMAT(sarana.created_at, '%Y-%m') <=", $bulan)
                    ->findAll();
                $columns = ['Kode', 'Nama Aset', 'Kategori', 'Lokasi', 'Kondisi', 'Jumlah', 'Status'];
                break;

            case 'peminjaman':
                $rows = $this->peminjamanModel
                    ->select('users.nama_lengkap, users.organisasi, peminjaman.kegiatan, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, peminjaman.status_peminjaman_global')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    ->like('peminjaman.tgl_pinjam_dimulai', $bulan)
                    ->orderBy('peminjaman.tgl_pinjam_dimulai', 'ASC')
                    ->findAll();
                $columns = ['Peminjam', 'Organisasi', 'Kegiatan', 'Mulai', 'Selesai', 'Status'];
                break;

            case 'kerusakan':
                $rows = $this->laporanModel
                    ->select('users.nama_lengkap, laporan_kerusakan.judul_laporan, laporan_kerusakan.tipe_aset, laporan_kerusakan.status_laporan, laporan_kerusakan.created_at, laporan_kerusakan.tindak_lanjut')
                    ->join('users', 'users.id = laporan_kerusakan.id_peminjam')
                    ->like('laporan_kerusakan.created_at', $bulan)
                    ->orderBy('laporan_kerusakan.created_at', 'ASC')
                    ->findAll();
                $columns = ['Pelapor', 'Masalah', 'Tipe Aset', 'Status', 'Tanggal', 'Tindak Lanjut'];
                break;
        }

        return ['rows' => $rows, 'columns' => $columns];
    }

    public function excel()
    {
        $bulan = $this->request->getGet('bulan');
        if (!$bulan) $bulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($bulan));

        // 1. Ambil Semua Data (Menggunakan fungsi yang sudah ada)
        $dataPeminjaman = $this->getDataLaporan('peminjaman', $bulan);
        $dataKerusakan  = $this->getDataLaporan('kerusakan', $bulan);
        $dataInventaris = $this->getDataLaporan('inventaris', $bulan);

        // 2. Buat Spreadsheet Baru
        $spreadsheet = new Spreadsheet();

        // --- SHEET 1: PEMINJAMAN ---
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Peminjaman');
        $this->writeSheet($sheet, $dataPeminjaman, 'DATA PEMINJAMAN - ' . $namaBulan);

        // --- SHEET 2: KERUSAKAN ---
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Kerusakan');
        $this->writeSheet($sheet2, $dataKerusakan, 'DATA KERUSAKAN - ' . $namaBulan);

        // --- SHEET 3: INVENTARIS ---
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Inventaris');
        $this->writeSheet($sheet3, $dataInventaris, 'REKAPITULASI INVENTARIS - ' . $namaBulan);

        // 3. Set Header untuk Download
        $filename = 'Laporan_Sarpras_' . date('F_Y', strtotime($bulan)) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // 4. Tulis ke Output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function writeSheet($sheet, $data, $title)
    {
        // Header Judul
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:G1'); // Sesuaikan merge dengan perkiraan jumlah kolom
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Tabel (Baris ke-3)
        $columns = $data['columns'];
        $sheet->setCellValue('A3', 'No');

        $colChar = 'B';
        foreach ($columns as $col) {
            $sheet->setCellValue($colChar . '3', $col);
            $colChar++;
        }

        // Styling Header Tabel
        $lastCol = chr(ord($colChar) - 1); // Huruf kolom terakhir
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFEFEFEF']
            ]
        ];
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray($headerStyle);

        // Isi Data (Mulai Baris ke-4)
        $rows = $data['rows'];
        $rowNum = 4;
        $no = 1;

        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $c = 'B';
            foreach ($row as $key => $val) {
                // Skip kolom ID jika ada
                if (strpos($key, 'id_') !== false) continue;

                $sheet->setCellValue($c . $rowNum, $val);
                $c++;
            }
            $rowNum++;
        }

        // Styling Border Data
        if ($rowNum > 4) {
            $lastRow = $rowNum - 1;
            $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
        }

        // Auto Size Kolom
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
