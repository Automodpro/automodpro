<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfiguraciones extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'clave' => ['type' => 'VARCHAR', 'constraint' => 80, 'unique' => true],
            'valor' => ['type' => 'TEXT'],
            'tipo' => ['type' => 'ENUM', 'constraint' => ['texto','numero','booleano','json'], 'default' => 'texto'],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'grupo' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'general'],
            'actualizado_en' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('configuraciones');
    }

    public function down()
    {
        $this->forge->dropTable('configuraciones');
    }
}
