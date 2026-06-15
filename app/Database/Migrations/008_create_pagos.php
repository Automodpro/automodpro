<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePagos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'pedido_id' => ['type' => 'INT', 'constraint' => 11],
            'monto' => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'metodo_pago' => ['type' => 'ENUM', 'constraint' => ['efectivo','tarjeta','transferencia','otros'], 'default' => 'efectivo'],
            'estado' => ['type' => 'ENUM', 'constraint' => ['pendiente','pagado','reembolsado','fallido'], 'default' => 'pendiente'],
            'referencia' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'fecha_pago' => ['type' => 'TIMESTAMP', 'null' => true],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pagos');
    }

    public function down()
    {
        $this->forge->dropTable('pagos');
    }
}
