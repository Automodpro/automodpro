<?php
namespace App\Models;
use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    protected $table = 'configuraciones';
    protected $primaryKey = 'id';
    protected $allowedFields = ['clave', 'valor'];
    protected $useTimestamps = false;

    public function getValor($clave)
    {
        $row = $this->where('clave', $clave)->first();
        return $row ? $row['valor'] : null;
    }
}
