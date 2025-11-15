<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LokasiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_lokasi' => 'Gedung Fakultas Teknik',
                'alamat'      => 'Jl. Jenderal A. Yani Km. 36, Banjarbaru',
                'created_at'  => Time::now(),
            ],
            [
                'nama_lokasi' => 'Gedung Dekanat',
                'alamat'      => 'Gedung Utama FT, Lantai 1',
                'created_at'  => Time::now(),
            ],
            [
                'nama_lokasi' => 'Gedung Jurusan T. Sipil',
                'alamat'      => 'Gedung B FT',
                'created_at'  => Time::now(),
            ],
        ];

        // Masukkan data batch
        $this->db->table('lokasi')->insertBatch($data);
    }
}
