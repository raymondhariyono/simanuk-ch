<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Entities\User;
use App\Models\ExtendedUserModel; // gunakan model baru

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = new ExtendedUserModel();

        $data = [
            [
                'email'         => 'admin@sarpras.test',
                'username'      => 'admin',
                'password'      => 'admin123',
                'nama_lengkap'  => 'Admin Sarpras',
                'organisasi'    => 'Bagian Umum',
                'kontak'        => '081234567890',
                'id_role'       => 1,
                'active'        => 1,
                'created_at'    => Time::now(),
            ],
            [
                'email'         => 'tu@sarpras.test',
                'username'      => 'tu',
                'password'      => 'tu123',
                'nama_lengkap'  => 'Petugas Tata Usaha',
                'organisasi'    => 'Tata Usaha FTI',
                'kontak'        => '081298765432',
                'id_role'       => 2,
                'active'        => 1,
                'created_at'    => Time::now(),
            ],
            [
                'email'         => 'ukm@sarpras.test',
                'username'      => 'ukm',
                'password'      => 'ukm123',
                'nama_lengkap'  => 'Ketua UKM Musik',
                'organisasi'    => 'UKM Musik Kampus',
                'kontak'        => '082112345678',
                'id_role'       => 3,
                'active'        => 1,
                'created_at'    => Time::now(),
            ],
            [
                'email'         => 'pimpinan@sarpras.test',
                'username'      => 'pimpinan',
                'password'      => 'pimpinan123',
                'nama_lengkap'  => 'Dekan FTI',
                'organisasi'    => 'Fakultas Teknologi Informasi',
                'kontak'        => '085211223344',
                'id_role'       => 4,
                'active'        => 1,
                'created_at'    => Time::now(),
            ],
        ];

        foreach ($data as $userData) {
            $user = new User($userData);
            $users->save($user);
        }
    }
}
