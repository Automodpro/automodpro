<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReglasValidacionSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('reglas_validacion')->insertBatch([
            ['slug_modificacion' => 'polarizado_frontal', 'tipo_regla' => 'minimo', 'valor_regla' => '70', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Polarizado frontal: mínimo 70% transmisión de luz', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'polarizado_frontal', 'tipo_regla' => 'maximo', 'valor_regla' => '69', 'estado_legal' => 'ilegal', 'severidad' => 'moderada', 'descripcion' => 'Polarizado frontal NO puede ser menor al 70%', 'base_legal' => 'Resolución 3754/2016 Art.5', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'polarizado_trasero', 'tipo_regla' => 'minimo', 'valor_regla' => '50', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Polarizado trasero: mínimo 50% transmisión de luz', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'polarizado_trasero', 'tipo_regla' => 'maximo', 'valor_regla' => '49', 'estado_legal' => 'ilegal', 'severidad' => 'moderada', 'descripcion' => 'Polarizado trasero NO puede ser menor al 50%', 'base_legal' => 'Resolución 3754/2016 Art.5', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'polarizado_parabrisas', 'tipo_regla' => 'igual', 'valor_regla' => '100', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Parabrisas NO puede tener polarizado', 'base_legal' => 'Código Nacional Tránsito Art.83', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'polarizado_parabrisas', 'tipo_regla' => 'maximo', 'valor_regla' => '99', 'estado_legal' => 'ilegal', 'severidad' => 'grave', 'descripcion' => 'Parabrisas NO puede tener polarizado', 'base_legal' => 'Código Nacional Tránsito Art.83', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'altura_suspension', 'tipo_regla' => 'minimo', 'valor_regla' => '12', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Altura mínima: 12 cm del suelo', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $300,000 + inmovilización'],
            ['slug_modificacion' => 'altura_suspension', 'tipo_regla' => 'maximo', 'valor_regla' => '11', 'estado_legal' => 'ilegal', 'severidad' => 'grave', 'descripcion' => 'Suspensión NO puede ser menor a 12 cm', 'base_legal' => 'Resolución 3754/2016 Art.8', 'sancion' => 'Multa $300,000 + inmovilización'],
            ['slug_modificacion' => 'altura_suspension', 'tipo_regla' => 'maximo', 'valor_regla' => '30', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Altura máxima: 30 cm', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'altura_suspension', 'tipo_regla' => 'minimo', 'valor_regla' => '31', 'estado_legal' => 'ilegal', 'severidad' => 'grave', 'descripcion' => 'Suspensión NO puede exceder 30 cm', 'base_legal' => 'Resolución 3754/2016 Art.8', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'tamano_rines', 'tipo_regla' => 'maximo', 'valor_regla' => '20', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Tamaño máximo rines: 20 pulgadas', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $150,000'],
            ['slug_modificacion' => 'tamano_rines', 'tipo_regla' => 'minimo', 'valor_regla' => '21', 'estado_legal' => 'ilegal', 'severidad' => 'moderada', 'descripcion' => 'Rines >20" requieren permiso especial', 'base_legal' => 'Resolución 3754/2016 Art.6', 'sancion' => 'Multa $150,000'],
            ['slug_modificacion' => 'kit_aerodinamico', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'condicional', 'severidad' => 'leve', 'descripcion' => 'Kit aerodinámico requiere revisión técnica', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'aleron_trasero', 'tipo_regla' => 'lista', 'valor_regla' => 'ninguno:pequeno:medio:grande', 'estado_legal' => 'condicional', 'severidad' => 'leve', 'descripcion' => 'Alerones que excedan 15 cm requieren permiso', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'sistema_escape', 'tipo_regla' => 'lista', 'valor_regla' => 'estandar:deportivo:libre', 'estado_legal' => 'condicional', 'severidad' => 'moderada', 'descripcion' => 'Escape libre prohibido en vía pública', 'base_legal' => 'Código Tránsito Art.82', 'sancion' => 'Multa $450,000'],
            ['slug_modificacion' => 'silenciador_deportivo', 'tipo_regla' => 'booleano', 'valor_regla' => 'false', 'estado_legal' => 'legal', 'severidad' => 'leve', 'descripcion' => 'Silenciador deportivo permitido si cumple 80dB', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'silenciador_deportivo', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'condicional', 'severidad' => 'moderada', 'descripcion' => 'Requiere certificación de ruido', 'base_legal' => 'Resolución 3754/2016 Art.9', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'luces_neon', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'condicional', 'severidad' => 'leve', 'descripcion' => 'Luces neón solo blanco o ámbar', 'base_legal' => 'Código Tránsito Art.81', 'sancion' => 'Multa $300,000'],
            ['slug_modificacion' => 'faros_led', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'condicional', 'severidad' => 'leve', 'descripcion' => 'Faros LED requieren alineación certificada', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $150,000'],
            ['slug_modificacion' => 'turbo', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'condicional', 'severidad' => 'grave', 'descripcion' => 'Turbo requiere certificación de emisiones', 'base_legal' => 'Resolución 3754/2016 Art.10', 'sancion' => 'Multa $600,000'],
            ['slug_modificacion' => 'ecu_remap', 'tipo_regla' => 'booleano', 'valor_regla' => 'true', 'estado_legal' => 'ilegal', 'severidad' => 'grave', 'descripcion' => 'ECU Remap no certificado PROHIBIDO', 'base_legal' => 'Resolución 3754/2016', 'sancion' => 'Multa $600,000'],
        ]);
    }
}
