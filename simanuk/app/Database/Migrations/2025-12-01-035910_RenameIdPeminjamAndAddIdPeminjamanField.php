<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameIdPeminjamAndAddIdPeminjamanField extends Migration
{
    public function up()
    {
        // Rename kolom id_peminjam menjadi id_pelapor
        $this->forge->modifyColumn('laporan_kerusakan', [
            'id_peminjam' => [
                'name' => 'id_pelapor',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        // Ubah kolom id_peminjaman agar boleh NULL
        $this->forge->addColumn('laporan_kerusakan', [
            'id_peminjaman' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'after' => 'id_pelapor',
                'null' => true, // KUNCI UTAMA DISINI
            ],
        ]);
    }

    public function down()
    {
        // Kembalikan nama kolom jika rollback
        $this->forge->modifyColumn('laporan_kerusakan', [
            'id_pelapor' => [
                'name' => 'id_peminjam',
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->dropColumn('laporan_kerusakan', 'id_peminjaman');
    }
}
