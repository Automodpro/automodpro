<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nombre' => ['type' => 'VARCHAR', 'constraint' => 100],
            'descripcion' => ['type' => 'TEXT', 'null' => true],
            'precio' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'categoria_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'duracion_estimada' => ['type' => 'INT', 'default' => 60],
            'requiere_aprobacion' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'activo' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('categoria_id', 'categorias_servicio', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('servicios');
    }

    public function down()
    {
        $this->forge->dropTable('servicios');
    }
}
