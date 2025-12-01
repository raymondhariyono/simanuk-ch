<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Services\PeminjamanService;

class VerifikasiPeminjamanController extends BaseController
{
    protected $peminjamanModel;
    protected $detailSaranaModel;
    protected $detailPrasaranaModel;
    protected $saranaModel;

    protected $peminjamanService;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
        $this->saranaModel = new SaranaModel();

        $this->peminjamanService = new PeminjamanService();
    }

    // -----------------------------------------------------------------------
    // 1. HALAMAN VERIFIKASI PEMINJAMAN (Mirip Admin Index)
    // -----------------------------------------------------------------------
    public function index()
    {
        // 1. Ambil semua data (Header + User info)
        // Urutkan: Diajukan paling atas (Prioritas), lalu tanggal terbaru
        $allData = $this->peminjamanModel
            ->select('peminjaman.*, users.nama_lengkap, users.organisasi, users.username')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->orderBy("FIELD(status_peminjaman_global, 'Diajukan', 'Disetujui', 'Dipinjam', 'Selesai', 'Ditolak', 'Dibatalkan')")
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // tab-tab dalam view
        $pending = [];  // Tab 1: Verifikasi Baru
        $active  = [];  // Tab 2: Sedang Berjalan (Disetujui/Dipinjam)
        $history = [];  // Tab 3: Riwayat

        foreach ($allData as $row) {
            $status = $row['status_peminjaman_global'];

            if ($status == 'Diajukan') {
                $pending[] = $row;
            } elseif (in_array($status, ['Disetujui', 'Dipinjam'])) {
                $active[] = $row;
            } else {
                $history[] = $row;
            }
        }

        // 1. JALANKAN AUTO CANCEL
        $canceledCount = $this->peminjamanService->autoCancelExpiredLoans();

        // Beri notifikasi flash message jika ada yang dibatalkan
        if ($canceledCount > 0) {
            session()->setFlashdata('info', "Sistem otomatis membatalkan $canceledCount pengajuan yang kedaluwarsa.");
        }

        $dataPeminjaman = $this->peminjamanModel
            ->select('peminjaman.*, users.username, users.nama_lengkap, users.organisasi')
            ->join('users', 'users.id = peminjaman.id_peminjam')
            ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIAJUKAN)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data = [
            'title'       => 'Verifikasi Peminjaman',
            'peminjaman'  => $dataPeminjaman,
            // tab-tab
            'pendingLoans' => $pending,
            'activeLoans'  => $active,
            'historyLoans' => $history,
            'showSidebar' => true,
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
                ['name' => 'Verifikasi Peminjaman', 'url' => site_url('tu/verifikasi-peminjaman')],
                ['name' => 'Detail Peminjaman']
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
                'status_verifikasi'        => PeminjamanModel::STATUS_DISETUJUI,
                'status_persetujuan'       => PeminjamanModel::STATUS_DISETUJUI,
                'status_peminjaman_global' => PeminjamanModel::STATUS_DISETUJUI,
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
            'status_verifikasi'        => PeminjamanModel::STATUS_DITOLAK,
            'status_persetujuan'       => PeminjamanModel::STATUS_DITOLAK,
            'status_peminjaman_global' => PeminjamanModel::STATUS_DITOLAK,
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
            ->where('status_peminjaman_global', PeminjamanModel::STATUS_DIPINJAM)
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
                'status_peminjaman_global' => PeminjamanModel::STATUS_SELESAI,
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
