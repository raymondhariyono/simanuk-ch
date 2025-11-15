<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrasaranaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_prasarana' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_kategori' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'id_lokasi' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'nama_prasarana' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kode_prasarana' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'luas_ruangan' => [ // INT
                'type'       => 'INT',
                'null'       => true,
            ],
            'kapasitas_orang' => [ // INT
                'type'       => 'INT',
                'null'       => true,
            ],
            'jenis_ruangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'fasilitas' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'lantai' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'tata_letak' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_ketersediaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'Tersedia, Dipinjam, Renovasi, Tidak Tersedia',
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

        $this->forge->addKey('id_prasarana', true);
        $this->forge->addUniqueKey('kode_prasarana'); // seperti slug

        // foreign keys
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_lokasi', 'lokasi', 'id_lokasi', 'CASCADE', 'NO ACTION');

        $this->forge->createTable('prasarana');
    }

    public function down()
    {
        $this->forge->dropTable('prasarana');
    }
}
