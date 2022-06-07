<?php
 
namespace App\Database\Migrations;
 
use CodeIgniter\Database\Migration;
 
class Product extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 100,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name'       => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'price' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'photo' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ]
            ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('product');
    }
 
    public function down()
    {
        $this->forge->dropTable('product');
    }
}