<?php

namespace App\Controllers\Pimpinan;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\LaporanKerusakanModel;

class LaporanController extends BaseController
{
    protected $peminjamanModel;
    protected $saranaModel;
    protected $laporanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->saranaModel = new SaranaModel();
        $this->laporanModel = new LaporanKerusakanModel();
    }

    /**
     * Halaman Utama Laporan (List Laporan)
     * Menampilkan daftar laporan yang tersedia (Virtual Reports)
     */
    public function index()
    {
        $filterJenis = $this->request->getGet('jenis');

        // Daftar Laporan "Virtual" 
        // (Dalam sistem nyata, ini bisa diambil dari tabel log/history laporan)
        // Kita buat dinamis berdasarkan bulan berjalan agar terlihat hidup.
        $bulanIni = date('F Y');
        $bulanLalu = date('F Y', strtotime('-1 month'));

        $daftarLaporan = [
            [
                'judul' => "Laporan Inventaris Aset - $bulanIni",
                'jenis' => 'Inventaris',
                'tanggal' => date('d F Y, 09:00'),
                'tipe_data' => 'sarana' // Parameter untuk detail
            ],
            [
                'judul' => "Laporan Peminjaman Sarpras - $bulanIni",
                'jenis' => 'Peminjaman',
                'tanggal' => date('d F Y, 15:30'),
                'tipe_data' => 'peminjaman'
            ],
            [
                'judul' => "Laporan Kerusakan Aset - $bulanIni",
                'jenis' => 'Kerusakan',
                'tanggal' => date('d F Y, 10:15'),
                'tipe_data' => 'kerusakan'
            ],
            [
                'judul' => "Laporan Peminjaman Sarpras - $bulanLalu",
                'jenis' => 'Peminjaman',
                'tanggal' => date('01 F Y, 08:00', strtotime('-1 month')),
                'tipe_data' => 'peminjaman'
            ],
        ];

        // Filter Logic
        if ($filterJenis && $filterJenis != 'Semua Jenis') {
            $daftarLaporan = array_filter($daftarLaporan, fn($row) => $row['jenis'] === $filterJenis);
        }

        $data = [
            'title' => 'Laporan Sistem',
            'showSidebar' => true,
            'laporan' => $daftarLaporan,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => site_url('pimpinan/dashboard')],
                ['name' => 'Laporan']
            ]
        ];

        return view('pimpinan/laporan/index', $data);
    }

    /**
     * Halaman Detail Laporan
     * Menampilkan data mentah (raw data) dari database sesuai tipe laporan
     */
    public function detail()
    {
        $tipe = $this->request->getGet('tipe');
        $judul = $this->request->getGet('judul');

        $dataRows = [];
        $columns = [];

        switch ($tipe) {
            case 'sarana': // Detail Inventaris
                $dataRows = $this->saranaModel
                    ->select('sarana.kode_sarana, sarana.nama_sarana, kategori.nama_kategori, lokasi.nama_lokasi, sarana.kondisi, sarana.jumlah, sarana.status_ketersediaan')
                    ->join('kategori', 'kategori.id_kategori = sarana.id_kategori')
                    ->join('lokasi', 'lokasi.id_lokasi = sarana.id_lokasi')
                    ->findAll();
                $columns = ['Kode', 'Nama Aset', 'Kategori', 'Lokasi', 'Kondisi', 'Jumlah', 'Status'];
                break;

            case 'peminjaman': // Detail Peminjaman
                $dataRows = $this->peminjamanModel
                    ->select('users.nama_lengkap, users.organisasi, peminjaman.kegiatan, peminjaman.tgl_pinjam_dimulai, peminjaman.tgl_pinjam_selesai, peminjaman.status_peminjaman_global')
                    ->join('users', 'users.id = peminjaman.id_peminjam')
                    ->orderBy('tgl_pengajuan', 'DESC')
                    ->findAll();
                $columns = ['Peminjam', 'Organisasi', 'Kegiatan', 'Mulai', 'Selesai', 'Status'];
                break;

            case 'kerusakan': // Detail Kerusakan
                $dataRows = $this->laporanModel
                    ->select('users.nama_lengkap, laporan_kerusakan.judul_laporan, laporan_kerusakan.deskripsi_kerusakan, laporan_kerusakan.status_laporan, laporan_kerusakan.created_at')
                    ->join('users', 'users.id = laporan_kerusakan.id_peminjam')
                    ->findAll();
                $columns = ['Pelapor', 'Judul', 'Deskripsi', 'Status', 'Tanggal Lapor'];
                break;
        }

        $data = [
            'title' => 'Detail Laporan',
            'showSidebar' => true,
            'judul_laporan' => $judul,
            'rows' => $dataRows,
            'columns' => $columns,
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => site_url('pimpinan/dashboard')],
                ['name' => 'Laporan', 'url' => site_url('pimpinan/lihat-laporan')],
                ['name' => 'Detail']
            ]
        ];

        return view('pimpinan/laporan/detail', $data);
    }
}