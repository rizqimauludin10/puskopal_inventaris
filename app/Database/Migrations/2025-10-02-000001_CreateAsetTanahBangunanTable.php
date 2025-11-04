<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsetTanahBangunanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'luas_tanah' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'luas_bangunan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'kepemilikan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'berlaku' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('aset_tanah_bangunan');
    }

    public function down()
    {
        $this->forge->dropTable('aset_tanah_bangunan');
    }
}