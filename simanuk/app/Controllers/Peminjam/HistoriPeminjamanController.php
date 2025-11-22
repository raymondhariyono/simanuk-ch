<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\PeminjamanModel;

class HistoriPeminjamanController extends BaseController
{
    protected $peminjamanModel;
    protected $detailSaranaModel;
    protected $detailPrasaranaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
    }

    public function index()
    {
        $userId = auth()->user()->id;

        // 1. Ambil semua data peminjaman milik user ini
        // Urutkan dari yang terbaru
        $listPeminjaman = $this->peminjamanModel
            ->where('id_peminjam', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $loans = [];

        // 2. Loop setiap transaksi untuk mengambil detail itemnya
        foreach ($listPeminjaman as $pinjam) {

            // A. Ambil Detail Sarana (Barang)
            // Join ke tabel 'sarana' untuk dapat nama & kode
            $itemsSarana = $this->detailSaranaModel
                ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
                ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
                ->where('id_peminjaman', $pinjam['id_peminjaman'])
                ->findAll();

            foreach ($itemsSarana as $item) {
                $loans[] = [
                    'id_peminjaman' => $pinjam['id_peminjaman'], // ID Transaksi (untuk aksi batal)
                    'id_detail'     => $item['id_detail_sarana'],
                    'nama_item'     => $item['nama_sarana'],
                    'kode'          => $item['kode_sarana'],
                    'kegiatan'      => $pinjam['kegiatan'],
                    'tgl_pinjam'    => $pinjam['tgl_pinjam_dimulai'], // Opsional: bisa ditampilkan
                    // Gunakan status global untuk user
                    'status'        => $pinjam['status_peminjaman_global'],
                    // Tentukan jenis aksi berdasarkan status
                    'aksi'          => $this->determineAction($pinjam['status_peminjaman_global']),
                    'tipe'          => 'Sarana'
                ];
            }

            // B. Ambil Detail Prasarana (Ruangan)
            // Join ke tabel 'prasarana'
            $itemsPrasarana = $this->detailPrasaranaModel
                ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
                ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
                ->where('id_peminjaman', $pinjam['id_peminjaman'])
                ->findAll();

            foreach ($itemsPrasarana as $item) {
                $loans[] = [
                    'id_peminjaman' => $pinjam['id_peminjaman'],
                    'id_detail'     => $item['id_detail_prasarana'],
                    'nama_item'     => $item['nama_prasarana'],
                    'kode'          => $item['kode_prasarana'],
                    'kegiatan'      => $pinjam['kegiatan'],
                    'tgl_pinjam'    => $pinjam['tgl_pinjam_dimulai'],
                    'status'        => $pinjam['status_peminjaman_global'],
                    'aksi'          => $this->determineAction($pinjam['status_peminjaman_global']),
                    'tipe'          => 'Prasarana'
                ];
            }
        }

        $data = [
            'title'       => 'Histori Peminjaman',
            'loans'       => $loans,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Histori Peminjaman'],
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
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Peminjaman Saya', 'url' => site_url('peminjam/histori-peminjaman')],
                ['name' => 'Detail ' . $kode],
            ]
        ];

        return view('peminjam/detail_peminjaman_view', $data);
    }

    /**
     * Helper untuk menentukan label tombol aksi
     */
    private function determineAction($status)
    {
        // Logika Tombol berdasarkan Status
        switch ($status) {
            case 'Diajukan':
                return 'Batal'; // Masih bisa dibatalkan user
            case 'Disetujui':
                return 'Lihat Detail'; // Menunggu diambil/digunakan
            case 'Dipinjam':
                return 'Kembalikan'; // Sedang dipinjam
            default:
                return null; // Selesai/Ditolak/Dibatalkan (Tidak ada aksi)
        }
    }
}
