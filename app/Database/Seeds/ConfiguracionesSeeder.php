<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ConfiguracionesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('configuraciones')->insertBatch([
            ['clave' => 'nombre_sistema', 'valor' => 'AutoMod Pro', 'tipo' => 'texto', 'descripcion' => 'Nombre del sistema', 'grupo' => 'general'],
            ['clave' => 'empresa_nombre', 'valor' => 'AutoMod Pro SAS', 'tipo' => 'texto', 'descripcion' => 'Nombre de la empresa', 'grupo' => 'general'],
            ['clave' => 'empresa_nit', 'valor' => '901.123.456-7', 'tipo' => 'texto', 'descripcion' => 'NIT de la empresa', 'grupo' => 'general'],
            ['clave' => 'empresa_telefono', 'valor' => '+57 300 123 4567', 'tipo' => 'texto', 'descripcion' => 'Teléfono de contacto', 'grupo' => 'general'],
            ['clave' => 'empresa_direccion', 'valor' => 'Cra 45 # 23-12, Bogotá', 'tipo' => 'texto', 'descripcion' => 'Dirección', 'grupo' => 'general'],
            ['clave' => 'iva_porcentaje', 'valor' => '19', 'tipo' => 'numero', 'descripcion' => 'Porcentaje de IVA aplicado', 'grupo' => 'facturacion'],
            ['clave' => 'moneda_simbolo', 'valor' => '$', 'tipo' => 'texto', 'descripcion' => 'Símbolo de moneda', 'grupo' => 'facturacion'],
            ['clave' => 'notificaciones_email', 'valor' => 'true', 'tipo' => 'booleano', 'descripcion' => 'Enviar notificaciones por correo', 'grupo' => 'notificaciones'],
            ['clave' => 'limite_vehiculos_usuario', 'valor' => '5', 'tipo' => 'numero', 'descripcion' => 'Máximo de vehículos por usuario', 'grupo' => 'limites'],
            ['clave' => 'mantenimiento_activo', 'valor' => 'false', 'tipo' => 'booleano', 'descripcion' => 'Modo mantenimiento del sistema', 'grupo' => 'sistema'],
            ['clave' => 'mensaje_bienvenida', 'valor' => 'Bienvenido a AutoMod Pro', 'tipo' => 'texto', 'descripcion' => 'Mensaje de bienvenida', 'grupo' => 'personalizacion'],
        ]);
    }
}
