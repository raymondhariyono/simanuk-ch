<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Entities\User;
use App\Models\ExtendedUserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = new ExtendedUserModel();

        $data = [
            [
                'email'         => 'admin1@sarpras.test',
                'username'      => 'admin_satu',
                'password'      => 'admin123',
                'id_role'       => 1, // pastikan role ID 1 ada di tabel roles
                'nama_lengkap'  => 'Administrator Sistem',
                'organisasi'    => 'Himpunan Mahasiswa Teknologi Informasi',
                'kontak'        => '081234567890',
                'active'        => 1,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'email'         => 'admin2@sarpras.test',
                'username'      => 'admin_dua',
                'password'      => 'admin223',
                'id_role'       => 1, // pastikan role ID 1 ada di tabel roles
                'nama_lengkap'  => 'Administrator Sistem',
                'organisasi'    => 'Himpunan Mahasiswa Teknologi Informasi',
                'kontak'        => '081234567890',
                'active'        => 1,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'email'         => 'tu@sarpras.test',
                'username'      => 'tu',
                'password'      => 'tu123',
                'id_role'       => 2, // pastikan role ID 2 ada di tabel roles
                'nama_lengkap'  => 'Adi',
                'organisasi'    => 'Tata Usaha',
                'kontak'        => '089876543210',
                'active'        => 1,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'email'         => 'ukm@sarpras.test',
                'username'      => 'ukm_musik',
                'password'      => 'ukm123',
                'id_role'       => 3,
                'nama_lengkap'  => 'Rudy Sanjaya',
                'organisasi'    => 'UKM Musik',
                'kontak'        => '087777777777',
                'active'        => 1,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
            [
                'email'         => 'pimpinan@sarpras.test',
                'username'      => 'pimpinan1',
                'password'      => 'pimpinan123',
                'id_role'       => 4,
                'nama_lengkap'  => 'Surya',
                'organisasi'    => 'Dekan Fakultas Teknik',
                'kontak'        => '087878787878',
                'active'        => 1,
                'created_at'    => Time::now(),
                'updated_at'    => Time::now(),
            ],
        ];

        foreach ($data as $userData) {
            $user = new User($userData);
            $users->save($user);
        }
    }
}
