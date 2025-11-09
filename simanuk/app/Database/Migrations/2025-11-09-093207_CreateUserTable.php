<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoleIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'id_role' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
                'after' => 'id', // 'id', bukan 'id_user' karena menggunakan ci4/shield
            ],

            // kolom penyesuaian di ERD
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'after' => 'username',
            ],
            'organisasi' => [
                'type'       => 'TEXT',
                'after' => 'nama_lengkap',
            ],
            'kontak' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after' => 'organisasi',
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'id_role');
    }
}
