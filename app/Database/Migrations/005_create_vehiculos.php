<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehiculos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'usuario_id' => ['type' => 'INT', 'constraint' => 11],
            'placa' => ['type' => 'VARCHAR', 'constraint' => 10, 'unique' => true],
            'marca' => ['type' => 'VARCHAR', 'constraint' => 60],
            'modelo' => ['type' => 'VARCHAR', 'constraint' => 60],
            'año' => ['type' => 'INT', 'constraint' => 11],
            'color' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'tipo' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'sedan'],
            'vin' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'kilometraje' => ['type' => 'INT', 'default' => 0],
            'observaciones' => ['type' => 'TEXT', 'null' => true],
            'activo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('vehiculos');
    }

    public function down()
    {
        $this->forge->dropTable('vehiculos');
    }
}
