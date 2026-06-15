<?php
namespace App\Models;

use CodeIgniter\Model;

class FactorMarcaModel extends Model
{
    protected $table = 'factores_marca';
    protected $primaryKey = 'id';
    protected $allowedFields = ['marca_id', 'factor'];
    protected $useTimestamps = false;

    public function getFactorMarca($marcaId)
    {
        $row = $this->where('marca_id', $marcaId)->first();
        return $row ? (float) $row['factor'] : 1.0;
    }
}

