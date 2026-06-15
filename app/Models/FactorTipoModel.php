<?php
namespace App\Models;

use CodeIgniter\Model;

class FactorTipoModel extends Model
{
    protected $table = 'factores_tipo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tipo', 'factor'];
    protected $useTimestamps = false;

    public function getFactorTipo(?string $tipo): float
    {
        if ($tipo === null || trim($tipo) === '') {
            return 1.00;
        }

        $tipoNormalizado = trim($tipo);

        $row = $this->where('tipo', $tipoNormalizado)->first();
        return $row ? (float) $row['factor'] : 1.00;
    }
}

