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
}
?>