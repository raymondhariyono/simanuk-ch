<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFotoAsetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_foto' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_sarana' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // boleh NULL
            ],
            'id_prasarana' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // boleh NULL
            ],
            'url_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'deskripsi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id_foto', true);

        // Foreign Keys
        $this->forge->addForeignKey('id_sarana', 'sarana', 'id_sarana', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_prasarana', 'prasarana', 'id_prasarana', 'CASCADE', 'CASCADE');

        $this->forge->createTable('foto_aset');
    }

    public function down()
    {
        $this->forge->dropTable('foto_aset');
    }
}
