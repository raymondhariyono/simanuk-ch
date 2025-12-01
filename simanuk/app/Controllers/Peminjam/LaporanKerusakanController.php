<?php

namespace App\Controllers\Peminjam;

use App\Controllers\BaseController;
use App\Models\LaporanKerusakanModel;
use App\Models\Sarpras\PrasaranaModel;
use App\Models\Sarpras\SaranaModel;

class LaporanKerusakanController extends BaseController
{
    protected $laporanModel;
    protected $saranaModel;
    protected $prasaranaModel;

    public function __construct()
    {
        $this->laporanModel = new LaporanKerusakanModel();
        $this->saranaModel = new SaranaModel();
        $this->prasaranaModel = new PrasaranaModel();
    }

    public function index()
    {
        $userId = auth()->user()->id;
        // ambil riwayat berdasarkan waktu dibuatnya laporan
        $riwayat = $this->laporanModel->where('id_peminjam', $userId)->orderBy('created_at', 'DESC')->findAll();

        // Manual Mapping Nama Aset (Untuk menghindari join kompleks di model)
        foreach ($riwayat as &$r) {
            if ($r['tipe_aset'] == 'Sarana') {
                $item = $this->saranaModel->find($r['id_sarana']);
                $r['nama_aset'] = $item['nama_sarana'] ?? 'Item / Sarana Terhapus';
                $r['kode_aset'] = $item['kode_sarana'] ?? '-';
            } else {
                $item = $this->prasaranaModel->find($r['id_prasarana']);
                $r['nama_aset'] = $item['nama_prasarana'] ?? 'Prasarana Terhapus';
                $r['kode_aset'] = $item['kode_prasarana'] ?? '-';
            }
        }

        $data = [
            'title' => 'Laporan Kerusakan',
            'riwayat' => $riwayat,
            'showSidebar' => true,
        ];

        return view('peminjam/laporan/laporan_kerusakan_view', $data);
    }

    // Form Buat Laporan
    public function new()
    {
        $data = [
            'title' => 'Buat Laporan Baru',
            // Kirim data untuk dropdown
            'saranaList' => $this->saranaModel->findAll(),
            'prasaranaList' => $this->prasaranaModel->findAll(),
            'breadcrumbs' => [
                ['name' => 'Laporan Kerusakan', 'url' => site_url('peminjam/laporan-kerusakan')],
                ['name' => 'Buat Baru'],
            ]
        ];
        return view('peminjam/laporan/laporan_kerusakan_create_view', $data);
    }

    // Proses Simpan
    public function create()
    {
        if (!$this->validate([
            'tipe_aset' => 'required',
            'judul_laporan' => 'required|min_length[5]',
            'bukti_foto' => 'uploaded[bukti_foto]|is_image[bukti_foto]|max_size[bukti_foto,2048]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Upload Foto menggunakan Helper
        $file = $this->request->getFile('bukti_foto');
        $pathFoto = upload_file($file, 'uploads/laporan_kerusakan');

        $data = [
            'id_peminjam' => auth()->user()->id,
            'tipe_aset' => $this->request->getPost('tipe_aset'),
            'judul_laporan' => $this->request->getPost('judul_laporan'),
            'deskripsi_kerusakan' => $this->request->getPost('deskripsi'),
            'bukti_foto' => $pathFoto,
            'status_laporan' => 'Diajukan'
        ];

        // Tentukan ID berdasarkan tipe
        if ($data['tipe_aset'] == 'Sarana') {
            $data['id_sarana'] = $this->request->getPost('id_sarana');
            $data['id_prasarana'] = null;
        } else {
            $data['id_sarana'] = null;
            $data['id_prasarana'] = $this->request->getPost('id_prasarana');
        }

        $this->laporanModel->save($data);

        return redirect()->to(site_url('peminjam/laporan-kerusakan'))->with('message', 'Laporan berhasil dikirim.');
    }
}
