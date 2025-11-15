<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SaranaSeeder extends Seeder
{
    public function run()
    {
        // mengambil data yang mengasumsikan ID dari seeder sebelumnya
        $data = [
            [
                'id_prasarana'      => 1, // 1 = Ruang Rapat Utama
                'id_kategori'       => 2, // 2 = Elektronik
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_sarana'       => 'Proyektor Portabel Epson',
                'kode_sarana'       => 'FT-INV-PRO-001',
                'jumlah'            => 5,
                'spesifikasi'       => json_encode(['Resolusi' => '1080p', 'Lumens' => 3000]),
                'deskripsi'         => 'Proyektor portabel untuk presentasi.',
                'kondisi'           => 'Baik',
                'status_ketersediaan' => 'Tersedia',
                'created_at'        => Time::now(),
            ],
            [
                'id_prasarana'      => 2, // 2 = Laboratorium Komputer Dasar
                'id_kategori'       => 2, // 2 = Elektronik
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_sarana'       => 'PC Unit Dell Optiplex',
                'kode_sarana'       => 'FT-INV-PC-001',
                'jumlah'            => 40,
                'spesifikasi'       => json_encode(['RAM' => '8GB', 'Storage' => '256GB SSD', 'CPU' => 'Core i5']),
                'deskripsi'         => 'PC Desktop untuk praktikum mahasiswa.',
                'kondisi'           => 'Baik',
                'status_ketersediaan' => 'Tersedia', // Status ini akan dikontrol oleh peminjaman ruangan
                'created_at'        => Time::now(),
            ],
            [
                'id_prasarana'      => 1, // 1 = Ruang Rapat Utama
                'id_kategori'       => 3, // 3 = Mebel
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_sarana'       => 'Kursi Rapat',
                'kode_sarana'       => 'FT-INV-KRS-001',
                'jumlah'            => 50,
                'spesifikasi'       => json_encode(['Warna' => 'Hitam', 'Bahan' => 'Jaring']),
                'deskripsi'         => 'Kursi untuk peserta rapat.',
                'kondisi'           => 'Baik',
                'status_ketersediaan' => 'Tersedia', // Status ini akan dikontrol oleh peminjaman ruangan
                'created_at'        => Time::now(),
            ],
            // data dengan id_prasarana NULL
            [
                'id_prasarana'      => NULL,
                'id_kategori'       => 4, // 4 = Alat Tulis Kantor
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_sarana'       => 'Spidol Hitam',
                'kode_sarana'       => 'FT-INV-SP-001',
                'jumlah'            => 3,
                'spesifikasi'       => json_encode(['Warna' => 'Hitam', 'Bahan' => 'Plastik']),
                'deskripsi'         => 'Spidol hitam untuk menulis di papan tulis',
                'kondisi'           => 'Baik',
                'status_ketersediaan' => 'Dipinjam', // Status ini akan dikontrol oleh peminjaman ruangan
                'created_at'        => Time::now(),
            ],
        ];

        $this->db->table('sarana')->insertBatch($data);
    }
}
