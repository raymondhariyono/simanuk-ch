<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaporanKerusakanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_laporan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_peminjam' => [ // Pelapor
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipe_aset' => [ // Pembeda: 'Sarana' atau 'Prasarana'
                'type'       => 'ENUM',
                'constraint' => ['Sarana', 'Prasarana'],
                'default'    => 'Sarana',
            ],
            'id_sarana' => [ // Nullable (Diisi jika tipe=Sarana)
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_prasarana' => [ // Nullable (Diisi jika tipe=Prasarana)
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'judul_laporan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'deskripsi_kerusakan' => [
                'type' => 'TEXT',
            ],
            'bukti_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'status_laporan' => [
                'type'       => 'ENUM',
                'constraint' => ['Diajukan', 'Diproses', 'Selesai', 'Ditolak'],
                'default'    => 'Diajukan',
            ],
            'tindak_lanjut' => [ // Catatan admin (misal: "Barang diganti baru")
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id_laporan', true);

        // Foreign Keys (Opsional: CASCADE atau SET NULL tergantung kebutuhan)
        $this->forge->addForeignKey('id_peminjam', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_sarana', 'sarana', 'id_sarana', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_prasarana', 'prasarana', 'id_prasarana', 'CASCADE', 'CASCADE');

        $this->forge->createTable('laporan_kerusakan');
    }

    public function down()
    {
        $this->forge->dropTable('laporan_kerusakan');
    }
}
