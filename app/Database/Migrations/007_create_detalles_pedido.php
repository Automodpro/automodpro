<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetallesPedido extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'pedido_id' => ['type' => 'INT', 'constraint' => 11],
            'servicio_id' => ['type' => 'INT', 'constraint' => 11],
            'cantidad' => ['type' => 'INT', 'default' => 1],
            'precio_unitario' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'observaciones' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('servicio_id', 'servicios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detalles_pedido');
    }

    public function down()
    {
        $this->forge->dropTable('detalles_pedido');
    }
}
