<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthProviders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11],
            'provider' => ['type' => 'VARCHAR', 'constraint' => 50],
            'provider_id' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['provider', 'provider_id']);
        $this->forge->addForeignKey('user_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_providers');
    }

    public function down()
    {
        $this->forge->dropTable('auth_providers');
    }
}
