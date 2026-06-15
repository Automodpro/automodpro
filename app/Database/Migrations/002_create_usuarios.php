<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nombre_usuario' => ['type' => 'VARCHAR', 'constraint' => 60, 'unique' => true],
            'correo' => ['type' => 'VARCHAR', 'constraint' => 120, 'unique' => true],
            'contrasena' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nombre_completo' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'telefono' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'avatar_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'rol_id' => ['type' => 'INT', 'constraint' => 11, 'default' => 4],
            'activo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'ultimo_acceso' => ['type' => 'TIMESTAMP', 'null' => true],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('rol_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
