<?php
namespace App\Models;

use CodeIgniter\Model;

class VehiculoModel extends Model
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'id';

    // BD real: vehiculos usa columna `año` (con ñ) y `creado_en`.
    // UI/controller trabajarán con `anio` (input), pero se convertirá a `año` antes de guardar.
    // BD real: guarda IDs de marca/modelo y además `tipo` (derivado del modelo en la UI).
    protected $allowedFields = ['usuario_id', 'marca_id', 'modelo_id', 'tipo', 'año', 'placa'];

    // Evitar CodeIgniter timestamps automáticos (no existen created_at/updated_at en la BD).
    protected $useTimestamps = false;

    protected $validationRules = [
        'usuario_id' => 'required|numeric',
        'marca_id' => 'required|numeric',
        'modelo_id' => 'required|numeric',
        'tipo' => 'required|in_list[Sedan,SUV,Camioneta,Deportivo,Hatchback,sedan,SUV,Camioneta,Deportivo,Hatchback]',
        'año' => 'required|numeric|min_length[4]|max_length[4]',
        'placa' => 'required|max_length[20]',
    ];

    protected $validationMessages = [
        'marca_id' => ['required' => 'La marca es obligatoria'],
        'modelo_id' => ['required' => 'El modelo es obligatorio'],
        'tipo' => ['required' => 'El tipo de vehículo es obligatorio'],
        'año' => ['required' => 'El año es obligatorio'],
        'placa' => ['required' => 'La placa es obligatoria'],
    ];
}

