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

        // mencari peminjaman yang aktif dan telah selesai (dikembalikan)
        $activeLoans = [];
        $historyLoans = [];

        $listPeminjamanDitolak = $this->peminjamanModel
            ->where('id_peminjam', $userId)
            ->where('status_peminjaman_global', 'Ditolak')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $loans = [];

        // 2. Loop setiap transaksi untuk mengambil detail itemnya
        foreach ($listPeminjaman as $pinjam) {
            // Tentukan kategori: Aktif atau Riwayat
            $status = $pinjam['status_peminjaman_global'];
            $isHistory = in_array($status, ['Selesai', 'Ditolak', 'Dibatalkan']);

            // A. Ambil Detail Sarana (Barang)
            // Join ke tabel 'sarana' untuk dapat nama & kode
            $itemsSarana = $this->detailSaranaModel
                ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
                ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
                ->where('id_peminjaman', $pinjam['id_peminjaman'])
                ->findAll();

            foreach ($itemsSarana as $item) {
                $dataItem = [
                    'id_peminjaman' => $pinjam['id_peminjaman'], // ID Transaksi (untuk aksi batal)
                    'id_detail'     => $item['id_detail_sarana'],
                    'nama_item'     => $item['nama_sarana'],
                    'kode'          => $item['kode_sarana'],
                    'kegiatan'      => $pinjam['kegiatan'],
                    'tgl_pinjam'    => $pinjam['tgl_pinjam_dimulai'],
                    'tgl_selesai'   => $pinjam['tgl_pinjam_selesai'], // tambahan untuk history
                    'status'        => $status,
                    'foto_sebelum'  => $item['foto_sebelum'],
                    'foto_sesudah'  => $item['foto_sesudah'],
                    'keterangan'    => $pinjam['keterangan'],
                    // Tentukan jenis aksi berdasarkan status
                    'aksi'          => $this->determineAction($pinjam['status_peminjaman_global']),
                    'tipe'          => 'Sarana'
                ];

                // logika history pengembalian
                if ($isHistory) {
                    $historyLoans[] = $dataItem;
                } else {
                    $activeLoans[] = $dataItem;
                }
            }

            // B. Ambil Detail Prasarana (Ruangan)
            // Join ke tabel 'prasarana'
            $itemsPrasarana = $this->detailPrasaranaModel
                ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
                ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
                ->where('id_peminjaman', $pinjam['id_peminjaman'])
                ->findAll();

            foreach ($itemsPrasarana as $item) {
                $dataItem = [
                    'id_peminjaman' => $pinjam['id_peminjaman'],
                    'id_detail'     => $item['id_detail_prasarana'],
                    'nama_item'     => $item['nama_prasarana'],
                    'kode'          => $item['kode_prasarana'],
                    'kegiatan'      => $pinjam['kegiatan'],
                    'tgl_pinjam'    => $pinjam['tgl_pinjam_dimulai'],
                    'tgl_selesai'   => $pinjam['tgl_pinjam_selesai'], // tambahan untuk history
                    'status'        => $status,
                    'foto_sebelum'  => $item['foto_sebelum'],
                    'foto_sesudah'  => $item['foto_sesudah'],
                    'keterangan'    => $pinjam['keterangan'],
                    'aksi'          => $this->determineAction($pinjam['status_peminjaman_global']),
                    'tipe'          => 'Prasarana'
                ];

                if ($isHistory) {
                    $historyLoans[] = $dataItem;
                } else {
                    $activeLoans[] = $dataItem;
                }
            }
        }
        $data = [
            'title'       => 'Histori Peminjaman',
            // 'loans'       => $loans,
            'activeLoans'  => $activeLoans,  // Data untuk Tab 1
            'historyLoans' => $historyLoans, // Data untuk Tab 2
            'peminjaman'  => $listPeminjamanDitolak,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Histori Peminjaman'],
            ]
        ];


        // Memuat file view yang akan kita buat selanjutnya
        return view('peminjam/histori_peminjaman_view', $data);
    }

    public function detail($id)
    {
        $userId = auth()->user()->id;

        // 1. Ambil Header Peminjaman & Pastikan milik user yang login
        // $peminjaman = $this->peminjamanModel->find($id);
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.kontak')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('peminjaman.id_peminjaman', $id)
            ->first();

        if (!$peminjaman || $peminjaman['id_peminjam'] != $userId) {
            return redirect()->to('peminjam/histori-peminjaman')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        // 2. Ambil Detail Item (Sarana)
        // Kita join ke tabel sarana untuk dapat nama & kode
        $itemsSarana = $this->detailSaranaModel
            ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        // 3. Ambil Detail Item (Prasarana) jika ada
        $itemsPrasarana = $this->detailPrasaranaModel
            ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $data = [
            'title' => 'Detail Peminjaman & Pengembalian',
            'peminjaman' => $peminjaman,
            'itemsSarana' => $itemsSarana,
            'itemsPrasarana' => $itemsPrasarana,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('peminjam/dashboard')],
                ['name' => 'Histori', 'url' => site_url('peminjam/histori-peminjaman')],
                ['name' => 'Detail Peminjaman'],
            ]
        ];

        // Arahkan ke view detail_peminjaman_view.php
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
                return 'Upload Foto Sebelum'; // Menunggu diambil/digunakan
            case 'Dipinjam':
                return 'Upload Foto Sesudah'; // Sedang dipinjam
            case 'Selesai':
                return 'Lihat Riwayat'; // Sedang dipinjam
            default:
                return 'Lihat Alasan'; // Selesai/Ditolak/Dibatalkan (Tidak ada aksi)
        }
    }
}
