<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KategoriSeederBaru extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kategori' => 'Nonruangan', // id = 1
                'created_at'    => Time::now(),
            ],
        ];

        $this->db->table('kategori')->insertBatch($data);
    }
}
