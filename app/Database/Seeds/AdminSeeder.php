<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('usuarios')->insert([
            'nombre_usuario' => 'admin',
            'correo' => 'admin@automod.pro',
            'contrasena' => password_hash('Admin123!', PASSWORD_DEFAULT),
            'nombre_completo' => 'Administrador del Sistema',
            'rol_id' => 1, // Administrador
            'activo' => 1,
        ]);
    }
}
