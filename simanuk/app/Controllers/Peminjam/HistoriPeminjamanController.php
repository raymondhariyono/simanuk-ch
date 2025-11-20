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

    // File: app/Controllers/Peminjam/HistoriPeminjamanController.php

    public function detail($kode)
    {
        // Data Dummy: Status masih "Digunakan"
        $detail = [
            'id' => $kode,
            'status' => 'Digunakan',
            'peminjam' => 'Ahmad Subagja (21120120140158)',
            'barang' => 'Proyektor InFocus X1',
            'jumlah' => '1 unit',
            'lokasi' => 'Ruang Rapat Gedung A',
            'jadwal_pinjam' => '28 Mei 2024, 09:00 WIB',
            'jadwal_kembali' => '28 Mei 2024, 12:00 WIB',
            'tujuan' => 'Untuk presentasi rapat koordinasi bulanan departemen.'
        ];

        // Timeline: Belum ada log pengembalian
        $histori = [
            ['date' => '27 Mei 2024, 14:30 WIB', 'title' => "Foto 'Sebelum' diunggah.", 'color' => 'bg-green-500'],
            ['date' => '27 Mei 2024, 10:05 WIB', 'title' => 'Peminjaman disetujui oleh Admin.', 'color' => 'bg-green-500'],
            ['date' => '27 Mei 2024, 09:15 WIB', 'title' => 'Pengajuan dibuat.', 'color' => 'bg-yellow-400']
        ];

        $data = [
            'title' => 'Detail Peminjaman',
            'detail' => $detail,
            'histori' => $histori,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Peminjaman Saya', 'url' => site_url('peminjam/histori-peminjaman')],
                ['name' => 'Detail ' . $kode],
            ]
        ];

        return view('peminjam/detail_peminjaman_view', $data);
    }
}