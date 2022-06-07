<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProduct extends Migration
{
    public function up()
    {
        $this->forge->addColumn('product', [
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 100,
                'after'          => 'id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('product', 'id_user');
    }
}