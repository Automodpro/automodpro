<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('categorias_servicio')->insertBatch([
            ['nombre' => 'Personalización', 'slug' => 'personalizacion', 'descripcion' => 'Modificaciones estéticas y visuales'],
            ['nombre' => 'Mecánica General', 'slug' => 'mecanica', 'descripcion' => 'Servicios de mantenimiento mecánico'],
            ['nombre' => 'Escape y Emisiones', 'slug' => 'escape', 'descripcion' => 'Sistemas de escape y control de emisiones'],
            ['nombre' => 'Suspensión', 'slug' => 'suspension', 'descripcion' => 'Sistemas de suspensión y dirección'],
            ['nombre' => 'Eléctrico', 'slug' => 'electrico', 'descripcion' => 'Sistemas eléctricos y electrónicos'],
            ['nombre' => 'Carrocería y Pintura', 'slug' => 'carroceria', 'descripcion' => 'Trabajos de carrocería y pintura'],
        ]);
    }
}
