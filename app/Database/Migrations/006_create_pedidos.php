<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePedidos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'usuario_id' => ['type' => 'INT', 'constraint' => 11],
            'vehiculo_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'estado' => ['type' => 'ENUM', 'constraint' => ['pendiente','aprobado','en_proceso','completado','cancelado'], 'default' => 'pendiente'],
            'total' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'notas' => ['type' => 'TEXT', 'null' => true],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
            'actualizado_en' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('vehiculo_id', 'vehiculos', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('pedidos');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos');
    }
}
