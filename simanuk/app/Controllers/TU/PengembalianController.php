<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;

class PengembalianController extends BaseController
{
    protected $peminjamanModel;
    protected $detailSaranaModel;
    protected $detailPrasaranaModel;
    protected $saranaModel;
    protected $prasaranaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
        $this->saranaModel = new SaranaModel();
        $this->prasaranaModel = new PrasaranaModel();
    }

    public function index()
    {
        // Tampilkan peminjaman yang statusnya 'Dipinjam'
        $dataPeminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.username, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->whereIn('status_peminjaman_global', ['Dipinjam'])
            ->orderBy('tgl_pinjam_selesai', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Verifikasi Pengembalian',
            'peminjaman'  => $dataPeminjaman,
            'showSidebar' => true,
        ];

        return view('tu/pengembalian/index', $data);
    }

    public function detail($id)
    {
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.kontak, users.username')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('peminjaman.id_peminjaman', $id)
            ->first();

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Ambil Detail Sarana
        $itemsSarana = $this->detailSaranaModel
            ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        // Ambil Detail Prasarana
        $itemsPrasarana = $this->detailPrasaranaModel
            ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $data = [
            'title'          => 'Detail Pengembalian',
            'peminjaman'     => $peminjaman,
            'itemsSarana'    => $itemsSarana,
            'itemsPrasarana' => $itemsPrasarana,
            'showSidebar'    => true,
            'breadcrumbs'    => [
                ['name' => 'Verifikasi Pengembalian', 'url' => site_url('tu/pengembalian')],
                ['name' => 'Detail Pengembalian'],
            ]
        ];

        return view('tu/pengembalian/detail', $data);
    }

    public function prosesKembali($id)
    {
        // Cek data peminjaman
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman || $peminjaman['status_peminjaman_global'] != 'Dipinjam') {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        // Ambil Input Kondisi Akhir dari Form
        // Format array: [id_detail => kondisi]
        $kondisiAkhirSarana = $this->request->getPost('kondisi_akhir_sarana');
        $kondisiAkhirPrasarana = $this->request->getPost('kondisi_akhir_prasarana');

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. PROSES PENGEMBALIAN SARANA (BARANG)
            $itemsSarana = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();

            foreach ($itemsSarana as $item) {
                // A. Update Kondisi di Tabel Detail
                $kondisi = isset($kondisiAkhirSarana[$item['id_detail_sarana']])
                    ? $kondisiAkhirSarana[$item['id_detail_sarana']]
                    : 'Baik';

                $this->detailSaranaModel->update($item['id_detail_sarana'], [
                    'kondisi_akhir' => $kondisi
                ]);

                // B. Kembalikan Stok ke Master Sarana
                $sarana = $this->saranaModel->find($item['id_sarana']);
                if ($sarana) {
                    $newStok = $sarana['jumlah'] + $item['jumlah'];

                    $updateData = ['jumlah' => $newStok];
                    // Jika status sebelumnya 'Tidak Tersedia'/'Dipinjam' dan sekarang stok ada, set 'Tersedia'
                    if ($newStok > 0) {
                        $updateData['status_ketersediaan'] = 'Tersedia';
                    }

                    $this->saranaModel->update($item['id_sarana'], $updateData);
                }
            }

            // 2. PROSES PENGEMBALIAN PRASARANA (RUANGAN)
            $itemsPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();

            foreach ($itemsPrasarana as $item) {
                // A. Update Kondisi di Tabel Detail
                $kondisi = isset($kondisiAkhirPrasarana[$item['id_detail_prasarana']])
                    ? $kondisiAkhirPrasarana[$item['id_detail_prasarana']]
                    : 'Baik';

                $this->detailPrasaranaModel->update($item['id_detail_prasarana'], [
                    'kondisi_akhir' => $kondisi
                ]);

                // B. Ubah Status Ruangan menjadi 'Tersedia'
                $this->prasaranaModel->update($item['id_prasarana'], [
                    'status_ketersediaan' => 'Tersedia'
                ]);
            }

            // 3. UPDATE STATUS GLOBAL PEMINJAMAN
            $this->peminjamanModel->update($id, [
                'status_peminjaman_global' => PeminjamanModel::STATUS_SELESAI,
                'updated_at'               => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses pengembalian. Transaksi dibatalkan.');
            }

            return redirect()->to(site_url('tu/pengembalian'))->with('message', 'Pengembalian berhasil diverifikasi. Stok dan status telah diperbarui.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
