<?php

namespace App\Controllers\Pimpinan;

use App\Controllers\BaseController;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\LaporanKerusakanModel;

class DashboardController extends BaseController
{
    protected $saranaModel;
    protected $prasaranaModel;
    protected $peminjamanModel;
    protected $laporanModel;

    public function __construct()
    {
        $this->saranaModel = new SaranaModel();
        $this->prasaranaModel = new PrasaranaModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->laporanModel = new LaporanKerusakanModel();
    }

    public function index()
    {
        // 1. Hitung Statistik Utama
        $totalSarana = $this->saranaModel->countAllResults();
        $totalPrasarana = $this->prasaranaModel->countAllResults();
        
        // Peminjaman Aktif (Status 'Dipinjam' atau 'Disetujui')
        $peminjamanAktif = $this->peminjamanModel
            ->whereIn('status_peminjaman_global', ['Dipinjam', 'Disetujui'])
            ->countAllResults();

        // Laporan Kerusakan 'Pending' (Belum Selesai)
        $laporanRusak = $this->laporanModel
            ->where('status_laporan !=', 'Selesai')
            ->where('status_laporan !=', 'Ditolak')
            ->countAllResults();

        // 2. Ambil 5 Peminjaman Terakhir (Untuk Tabel Ringkas di Dashboard)
        $recentActivity = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title' => 'Dashboard Pimpinan',
            'showSidebar' => true,
            'stats' => [
                'total_aset' => $totalSarana + $totalPrasarana,
                'peminjaman_aktif' => $peminjamanAktif,
                'laporan_rusak' => $laporanRusak,
            ],
            'recentActivity' => $recentActivity
        ];

        return view('pimpinan/dashboard_view', $data);
    }
}