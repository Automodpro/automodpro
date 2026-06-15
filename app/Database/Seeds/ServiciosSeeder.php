<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ServiciosSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('servicios')->insertBatch([
            ['nombre' => 'Kit Carrocería Deportivo', 'descripcion' => 'Instalación de kit aerodinámico completo', 'precio' => 2500000, 'categoria_id' => 1, 'duracion_estimada' => 240, 'requiere_aprobacion' => 1],
            ['nombre' => 'Polarizado Profesional', 'descripcion' => 'Polarizado de vidrios con lámina certificada', 'precio' => 450000, 'categoria_id' => 1, 'duracion_estimada' => 90],
            ['nombre' => 'Instalación Turbo', 'descripcion' => 'Kit turbo compresor con intercooler', 'precio' => 5500000, 'categoria_id' => 2, 'duracion_estimada' => 480, 'requiere_aprobacion' => 1],
            ['nombre' => 'Sistema Escape Deportivo', 'descripcion' => 'Escape completo en acero inoxidable', 'precio' => 1800000, 'categoria_id' => 3, 'duracion_estimada' => 120, 'requiere_aprobacion' => 1],
            ['nombre' => 'Suspensión Deportiva', 'descripcion' => 'Suspensión ajustable de alto rendimiento', 'precio' => 3500000, 'categoria_id' => 4, 'duracion_estimada' => 180, 'requiere_aprobacion' => 1],
            ['nombre' => 'Faros LED', 'descripcion' => 'Conversión a iluminación LED completa', 'precio' => 800000, 'categoria_id' => 5, 'duracion_estimada' => 60],
            ['nombre' => 'ECU Remap', 'descripcion' => 'Remapeo de centralita electrónica', 'precio' => 1500000, 'categoria_id' => 5, 'duracion_estimada' => 120, 'requiere_aprobacion' => 1],
            ['nombre' => 'Cambio de Rines', 'descripcion' => 'Montaje y balanceo de rines personalizados', 'precio' => 200000, 'categoria_id' => 1, 'duracion_estimada' => 60],
            ['nombre' => 'Alerón Deportivo', 'descripcion' => 'Instalación de alerón trasero', 'precio' => 800000, 'categoria_id' => 6, 'duracion_estimada' => 90, 'requiere_aprobacion' => 1],
        ]);
    }
}
