<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAsetKendaraanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'jenis_kendaraan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'merk' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'no_rangka' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'no_mesin' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'tahun' => [
                'type'       => 'YEAR',
            ],
            'nopol' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'bpkb' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'kondisi' => [
                'type'       => 'ENUM',
                'constraint' => ['Baik', 'Rusak Ringan', 'Rusak Berat'],
                'default'    => 'Baik',
            ],
            'pajak' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('aset_kendaraan');
    }

    public function down()
    {
        $this->forge->dropTable('aset_kendaraan');
    }
}