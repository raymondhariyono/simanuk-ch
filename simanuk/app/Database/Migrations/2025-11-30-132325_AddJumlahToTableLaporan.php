<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJumlahToTableLaporan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('laporan_kerusakan', [
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1, // Default 1 untuk Prasarana
                'after'      => 'id_prasarana'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('laporan_kerusakan', 'jumlah');
    }
}
