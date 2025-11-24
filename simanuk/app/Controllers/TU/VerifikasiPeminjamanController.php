<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;

class VerifikasiPeminjamanController extends BaseController
{
    protected $peminjamanModel;
    protected $detailSaranaModel;
    protected $detailPrasaranaModel;
    protected $saranaModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
        $this->saranaModel = new SaranaModel();
    }

    // -----------------------------------------------------------------------
    // 1. HALAMAN VERIFIKASI PEMINJAMAN (Mirip Admin Index)
    // -----------------------------------------------------------------------
    public function index()
    {
        $dataPeminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.username, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('status_peminjaman_global', 'Diajukan')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title'       => 'Verifikasi Peminjaman Masuk',
            'peminjaman'  => $dataPeminjaman,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('tu/dashboard')],
                ['name' => 'Verifikasi Peminjaman']
            ]
        ];

        return view('tu/peminjaman/index', $data);
    }

    public function detail($id)
    {
        $peminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.kontak')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('peminjaman.id_peminjaman', $id)
            ->first();

        if (!$peminjaman) {
            return redirect()->to(site_url('tu/verifikasi-peminjaman'))->with('error', 'Data tidak ditemukan.');
        }

        $itemsSarana = $this->detailSaranaModel
            ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $itemsPrasarana = $this->detailPrasaranaModel
            ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $data = [
            'title'          => 'Detail Verifikasi',
            'peminjaman'     => $peminjaman,
            'itemsSarana'    => $itemsSarana,
            'itemsPrasarana' => $itemsPrasarana,
            'showSidebar'    => true,
            'breadcrumbs'    => [
                ['name' => 'Beranda', 'url' => site_url('tu/dashboard')],
                ['name' => 'Verifikasi', 'url' => site_url('tu/verifikasi-peminjaman')],
                ['name' => 'Detail']
            ]
        ];

        return view('tu/peminjaman/detail', $data);
    }

    public function approve($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Cek Stok Sarana
            $items = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
            foreach ($items as $item) {
                $sarana = $this->saranaModel->find($item['id_sarana']);
                if ($sarana['jumlah'] < $item['jumlah']) {
                    throw new \Exception("Stok '{$sarana['nama_sarana']}' tidak mencukupi.");
                }
                // Kurangi stok
                $newStok = $sarana['jumlah'] - $item['jumlah'];
                $this->saranaModel->update($item['id_sarana'], ['jumlah' => $newStok]);
            }

            $this->peminjamanModel->update($id, [
                'status_verifikasi'        => 'Disetujui',
                'status_persetujuan'       => 'Disetujui',
                'status_peminjaman_global' => 'Disetujui', 
                'id_tu_approver'           => auth()->user()->id
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses data.');
            }

            return redirect()->to(site_url('tu/verifikasi-peminjaman'))->with('message', 'Peminjaman berhasil disetujui.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Proses Tolak
    public function reject($id)
    {
        $alasan = $this->request->getPost('alasan_tolak');
        $peminjaman = $this->peminjamanModel->find($id);
        
        $keteranganBaru = $peminjaman['keterangan'] . " [DITOLAK TU: $alasan]";

        $this->peminjamanModel->update($id, [
            'status_verifikasi'        => 'Ditolak',
            'status_persetujuan'       => 'Ditolak',
            'status_peminjaman_global' => 'Ditolak',
            'keterangan'               => $keteranganBaru,
            'id_tu_approver'           => auth()->user()->id
        ]);

        return redirect()->to(site_url('tu/verifikasi-peminjaman'))->with('message', 'Peminjaman ditolak.');
    }

    // -----------------------------------------------------------------------
    // 2. HALAMAN VERIFIKASI PENGEMBALIAN
    // -----------------------------------------------------------------------
    public function indexPengembalian()
    {
        $dataPeminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('status_peminjaman_global', 'Dipinjam') 
            ->orderBy('tgl_pinjam_selesai', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Verifikasi Pengembalian Barang',
            'peminjaman'  => $dataPeminjaman,
            'showSidebar' => true,
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('tu/dashboard')],
                ['name' => 'Verifikasi Pengembalian']
            ]
        ];

        return view('tu/verifikasi_pengembalian/index', $data);
    }

    public function complete($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $items = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
            foreach ($items as $item) {
                $sarana = $this->saranaModel->find($item['id_sarana']);
                $newStok = $sarana['jumlah'] + $item['jumlah'];
                
                $updateData = ['jumlah' => $newStok];
                if ($sarana['status_ketersediaan'] == 'Tidak Tersedia' && $newStok > 0) {
                    $updateData['status_ketersediaan'] = 'Tersedia';
                }
                
                $this->saranaModel->update($item['id_sarana'], $updateData);
            }

            $this->peminjamanModel->update($id, [
                'status_peminjaman_global' => 'Selesai',
                'updated_at'               => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();
            return redirect()->to(site_url('tu/verifikasi-pengembalian'))->with('message', 'Barang telah dikembalikan dan transaksi Selesai.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}