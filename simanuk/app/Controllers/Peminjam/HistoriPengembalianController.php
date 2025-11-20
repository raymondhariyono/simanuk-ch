<?php
namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;

class HistoriPengembalianController extends BaseController
{
    public function index()
    {

        $dummyReturns = [
            [
                'nama_item' => 'Proyektor Epson EB-X450',
                'kode' => 'PRJ-001',
                'kegiatan' => 'Seminar Nasional',
                'status' => 'Selesai',
            ],
            [
                'nama_item' => 'Kamera DSLR Canon 80D',
                'kode' => 'CAM-003',
                'kegiatan' => 'IT Fest',
                'status' => 'Selesai',
            ],
            [
                'nama_item' => 'Kursi Kantor Ergonomis',
                'kode' => 'KRS-021',
                'kegiatan' => 'Makrab',
                'status' => 'Selesai',
            ],
            [
                'nama_item' => 'Lapangan Basket',
                'kode' => 'LPN-001',
                'kegiatan' => 'Diesna',
                'status' => 'Selesai',
            ],
            [
                'nama_item' => 'Aula 1',
                'kode' => 'RUA-001',
                'kegiatan' => 'Upgrading Pengurus',
                'status' => 'Selesai',
            ],
        ];

        $data = [
            'title'       => 'Histori Pengembalian',
            'returns'     => $dummyReturns,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Histori Pengembalian'],
            ]
        ];

        return view('peminjam/histori_pengembalian_view', $data);
    }

        public function detail($kode)
    {
        // Data Dummy (Pura-pura ambil dari Database berdasarkan $kode)
        $detailPeminjaman = [
            'id' => 'LTP-005',
            'status' => 'Digunakan', // Status untuk badge
            'peminjam' => 'Ahmad Subagja (21120120140158)',
            'barang' => 'Proyektor InFocus X1',
            'jumlah' => '1 unit',
            'lokasi' => 'Ruang Rapat Gedung A',
            'jadwal_pinjam' => '28 Mei 2024, 09:00 WIB',
            'jadwal_kembali' => '28 Mei 2024, 12:00 WIB',
            'tujuan' => 'Untuk presentasi rapat koordinasi bulanan departemen.'
        ];

        // Data Dummy Histori Timeline
        $histori = [
            [
                'date' => '27 Mei 2024, 14:30 WIB',
                'title' => "Foto 'Sebelum' diunggah.",
                'color' => 'bg-green-500'
            ],
            [
                'date' => '27 Mei 2024, 10:05 WIB',
                'title' => 'Peminjaman disetujui oleh Admin Sarpras.',
                'color' => 'bg-green-500'
            ],
            [
                'date' => '27 Mei 2024, 09:15 WIB',
                'title' => 'Pengajuan peminjaman dibuat.',
                'color' => 'bg-yellow-400'
            ]
        ];

        $data = [
            'title' => 'Detail Peminjaman',
            'detail' => $detailPeminjaman,
            'histori' => $histori,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Histori Pengembalian', 'url' => site_url('peminjam/histori-pengembalian')],
                ['name' => 'Detail ' . $kode],
            ]
        ];

        return view('peminjam/detail_pengembalian_view', $data);
    }
}
?>