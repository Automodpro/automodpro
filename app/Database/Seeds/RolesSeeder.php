<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('roles')->insertBatch([
            ['nombre' => 'Administrador', 'slug' => 'admin', 'descripcion' => 'Gestión completa de operaciones', 'nivel' => 80],
            ['nombre' => 'Mecánico', 'slug' => 'mecanico', 'descripcion' => 'Gestión de servicios y vehículos', 'nivel' => 50],
            ['nombre' => 'Usuario', 'slug' => 'usuario', 'descripcion' => 'Acceso básico a personalización', 'nivel' => 10],
        ]);
    }
}
