<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;

class LaporanKerusakanController extends BaseController
{
    public function index()
    {
        $peminjamanAktif = [
            [
                'id' => 1,
                'nama_item' => 'Proyektor Epson EB-X450',
                'kode' => 'PRJ-001'
            ],
            [
                'id' => 2,
                'nama_item' => 'Kamera DSLR Canon 80D',
                'kode' => 'CAM-003'
            ]
        ];

        $data = [
            'title'       => 'Lapor Kerusakan',
            'items'       => $peminjamanAktif, // Data untuk dropdown
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Laporan Kerusakan'],
            ]
        ];

        return view('peminjam/laporan_kerusakan_view', $data);
    }

    public function store()
    {
        // Logika penyimpanan laporan ke database (insert ke tabel laporan)
        return redirect()->to('peminjam/laporan-kerusakan')->with('msg', 'Laporan berhasil dikirim.');
    }
}