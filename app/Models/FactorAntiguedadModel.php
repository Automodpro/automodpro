<?php
namespace App\Models;

use CodeIgniter\Model;

class FactorAntiguedadModel extends Model
{
    protected $table = 'factores_antiguedad';
    protected $primaryKey = 'id';
    protected $allowedFields = ['anio_min', 'anio_max', 'factor'];
    protected $useTimestamps = false;

    public function getFactorAnio($anio)
    {
        $row = $this->where('anio_min <=', (int)$anio)
            ->where('anio_max >=', (int)$anio)
            ->first();

        return $row ? (float) $row['factor'] : 1.0;
    }
}

