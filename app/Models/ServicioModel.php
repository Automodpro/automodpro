<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\FactorMarcaModel;
use App\Models\FactorAntiguedadModel;


class ServicioModel extends Model
{
    protected $table = 'servicios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'descripcion', 'precio'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'permit_empty|max_length[500]',
        'precio' => 'required|numeric',
    ];



    public function calcularPrecioFinal(int $servicioId, array $vehiculo): float
    {
        $servicio = $this->find($servicioId);
        if (!$servicio) {
            return 0.0;
        }
        return $this->calcularPrecioFinalServicio($servicio, $vehiculo);
    }

    public function calcularPrecioFinalServicio(array $servicio, array $vehiculo): float
    {
        if (empty($vehiculo)) {
            return 0.0;
        }

        $precioBase = (float) ($servicio['precio'] ?? 0);
        if ($precioBase <= 0) {
            return 0.0;
        }

        $factorTipo = (new FactorTipoModel())->getFactorTipo($vehiculo['tipo'] ?? null);

        $factorMarca = 1.0;

        $marcaId = $vehiculo['marca_id'] ?? null;
        if (!empty($marcaId)) {
            $factorMarca = (new FactorMarcaModel())->getFactorMarca((int)$marcaId);
        }

        $factorAntiguedad = 1.0;
        $anio = $vehiculo['año'] ?? ($vehiculo['anio'] ?? null);
        if (!empty($anio)) {
            $factorAntiguedad = (new FactorAntiguedadModel())->getFactorAnio((int)$anio);
        }

        return $precioBase * $factorTipo * $factorMarca * $factorAntiguedad;
    }

}
