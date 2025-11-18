<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;

class HistoriPeminjamanController extends BaseController
{
    public function index()
    {
        // --- Data Dummy (Contoh) ---
        // Ini adalah data pura-pura agar tabel Anda terisi
        $dummyLoans = [
            [
                'nama_item' => 'Proyektor Epson EB-X450',
                'kode' => 'PRJ-001',
                'kegiatan' => 'Seminar',
                'status' => 'Menunggu Verifikasi',
                'aksi' => 'Batal',
            ],
            [
                'nama_item' => 'Meja Rapat Kayu',
                'kode' => 'MJA-012',
                'kegiatan' => 'Rapat',
                'status' => 'Menunggu Persetujuan',
                'aksi' => 'Batal',
            ],
            [
                'nama_item' => 'Kamera DSLR Canon 80D',
                'kode' => 'CAM-003',
                'kegiatan' => 'Makrab',
                'status' => 'Disetujui',
                'aksi' => 'Upload Foto SEBELUM *',
            ],
            [
                'nama_item' => 'Laptop Dell XPS 15',
                'kode' => 'LTP-005',
                'kegiatan' => 'Lomba',
                'status' => 'Berlangsung',
                'aksi' => 'Kembalikan',
            ],
        ];
        // --- Akhir Data Dummy ---

        $data = [
            'title'       => 'Histori Peminjaman',
            'loans'       => $dummyLoans, // Menggunakan data dummy
            'showSidebar' => true,
            'breadcrumbs' => [
                [
                    'name' => 'Beranda',
                    'url'  => site_url('peminjam/dashboard')
                ],
                [
                    'name' => 'Histori Peminjaman',
                ]
            ]
        ];

        // Memuat file view yang akan kita buat selanjutnya
        return view('peminjam/histori_peminjaman_view', $data);
    }
}