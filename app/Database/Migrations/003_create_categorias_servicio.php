<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriasServicio extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 80],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('categorias_servicio');
    }

    public function down()
    {
        $this->forge->dropTable('categorias_servicio');
    }
}
