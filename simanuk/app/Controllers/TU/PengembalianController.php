<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;

class PengembalianController extends BaseController
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

    public function index()
    {
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
            'breadcrumbs' => [
                ['name' => 'Beranda', 'url' => site_url('tu/dashboard')],
                ['name' => 'Verifikasi Pengembalian'],
            ]
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
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $itemsSarana = $this->detailSaranaModel
            ->select('detail_peminjaman_sarana.*, sarana.nama_sarana, sarana.kode_sarana, sarana.image')
            ->join('sarana', 'sarana.id_sarana = detail_peminjaman_sarana.id_sarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $itemsPrasarana = $this->detailPrasaranaModel
            ->select('detail_peminjaman_prasarana.*, prasarana.nama_prasarana, prasarana.kode_prasarana, prasarana.image')
            ->join('prasarana', 'prasarana.id_prasarana = detail_peminjaman_prasarana.id_prasarana')
            ->where('id_peminjaman', $id)
            ->findAll();

        $data = [
            'title'          => 'Detail Pengembalian',
            'peminjaman'     => $peminjaman,
            'itemsSarana'    => $itemsSarana,
            'itemsPrasarana' => $itemsPrasarana,
            'showSidebar' => true,
            'breadcrumbs'    => [
                ['name' => 'Beranda', 'url' => site_url('tu/dashboard')],
                ['name' => 'Verifikasi Pengembalian', 'url' => site_url('tu/pengembalian')],
                ['name' => 'Detail'],
            ]
        ];

        return view('tu/pengembalian/detail', $data);
    }

    public function prosesKembali($id)
    {
        // 1. Validasi Input 
        // Admin biasanya menerima input kondisi akhir per barang
        $kondisiAkhir = $this->request->getPost('kondisi_akhir'); 
        
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $items = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
            
            foreach ($items as $item) {
                $kondisi = isset($kondisiAkhir[$item['id']]) ? $kondisiAkhir[$item['id']] : 'Baik';
                $this->detailSaranaModel->update($item['id'], ['kondisi_akhir' => $kondisi]);

                $sarana = $this->saranaModel->find($item['id_sarana']);
                if ($sarana) {
                    $newStok = $sarana['jumlah'] + $item['jumlah'];
                    
                    $updateData = ['jumlah' => $newStok];
                    if ($sarana['status_ketersediaan'] == 'Tidak Tersedia' && $newStok > 0) {
                        $updateData['status_ketersediaan'] = 'Tersedia';
                    }
                    $this->saranaModel->update($item['id_sarana'], $updateData);
                }
            }

            $itemsPra = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();
            foreach ($itemsPra as $item) {
                 //update kondisi akhir saja
                 // $this->detailPrasaranaModel->update($item['id'], ['kondisi_akhir' => 'Baik']);
            }

            $this->peminjamanModel->update($id, [
                'status_peminjaman_global' => 'Selesai',
                'tgl_kembali_realisasi'    => date('Y-m-d H:i:s'),
                'updated_at'               => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses pengembalian.');
            }

            return redirect()->to(site_url('tu/pengembalian'))->with('message', 'Pengembalian berhasil diverifikasi dan status Selesai.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}