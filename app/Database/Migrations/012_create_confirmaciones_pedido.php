<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfirmacionesPedido extends Migration
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
            'propietario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'estado' => [
                'type' => "ENUM('pendiente','confirmado','rechazado','expirado')",
                'null' => false,
                'default' => 'pendiente',
            ],
            'fecha_expiracion' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'fecha_respuesta' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'motivo_rechazo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => 'current_timestamp()',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('propietario_id', 'usuarios', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->addUniqueKey(['token'], 'confirmaciones_pedido_token_uniq');

        $this->forge->createTable('confirmaciones_pedido', true);
    }

    public function down()
    {
        $this->forge->dropTable('confirmaciones_pedido', true);
    }
}

