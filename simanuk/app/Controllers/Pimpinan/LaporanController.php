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

    public function cetak()
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

        $html = view('pimpinan/laporan/cetak_pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times-Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = "Laporan_Sarpras_" . date('F_Y', strtotime($bulan)) . ".pdf";
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
                // 1. Ambil Sarana + Foto
                $subQueryRusakSarana = "(SELECT COALESCE(SUM(lk.jumlah), 0) FROM laporan_kerusakan lk WHERE lk.id_sarana = sarana.id_sarana AND lk.tipe_aset = 'Sarana' AND lk.status_laporan IN ('Diajukan', 'Diproses'))";
                $subQueryFotoSarana = "(SELECT url_foto FROM foto_aset WHERE foto_aset.id_sarana = sarana.id_sarana ORDER BY id_foto DESC LIMIT 1)";

                $dataSarana = $this->saranaModel
                    ->select('sarana.kode_sarana, sarana.nama_sarana, kategori.nama_kategori, lokasi.nama_lokasi')
                    ->select('sarana.jumlah as total_stok')
                    ->select("$subQueryRusakSarana as stok_rusak")
                    ->select("$subQueryFotoSarana as foto_aset")
                    ->join('kategori', 'kategori.id_kategori = sarana.id_kategori', 'left')
                    ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi', 'left')
                    ->where("DATE_FORMAT(sarana.created_at, '%Y-%m') <=", $bulan)
                    ->findAll();

                // 2. Ambil Prasarana + Foto
                $subQueryRusakPrasarana = "(SELECT COUNT(*) FROM laporan_kerusakan lk WHERE lk.id_prasarana = prasarana.id_prasarana AND lk.tipe_aset = 'Prasarana' AND lk.status_laporan IN ('Diajukan', 'Diproses'))";
                $subQueryFotoPrasarana = "(SELECT url_foto FROM foto_aset WHERE foto_aset.id_prasarana = prasarana.id_prasarana ORDER BY id_foto DESC LIMIT 1)";

                $dataPrasarana = $this->prasaranaModel
                    ->select('prasarana.kode_prasarana, prasarana.nama_prasarana, kategori.nama_kategori, lokasi.nama_lokasi')
                    ->select('1 as total_stok')
                    ->select("$subQueryRusakPrasarana as stok_rusak")
                    ->select("$subQueryFotoPrasarana as foto_aset")
                    ->join('kategori', 'kategori.id_kategori = prasarana.id_kategori', 'left')
                    ->join('lokasi', 'lokasi.id_lokasi = prasarana.id_lokasi', 'left')
                    ->where("DATE_FORMAT(prasarana.created_at, '%Y-%m') <=", $bulan)
                    ->findAll();

                // Gabungkan Data dan Mapping Manual
                $allData = array_merge($dataSarana, $dataPrasarana);

                foreach ($allData as $item) {
                    $total = $item['total_stok'];
                    $rusak = $item['stok_rusak'];
                    $baik  = $total - $rusak;
                    $statusStok = "{$total} Unit ({$baik} Baik / {$rusak} Rusak)";

                    $rows[] = [
                        'foto_aset' => $item['foto_aset'], // Tampilkan foto di awal
                        'kode'      => isset($item['kode_sarana']) ? $item['kode_sarana'] : $item['kode_prasarana'],
                        'nama'      => isset($item['nama_sarana']) ? $item['nama_sarana'] : $item['nama_prasarana'],
                        'kategori'  => $item['nama_kategori'],
                        'lokasi'    => $item['nama_lokasi'],
                        'stok_info' => $statusStok
                    ];
                }

                $columns = ['Foto', 'Kode', 'Nama Aset', 'Kategori', 'Lokasi', 'Kondisi / Stok'];
                break;

            case 'peminjaman':
                // A. Query Sarana
                $builderSarana = $db->table('detail_peminjaman_sarana')
                    ->select('users.nama_lengkap, sarana.nama_sarana as nama_item, detail_peminjaman_sarana.foto_sebelum, detail_peminjaman_sarana.foto_sesudah, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, detail_peminjaman_sarana.kondisi_akhir')
                    ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_sarana.id_peminjaman')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
                    ->like('peminjaman.tgl_pinjam_dimulai', $bulan);

                // B. Query Prasarana
                $builderPrasarana = $db->table('detail_peminjaman_prasarana')
                    ->select('users.nama_lengkap, prasarana.nama_prasarana as nama_item, detail_peminjaman_prasarana.foto_sebelum, detail_peminjaman_prasarana.foto_sesudah, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, detail_peminjaman_prasarana.kondisi_akhir')
                    ->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_prasarana.id_peminjaman')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    // PERBAIKAN DI SINI: Join ke prasarana menggunakan id_prasarana, bukan id_sarana
                    ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
                    ->like('peminjaman.tgl_pinjam_dimulai', $bulan);
                
                $rawRows = $builderSarana->union($builderPrasarana)->get()->getResultArray();
                
                // MAPPING MANUAL agar urutan kolom PASTI BENAR sesuai Header
                foreach ($rawRows as $r) {
                    $rows[] = [
                        'peminjam'    => $r['nama_lengkap'],
                        'barang'      => $r['nama_item'],
                        'foto_awal'   => $r['foto_sebelum'], // Mengandung kata 'foto' agar dideteksi View
                        'foto_akhir'  => $r['foto_sesudah'], // Mengandung kata 'foto' agar dideteksi View
                        'tgl_pinjam'  => $r['tgl_pinjam_dimulai'],
                        'tgl_selesai' => $r['tgl_pinjam_selesai'],
                        'kondisi'     => $r['kondisi_akhir']
                    ];
                }

                $columns = ['Peminjam', 'Barang', 'Foto Awal', 'Foto Akhir', 'Tgl Pinjam', 'Tgl Selesai', 'Kondisi'];
                break;

            case 'kerusakan':
                $rawRows = $this->laporanModel
                    ->select('laporan_kerusakan.bukti_foto, users.nama_lengkap, laporan_kerusakan.judul_laporan, laporan_kerusakan.tipe_aset, laporan_kerusakan.status_laporan, laporan_kerusakan.created_at, laporan_kerusakan.tindak_lanjut')
                    ->join('users', 'users.id = laporan_kerusakan.id_pelapor')
                    ->like('laporan_kerusakan.created_at', $bulan)
                    ->orderBy('laporan_kerusakan.created_at', 'ASC')
                    ->findAll();
                
                // MAPPING MANUAL
                foreach ($rawRows as $r) {
                    $rows[] = [
                        'bukti_foto' => $r['bukti_foto'], // Foto pertama
                        'pelapor'    => $r['nama_lengkap'],
                        'masalah'    => $r['judul_laporan'],
                        'tipe'       => $r['tipe_aset'],
                        'status'     => $r['status_laporan'],
                        'tanggal'    => $r['created_at'],
                        'tindak'     => $r['tindak_lanjut']
                    ];
                }

                $columns = ['Bukti Foto', 'Pelapor', 'Masalah', 'Tipe Aset', 'Status', 'Tanggal', 'Tindak Lanjut'];
                break;
        }

        return ['rows' => $rows, 'columns' => $columns];
    }

    public function excel()
    {
        $bulan = $this->request->getGet('bulan');
        if (!$bulan) $bulan = date('Y-m');

        $namaBulan = date('F Y', strtotime($bulan));

        $dataPeminjaman = $this->getDataLaporan('peminjaman', $bulan);
        $dataKerusakan  = $this->getDataLaporan('kerusakan', $bulan);
        $dataInventaris = $this->getDataLaporan('inventaris', $bulan);

        $spreadsheet = new Spreadsheet();

        // SHEET 1: PEMINJAMAN
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Peminjaman');
        $this->writeSheet($sheet, $dataPeminjaman, 'DATA PEMINJAMAN - ' . $namaBulan);

        // SHEET 2: KERUSAKAN
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Kerusakan');
        $this->writeSheet($sheet2, $dataKerusakan, 'DATA KERUSAKAN - ' . $namaBulan);

        // SHEET 3: INVENTARIS
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Inventaris');
        $this->writeSheet($sheet3, $dataInventaris, 'REKAPITULASI INVENTARIS - ' . $namaBulan);

        $filename = 'Laporan_Sarpras_' . date('F_Y', strtotime($bulan)) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function writeSheet($sheet, $data, $title)
    {
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $columns = $data['columns'];
        $sheet->setCellValue('A3', 'No');

        $colChar = 'B';
        foreach ($columns as $col) {
            $sheet->setCellValue($colChar . '3', $col);
            $colChar++;
        }

        $lastCol = chr(ord($colChar) - 1);
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

        $rows = $data['rows'];
        $rowNum = 4;
        $no = 1;

        foreach ($rows as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $c = 'B';
            foreach ($row as $key => $val) {
                if (strpos($key, 'id_') !== false) continue;
                $sheet->setCellValue($c . $rowNum, $val);
                $c++;
            }
            $rowNum++;
        }

        if ($rowNum > 4) {
            $lastRow = $rowNum - 1;
            $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
        }

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}