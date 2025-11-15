<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePeminjamanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_peminjaman' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_peminjam' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'id_admin_verifikator' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_tu_approver' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'kegiatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tgl_pengajuan' => [
                'type'    => 'DATETIME',
            ],
            'tgl_pinjam_dimulai' => [
                'type'    => 'DATETIME',
            ],
            'tgl_pinjam_selesai' => [
                'type'    => 'DATETIME',
            ],
            'durasi' => [
                'type' => 'INT',
                'null' => true,
            ],
            'status_verifikasi' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Pending',
                'comment'    => 'Pending, Disetujui, Ditolak',
            ],
            'status_persetujuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Pending',
                'comment'    => 'Pending, Disetujui, Ditolak',
            ],
            'status_peminjaman_global' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Diajukan',
                'comment'    => 'Diajukan, Disetujui, Dipinjam, Selesai, Dibatalkan',
            ],
            'tipe_peminjaman' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Peminjaman',
                'comment'    => 'Peminjaman, Perpanjangan',
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tgl_perpanjangan_dimulai' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'tgl_perpanjangan_selesai' => [
                'type'    => 'DATETIME',
                'null'    => true,
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

        $this->forge->addKey('id_peminjaman', true);

        // Setup Foreign Keys
        // Pastikan ini merujuk ke tabel 'users' (PK 'id') dari Shield,
        // sesuai ERD "Users { id int [pk, increment] ... }" Anda.
        $this->forge->addForeignKey('id_peminjam', 'users', 'id', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_admin_verifikator', 'users', 'id', 'SET NULL', 'NO ACTION');
        $this->forge->addForeignKey('id_tu_approver', 'users', 'id', 'SET NULL', 'NO ACTION');

        $this->forge->createTable('peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('peminjaman');
    }
}
