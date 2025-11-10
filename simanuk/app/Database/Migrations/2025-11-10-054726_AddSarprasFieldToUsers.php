<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSarprasFieldsToUsers extends Migration
{
    public function up()
    {
        // Ini adalah kolom-kolom dari ERD Anda
        $fields = [
            'id_role' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false, // buat false agar sesuai ERD
                'after'      => 'id', // tambahkan setelah kolom 'id' bawaan Shield
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'after'      => 'id_role',
            ],
            'organisasi' => [
                'type'       => 'TEXT',
                'null'       => true, // Di ERD Anda tidak ada 'not null'
                'after'      => 'nama_lengkap',
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'organisasi',
            ],
        ];

        $this->forge->addColumn('users', $fields);

        // Tambahkan Foreign Key SETELAH kolom dibuat
        // Pastikan 'roles' (dari CreateRoleTable) sudah ada
        $this->forge->addForeignKey('id_role', 'roles', 'id_role', 'CASCADE', 'CASCADE', 'users_id_role_foreign');

        // Update tabel agar Foreign Key ter-apply
        $this->db->query('ALTER TABLE `users` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)');
    }

    public function down()
    {
        // Hapus Foreign Key dulu
        $this->forge->dropForeignKey('users', 'users_id_role_foreign');

        // Hapus kolom-kolom
        $this->forge->dropColumn('users', [
            'id_role',
            'nama_lengkap',
            'organisasi',
            'kontak',
        ]);
    }
}
