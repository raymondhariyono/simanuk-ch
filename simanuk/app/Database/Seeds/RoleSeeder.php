<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_role' => 'Admin', 'created_at' => Time::now()],
            ['nama_role' => 'TU', 'created_at' => Time::now()],
            ['nama_role' => 'Peminjam', 'created_at' => Time::now()],
            ['nama_role' => 'Pimpinan', 'created_at' => Time::now()],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}