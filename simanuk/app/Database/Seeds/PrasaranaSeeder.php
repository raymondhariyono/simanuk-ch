<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PrasaranaSeeder extends Seeder
{
    public function run()
    {
        // data mengambil / mengasumsikan ID dari LokasiSeeder dan KategoriSeeder
        $data = [
            [
                'id_kategori'       => 1, // 1 = Ruangan
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_prasarana'    => 'Ruang Rapat Utama',
                'kode_prasarana'    => 'FT-RRU-001',
                'luas_ruangan'      => 100,
                'kapasitas_orang'   => 50,
                'jenis_ruangan'     => 'Ruang Rapat',
                'fasilitas'         => json_encode(['AC', 'Proyektor Terpasang', 'Papan Tulis']),
                'lantai'            => 2,
                'status_ketersediaan' => 'Tersedia',
                'created_at'        => Time::now(),
            ],
            [
                'id_kategori'       => 1, // 1 = Ruangan
                'id_lokasi'         => 1, // 1 = Gedung Fakultas Teknik
                'nama_prasarana'    => 'Laboratorium Komputer Dasar',
                'kode_prasarana'    => 'FT-LAB-KMD',
                'luas_ruangan'      => 120,
                'kapasitas_orang'   => 40,
                'jenis_ruangan'     => 'Laboratorium',
                'fasilitas'         => json_encode(['AC', '40 Unit PC', 'Papan Tulis']),
                'lantai'            => 3,
                'status_ketersediaan' => 'Tersedia',
                'created_at'        => Time::now(),
            ],
        ];

        $this->db->table('prasarana')->insertBatch($data);
    }
}
