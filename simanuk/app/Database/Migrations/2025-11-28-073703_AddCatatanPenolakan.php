<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatatanPenolakan extends Migration
{
    public function up()
    {
        $fields = [
            'catatan_penolakan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'catatan' // Sesuaikan posisi
            ]
        ];
        $this->forge->addColumn('detail_peminjaman_sarana', $fields);
        $this->forge->addColumn('detail_peminjaman_prasarana', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('detail_peminjaman_sarana', 'catatan_penolakan');
        $this->forge->dropColumn('detail_peminjaman_prasarana', 'catatan_penolakan');
    }
}
