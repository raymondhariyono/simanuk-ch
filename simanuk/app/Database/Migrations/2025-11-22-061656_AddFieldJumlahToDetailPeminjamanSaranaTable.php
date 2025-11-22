<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldJumlahToDetailPeminjamanSaranaTable extends Migration
{
    public function up()
    {
        $fields = ['jumlah' => ['type' => 'INT', 'unsigned' => true, 'default' => 1],];
        $this->forge->addColumn('detail_peminjaman_sarana', $fields);
    }
    public function down()
    {
        $this->forge->dropColumn('detail_peminjaman_sarana', 'jumlah');
    }
}
