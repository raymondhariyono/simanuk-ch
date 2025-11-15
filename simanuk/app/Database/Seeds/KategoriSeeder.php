<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kategori' => 'Ruangan', // id = 1
                'created_at'    => Time::now(),
            ],
            [
                'nama_kategori' => 'Elektronik', // id = 2
                'created_at'    => Time::now(),
            ],
            [
                'nama_kategori' => 'Mebel', // id = 3
                'created_at'    => Time::now(),
            ],
            [
                'nama_kategori' => 'Alat Tulis Kantor', // id = 4
                'created_at'    => Time::now(),
            ],
        ];

        $this->db->table('kategori')->insertBatch($data);
    }
}
