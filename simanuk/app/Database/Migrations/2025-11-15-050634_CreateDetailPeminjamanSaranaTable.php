<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPeminjamanSaranaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail_sarana' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_peminjaman' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'id_sarana' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'foto_sebelum' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'foto_sesudah' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'kondisi_awal' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'comment' => 'Baik', 'Rusak Ringan', 'Rusak Berat'],
            'kondisi_akhir' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true, 'comment' => 'Baik', 'Rusak Ringan', 'Rusak Berat'],
            'catatan' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id_detail_sarana', true);
        $this->forge->addForeignKey('id_peminjaman', 'peminjaman', 'id_peminjaman', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_sarana', 'sarana', 'id_sarana', 'CASCADE', 'CASCADE');

        $this->forge->createTable('detail_peminjaman_sarana');
    }

    public function down()
    {
        $this->forge->dropTable('detail_peminjaman_sarana');
    }
}
