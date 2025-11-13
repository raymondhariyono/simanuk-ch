<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Entities\User; // Pastikan menggunakan Entitas kustom Anda
use App\Models\ExtendedUserModel;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Dapatkan User Provider (ExtendedUserModel) dari service auth
        // $userModel = new ExtendedUserModel();
        $userModel = auth()->getProvider();

        // 2. Siapkan data. Hapus 'group', 'active', dan timestamps.
        //    Pastikan 'id_role' sesuai dengan 'RoleSeeder' Anda.
        $users = [
            [
                'id_role'       => 1, // Asumsi 1 = admin
                'username'      => 'admin_satu',
                'email'         => 'admin@sarpras.test',
                'password'      => 'admin123',
                'nama_lengkap'  => 'Administrator Sistem Ujang',
                'organisasi'    => 'Himpunan Mahasiswa Teknologi Informasi',
                'kontak'        => '081234567890',
            ],
            [
                'email'         => 'tu@sarpras.test',
                'username'      => 'tu',
                'password'      => 'tu123',
                'id_role'       => 2, // Asumsi 2 = tu
                'nama_lengkap'  => 'Adi',
                'organisasi'    => 'Tata Usaha',
                'kontak'        => '089876543210',
            ],
            [
                'email'         => 'ukm@sarpras.test',
                'username'      => 'ukm_musik',
                'password'      => 'ukm123',
                'id_role'       =>  3, // Asumsi 3 = peminjam
                'nama_lengkap'  => 'Rudy Sanjaya',
                'organisasi'    => 'UKM Musik',
                'kontak'        => '087777777777',
            ],
            [
                'email'         => 'pimpinan@sarpras.test',
                'username'      => 'pimpinan1',
                'password'      => 'pimpinan123',
                'id_role'       =>  4, // Asumsi 4 = pimpinan
                'nama_lengkap'  => 'Surya',
                'organisasi'    => 'Dekan Fakultas Teknik',
                'kontak'        => '087878787878',
            ]
        ];

        foreach ($users as $userData) {
            // $user = new User($userData);
            $userModel->save($userData);
        }

        echo "✅ Seeding users berhasil!\n";
    }
}
