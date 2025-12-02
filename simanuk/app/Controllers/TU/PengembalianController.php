<?php

namespace App\Controllers\TU;

use App\Controllers\BaseController;
use App\Models\LaporanKerusakanModel;
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

    protected $laporanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->detailSaranaModel = new DetailPeminjamanSaranaModel();
        $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
        $this->saranaModel = new SaranaModel();
        $this->prasaranaModel = new PrasaranaModel();

        $this->laporanModel = new LaporanKerusakanModel();
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
                ['name' => 'Verifikasi Pengembalian', 'url' => site_url('tu/verifikasi-pengembalian')],
                ['name' => 'Detail Pengembalian'],
            ]
        ];

        return view('tu/pengembalian/detail', $data);
    }

    /**
     * Fitur untuk menolak foto bukti (Sebelum/Sesudah)
     * @param string $tipe 'sarana' atau 'prasarana'
     * @param string $jenisFoto 'sebelum' atau 'sesudah'
     * @param int $idDetail
     */
    public function tolakFoto($tipe, $jenisFoto, $idDetail)
    {
        $alasan = $this->request->getPost('alasan');
        if (empty($alasan)) {
            return redirect()->back()->with('error', 'Harap isi alasan penolakan foto.');
        }

        // Tentukan Model & Kolom
        $model = ($tipe == 'sarana') ? $this->detailSaranaModel : $this->detailPrasaranaModel;
        $kolomFoto = ($jenisFoto == 'sebelum') ? 'foto_sebelum' : 'foto_sesudah';

        // 1. Ambil Data Lama untuk Hapus File Fisik
        $item = $model->find($idDetail);
        $pathLama = $item[$kolomFoto];

        if ($pathLama && is_file(FCPATH . $pathLama)) {
            unlink(FCPATH . $pathLama);
        }

        // 2. Update Database: Kosongkan Foto & Isi Catatan
        $updateData = [
            $kolomFoto => null, // Reset foto jadi null
            'catatan_penolakan' => "Foto $jenisFoto DITOLAK: " . $alasan
        ];

        // Khusus jika menolak foto 'sebelum', status global mungkin perlu dikembalikan
        // Tapi agar simple, kita cukup reset fotonya saja. 
        // Logika di View User akan mendeteksi 'foto_sebelum' kosong -> Munculkan tombol upload.

        $model->update($idDetail, $updateData);

        return redirect()->back()->with('message', 'Foto berhasil ditolak. User diminta upload ulang.');
    }

    public function prosesKembali($id)
    {
        // 1. Cek Validitas Data Peminjaman
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman || $peminjaman['status_peminjaman_global'] != 'Dipinjam') {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        // 2. Ambil Input Kondisi dari Form
        $kondisiAkhirSarana = $this->request->getPost('kondisi_akhir_sarana');
        $kondisiAkhirPrasarana = $this->request->getPost('kondisi_akhir_prasarana');

        // Validasi: Pastikan admin memilih kondisi
        if (empty($kondisiAkhirSarana) && empty($kondisiAkhirPrasarana)) {
            return redirect()->back()->with('error', 'Mohon konfirmasi kondisi barang pada form.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // --- PROSES SARANA (BARANG) ---
            $itemsSarana = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();

            // Gunakan Model Laporan yang sudah di-init di constructor
            $laporanModel = $this->laporanModel;

            foreach ($itemsSarana as $item) {
                // A. Validasi Input per Item
                if (!isset($kondisiAkhirSarana[$item['id_detail_sarana']])) {
                    // Skip jika input tidak ada, jangan set default 'Baik' sembarangan
                    continue;
                }

                $kondisi = $kondisiAkhirSarana[$item['id_detail_sarana']];

                // B. Update Status Detail Peminjaman (Database Transaksi)
                $this->detailSaranaModel->update($item['id_detail_sarana'], [
                    'kondisi_akhir' => $kondisi
                ]);

                // C. LOGIKA RETURN GATE PRIORITY (PINTU UTAMA STOK)
                $sarana = $this->saranaModel->find($item['id_sarana']);

                if ($sarana) {
                    if ($kondisi === 'Baik') {
                        // KASUS 1: BARANG BAIK -> KEMBALIKAN STOK
                        $newStok = $sarana['jumlah'] + $item['jumlah'];

                        $this->saranaModel->update($item['id_sarana'], [
                            'jumlah' => $newStok,
                            'status_ketersediaan' => ($newStok > 0) ? 'Tersedia' : 'Tidak Tersedia'
                        ]);
                    } else {
                        // KASUS 2: BARANG RUSAK -> TAHAN STOK
                        // Jangan tambahkan stok.

                        // Cari Laporan Kerusakan (Hapus filter 'Diajukan' agar lebih robust)
                        $laporan = $laporanModel->where('id_peminjaman', $id)
                            ->where('id_sarana', $item['id_sarana'])
                            ->first();

                        if ($laporan) {
                            // OPSI A: LAPORAN SUDAH ADA (Dari Peminjam)
                            // Update status jadi 'Diproses'
                            $laporanModel->update($laporan['id_laporan'], [
                                'status_laporan' => 'Diproses',
                                'tindak_lanjut'  => 'Diverifikasi Admin saat pengembalian: ' . $kondisi,
                                'updated_at'     => date('Y-m-d H:i:s')
                            ]);
                        } else {
                            // OPSI B: LAPORAN BELUM ADA (Kasus Peminjam Lupa/Salah Lapor)
                            // BUAT LAPORAN BARU OTOMATIS
                            $laporanModel->save([
                                'id_pelapor'          => auth()->user()->id, // Admin sebagai pelapor sistem
                                'tipe_aset'           => 'Sarana',
                                'id_peminjaman'       => $id,
                                'id_sarana'           => $item['id_sarana'],
                                'judul_laporan'       => "Kerusakan ditemukan saat Pengembalian ($kondisi)",
                                'deskripsi_kerusakan' => "Barang ditemukan dalam kondisi $kondisi saat verifikasi pengembalian oleh Admin.",
                                'bukti_foto'          => $item['foto_sesudah'] ?? null, // Pakai foto dari detail jika ada
                                'status_laporan'      => 'Diproses', // Langsung Diproses
                                'jumlah'              => $item['jumlah'],
                                'tindak_lanjut'       => 'Menunggu perbaikan/penggantian.'
                            ]);
                        }
                    }
                }
            }

            // --- PROSES PRASARANA (RUANGAN) ---
            $itemsPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();
            foreach ($itemsPrasarana as $item) {
                $kondisiP = $kondisiAkhirPrasarana[$item['id_detail_prasarana']] ?? 'Baik';

                $this->detailPrasaranaModel->update($item['id_detail_prasarana'], ['kondisi_akhir' => $kondisiP]);

                // Ruangan selalu kembali tersedia
                $this->prasaranaModel->update($item['id_prasarana'], ['status_ketersediaan' => 'Tersedia']);
            }

            // 3. Finalisasi Status Peminjaman Global
            $this->peminjamanModel->update($id, [
                'status_peminjaman_global' => 'Selesai',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses transaksi.');
            }

            return redirect()->to(site_url('admin/pengembalian'))->with('message', 'Pengembalian selesai. Stok barang BAIK telah dikembalikan, barang RUSAK tercatat di laporan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * PROSES UTAMA: Selesai & Restock
     */
    public function prosesSelesai($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman || $peminjaman['status_peminjaman_global'] != PeminjamanModel::STATUS_DIPINJAM) {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. RESTOCK SARANA (Barang)
            $itemsSarana = $this->detailSaranaModel->where('id_peminjaman', $id)->findAll();
            // $itemsPrasarana = $this->detailPrasaranaModel->where('id_peminjaman', $id)->findAll();

            foreach ($itemsSarana as $item) {
                $sarana = $this->saranaModel->find($item['id_sarana']);

                // Rumus: Stok Lama + Jumlah Dikembalikan
                $stokBaru = $sarana['jumlah'] + $item['jumlah'];

                // Update Stok & Status Availability
                $updateData = ['jumlah' => $stokBaru];
                // jika stok > 0, maka tersedia
                if ($stokBaru > 0) {
                    $updateData['status_ketersediaan'] = 'Tersedia';
                }

                $this->saranaModel->update($item['id_sarana'], $updateData);
            }

            // foreach ($itemsPrasarana as $item) {
            //    // Untuk ruangan, cukup set status jadi Tersedia kembali
            //    $this->prasaranaModel->update($item['id_prasarana'], [
            //       'status_ketersediaan' => 'Tersedia'
            //    ]);
            // }

            // 3. UPDATE STATUS GLOBAL -> SELESAI
            $this->peminjamanModel->update($id, [
                'status_peminjaman_global' => PeminjamanModel::STATUS_SELESAI,
                // Opsional: Catat tanggal pengembalian aktual admin
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Gagal memproses pengembalian.');
            }

            return redirect()->to(site_url('admin/pengembalian'))->with('message', 'Pengembalian berhasil diverifikasi. Stok telah dikembalikan.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
