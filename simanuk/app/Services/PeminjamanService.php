<?php

namespace App\Services;

use App\Models\Peminjaman\PeminjamanModel;
use App\Models\Peminjaman\DetailPeminjamanSaranaModel;
use App\Models\Peminjaman\DetailPeminjamanPrasaranaModel;
use App\Models\Sarpras\SaranaModel;
use App\Models\Sarpras\PrasaranaModel;
use Config\Database;

class PeminjamanService
{
   protected $peminjamanModel;
   protected $detailSaranaModel;
   protected $detailPrasaranaModel;
   protected $saranaModel;
   protected $prasaranaModel;
   protected $db;

   public function __construct()
   {
      $this->peminjamanModel      = new PeminjamanModel();
      $this->detailSaranaModel    = new DetailPeminjamanSaranaModel();
      $this->detailPrasaranaModel = new DetailPeminjamanPrasaranaModel();
      $this->saranaModel          = new SaranaModel();
      $this->prasaranaModel       = new PrasaranaModel();
      $this->db                   = Database::connect();
   }

   /**
    * Menangani pembuatan pengajuan peminjaman baru secara menyeluruh.
    *
    * @param int $userId ID User yang login
    * @param array $postData Data dari request POST (form)
    * @return int ID Peminjaman yang baru dibuat
    * @throws \Exception Jika ada validasi bisnis yang gagal
    */
   public function createSubmission(int $userId, array $postData): int
   {
      // 1. Validasi Logika Bisnis (Tanggal, Ketersediaan)
      $this->validateBusinessLogic($postData);

      // 2. Mulai Transaksi Database
      $this->db->transStart();

      try {
         $tglMulai   = $postData['tgl_pinjam_dimulai'];
         $tglSelesai = $postData['tgl_pinjam_selesai'];

         // Hitung Durasi
         $diff   = strtotime($tglSelesai) - strtotime($tglMulai);
         $durasi = round($diff / (60 * 60 * 24)) + 1;

         // A. Insert Header Peminjaman
         $dataPeminjaman = [
            'id_peminjam'              => $userId,
            'kegiatan'                 => $postData['kegiatan'],
            'tgl_pengajuan'            => date('Y-m-d H:i:s'),
            'tgl_pinjam_dimulai'       => $tglMulai,
            'tgl_pinjam_selesai'       => $tglSelesai,
            'durasi'                   => $durasi,
            'status_verifikasi'        => 'Pending',
            'status_persetujuan'       => 'Pending',
            'status_peminjaman_global' => 'Diajukan',
            'tipe_peminjaman'          => 'Peminjaman',
            'keterangan'               => $postData['keterangan'] ?? null,
         ];

         $this->peminjamanModel->insert($dataPeminjaman);
         $peminjamanId = $this->peminjamanModel->getInsertID();

         // B. Proses Item Sarana (Jika Ada)
         $itemsSarana = $postData['items']['sarana'] ?? [];
         $itemsJumlah = $postData['items']['jumlah'] ?? [];

         if (!empty($itemsSarana)) {
            foreach ($itemsSarana as $index => $idSarana) {
               // Skip jika ID kosong (bug frontend prevention)
               if (empty($idSarana)) continue;

               $jumlah = $itemsJumlah[$index] ?? 1;

               // Insert Detail
               $this->detailSaranaModel->insert([
                  'id_peminjaman' => $peminjamanId,
                  'id_sarana'     => $idSarana,
                  'jumlah'        => $jumlah,
                  'kondisi_awal'  => 'Baik', // Default asumsi saat pengajuan
               ]);
            }
         }

         // C. Proses Item Prasarana (Jika Ada)
         $itemsPrasarana = $postData['items']['prasarana'] ?? [];

         if (!empty($itemsPrasarana)) {
            foreach ($itemsPrasarana as $idPrasarana) {
               if (empty($idPrasarana)) continue;

               // Insert Detail
               $this->detailPrasaranaModel->insert([
                  'id_peminjaman' => $peminjamanId,
                  'id_prasarana'  => $idPrasarana,
                  'kondisi_awal'  => 'Baik',
               ]);
            }
         }

         // Commit Transaksi
         $this->db->transComplete();

         if ($this->db->transStatus() === false) {
            throw new \Exception('Gagal menyimpan transaksi ke database. Silakan coba lagi.');
         }

         return $peminjamanId;
      } catch (\Exception $e) {
         // Rollback jika ada error apa pun dalam blok try
         $this->db->transRollback();
         throw $e; // Lempar ulang error agar bisa ditangkap Controller untuk ditampilkan ke user
      }
   }

   /**
    * Validasi aturan bisnis sebelum data disimpan.
    * Mengecek validitas tanggal, stok sarana, dan jadwal prasarana.
    */
   private function validateBusinessLogic(array $data): void
   {
      $start = $data['tgl_pinjam_dimulai'];
      $end   = $data['tgl_pinjam_selesai'];

      // 1. Cek Validitas Tanggal
      if ($start > $end) {
         throw new \Exception('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
      }
      if ($start < date('Y-m-d')) {
         throw new \Exception('Tanggal mulai tidak boleh di masa lalu.');
      }

      // 2. Cek Minimal Satu Item Dipilih
      $hasSarana    = !empty(array_filter($data['items']['sarana'] ?? []));
      $hasPrasarana = !empty(array_filter($data['items']['prasarana'] ?? []));

      if (!$hasSarana && !$hasPrasarana) {
         throw new \Exception('Mohon pilih minimal satu Barang (Sarana) atau Ruangan (Prasarana).');
      }

      // 3. Validasi Ketersediaan Prasarana (Cek Bentrok Jadwal)
      if ($hasPrasarana) {
         foreach ($data['items']['prasarana'] as $idPrasarana) {
            if (empty($idPrasarana)) continue;

            if ($this->isPrasaranaBooked($idPrasarana, $start, $end)) {
               $ruangan = $this->prasaranaModel->find($idPrasarana);
               $nama = $ruangan['nama_prasarana'] ?? 'Ruangan';
               throw new \Exception("Ruangan '$nama' sudah dipinjam oleh kegiatan lain pada tanggal tersebut.");
            }
         }
      }

      // 4. Validasi Stok Sarana
      // (Opsional: Cek apakah stok master cukup untuk permintaan ini)
      if ($hasSarana) {
         foreach ($data['items']['sarana'] as $index => $idSarana) {
            if (empty($idSarana)) continue;

            $jumlahMinta = $data['items']['jumlah'][$index] ?? 1;
            $barang = $this->saranaModel->find($idSarana);

            if (!$barang) {
               throw new \Exception("Data sarana tidak ditemukan.");
            }

            if ($barang['jumlah'] < $jumlahMinta) {
               throw new \Exception("Stok '{$barang['nama_sarana']}' tidak mencukupi. Tersedia: {$barang['jumlah']}");
            }

            // Cek Konflik Relasi (Jika barang terikat ruangan yang sedang dipakai)
            if (!empty($barang['id_prasarana'])) {
               if ($this->isPrasaranaBooked($barang['id_prasarana'], $start, $end)) {
                  throw new \Exception("Barang '{$barang['nama_sarana']}' berada di ruangan yang sedang dipakai kegiatan lain.");
               }
            }
         }
      }
   }

   /**
    * Mengecek apakah suatu ruangan sudah dipinjam (Booked)
    * pada rentang tanggal tertentu.
    * * @return bool True jika bentrok, False jika aman.
    */
   private function isPrasaranaBooked($idPrasarana, $start, $end): bool
   {
      // Cari di detail prasarana yang terhubung ke peminjaman aktif
      // Status aktif = Disetujui atau Dipinjam (Diajukan biasanya belum memblokir jadwal, tergantung kebijakan)
      // Logika Overlap: (StartA <= EndB) and (EndA >= StartB)

      $builder = $this->detailPrasaranaModel->builder();
      $builder->select('detail_peminjaman_prasarana.id_detail_prasarana');
      $builder->join('peminjaman', 'peminjaman.id_peminjaman = detail_peminjaman_prasarana.id_peminjaman');

      $builder->where('detail_peminjaman_prasarana.id_prasarana', $idPrasarana);

      // Status yang dianggap "Memakai Ruangan"
      $builder->whereIn('peminjaman.status_peminjaman_global', ['Disetujui', 'Dipinjam']);

      // Cek Irisan Tanggal
      $builder->where('peminjaman.tgl_pinjam_dimulai <=', $end);
      $builder->where('peminjaman.tgl_pinjam_selesai >=', $start);

      return $builder->countAllResults() > 0;
   }
}
