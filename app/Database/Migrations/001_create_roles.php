<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 60, 'unique' => true],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'nivel' => ['type' => 'INT', 'default' => 0],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
