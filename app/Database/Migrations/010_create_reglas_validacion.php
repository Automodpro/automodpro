<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReglasValidacion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'slug_modificacion' => ['type' => 'VARCHAR', 'constraint' => 50],
            'tipo_regla' => ['type' => 'ENUM', 'constraint' => ['minimo','maximo','igual','booleano','lista']],
            'valor_regla' => ['type' => 'VARCHAR', 'constraint' => 100],
            'estado_legal' => ['type' => 'ENUM', 'constraint' => ['legal','ilegal','condicional']],
            'severidad' => ['type' => 'ENUM', 'constraint' => ['leve','moderada','grave'], 'default' => 'leve'],
            'descripcion' => ['type' => 'TEXT'],
            'base_legal' => ['type' => 'TEXT', 'null' => true],
            'sancion' => ['type' => 'TEXT', 'null' => true],
            'creado_en' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('reglas_validacion');
    }

    public function down()
    {
        $this->forge->dropTable('reglas_validacion');
    }
}
