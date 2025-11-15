<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSaranaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_sarana' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_prasarana' => [ // boleh NULL
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'id_lokasi' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nama_sarana' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kode_sarana' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'jumlah' => [
                'type' => 'INT',
            ],
            'spesifikasi' => [ // Tipe JSON
                'type' => 'JSON',
                'null' => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kondisi' => [ // ENUM dengan VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'comment'    => 'Baik, Rusak Ringan, Rusak Berat',
            ],
            'status_ketersediaan' => [ // ENUM dengan VARCHAR
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'Tersedia, Dipinjam, Perawatan, Tidak Tersedia',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id_sarana', true);
        $this->forge->addUniqueKey('kode_sarana');

        // Foreign Keys
        $this->forge->addForeignKey('id_prasarana', 'prasarana', 'id_prasarana', 'SET NULL', 'NO ACTION'); // SET NULL jika prasarana dihapus
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id_lokasi', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('sarana');
    }

    public function down()
    {
        $this->forge->dropTable('sarana');
    }
}
