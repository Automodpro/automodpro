<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHistorialEstadosPedido extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pedido_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'estado_anterior' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
                'null' => true,
            ],
            'estado_nuevo' => [
                'type' => 'VARCHAR',
                'constraint' => 60,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'comentario' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fecha_cambio' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->addUniqueKey(['pedido_id', 'estado_nuevo', 'fecha_cambio'], 'historial_estados_pedido_uniq');

        $this->forge->createTable('historial_estados_pedido', true);
    }

    public function down()
    {
        $this->forge->dropTable('historial_estados_pedido', true);
    }
}

