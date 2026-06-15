<?php
namespace App\Models;

use CodeIgniter\Model;

class FactorPrecioModel extends Model
{
    protected $table = 'factores_precio';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tipo_vehiculo', 'factor', 'servicio_id'];
    protected $useTimestamps = false;

    public function getFactorTipo($servicioId, $tipoVehiculo)
    {
        if ($tipoVehiculo === null || $tipoVehiculo === '') {
            return 1.0;
        }

        $tipos = [
            'sedan' => 'Sedan',
            'suv' => 'SUV',
            'camioneta' => 'Camioneta',
            'deportivo' => 'Deportivo',
            'hatchback' => 'Hatchback',
        ];
        $tipoVehiculo = $tipos[strtolower(trim((string) $tipoVehiculo))] ?? trim((string) $tipoVehiculo);

        $row = $this->where('servicio_id', $servicioId)
            ->where('tipo_vehiculo', $tipoVehiculo)
            ->first();

        return $row ? (float) $row['factor'] : 1.0;
    }
}

