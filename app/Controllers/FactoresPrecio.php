<?php
namespace App\Controllers;

use App\Models\FactorPrecioModel;
use App\Models\FactorTipoModel;

use App\Models\FactorMarcaModel;
use App\Models\FactorAntiguedadModel;
use App\Models\ServicioModel;
use App\Models\MarcaModel;

class FactoresPrecio extends BaseController
{
    private function requireAdmin(): void
    {
        if (session('rol_id') != 1) {
            throw new \RuntimeException('No autorizado');
        }
    }

    public function index()
    {
        // Administrador (1) y Mecánico (2) pueden ver el catálogo de factores
        if (!in_array((int)session('rol_id'), [1, 2])) {
            return redirect()->to('/dashboard')->with('error', 'No autorizado');
        }

        $factorTipoModel = new FactorTipoModel();
        $factorMarcaModel = new FactorMarcaModel();
        $factorAntiguedadModel = new FactorAntiguedadModel();

        $data['factores_tipo'] = $factorTipoModel->select('factores_tipo.*')->orderBy('tipo')->findAll();


        $data['factores_marca'] = $factorMarcaModel->select('factores_marca.*, marcas.nombre as marca_nombre')
            ->join('marcas', 'marcas.id = factores_marca.marca_id', 'left')
            ->orderBy('marca_id')
            ->findAll();

        $data['factores_antiguedad'] = $factorAntiguedadModel->orderBy('anio_min')->findAll();

        return view('factores_precio/index', $data);
    }

    public function crear()
    {
        $this->requireAdmin();
        $tipo = $this->request->getGet('tipo');

        $data = [];
        $data['tipo'] = $tipo;

        if ($tipo === 'tipo_vehiculo') {
            // Catálogo fijo de tipos (coincide con la tabla factores_tipo)
            $data['tipos_vehiculo'] = ['Sedan', 'SUV', 'Camioneta', 'Deportivo', 'Hatchback'];
            return view('factores_precio/form', $data);
        }

        $data['tipos_vehiculo'] = ['Sedan', 'SUV', 'Camioneta', 'Deportivo', 'Hatchback'];

        $marcaModel = new MarcaModel();
        $data['marcas'] = $marcaModel->orderBy('nombre')->findAll();

        $servicioModel = new ServicioModel();
        $data['servicios'] = $servicioModel->orderBy('nombre')->findAll();

        return view('factores_precio/form', $data);

    }

    public function guardar()
    {
        $this->requireAdmin();

        $tipo = $this->request->getPost('tipo_factor');

        if ($tipo === 'tipo_vehiculo') {
            $model = new FactorTipoModel();
            $tipoVehiculo = $this->request->getPost('tipo_vehiculo');

            $data = [
                'tipo' => $tipoVehiculo,
                'factor' => (float) $this->request->getPost('factor'),
            ];

            $existing = $model->where('tipo', $data['tipo'])->first();
            if ($existing) {
                $model->update($existing['id'], $data);
                $msg = 'Factor de tipo actualizado';
            } else {
                $model->insert($data);
                $msg = 'Factor de tipo creado';
            }
        } elseif ($tipo === 'marca') {

            $model = new FactorMarcaModel();
            $data = [
                'marca_id' => (int) $this->request->getPost('marca_id'),
                'factor' => (float) $this->request->getPost('factor'),
            ];

            $existing = $model->where('marca_id', $data['marca_id'])->first();
            if ($existing) {
                $model->update($existing['id'], $data);
                $msg = 'Factor de marca actualizado';
            } else {
                $model->insert($data);
                $msg = 'Factor de marca creado';
            }
        } elseif ($tipo === 'antiguedad') {
            $model = new FactorAntiguedadModel();
            $data = [
                'anio_min' => (int) $this->request->getPost('anio_min'),
                'anio_max' => (int) $this->request->getPost('anio_max'),
                'factor' => (float) $this->request->getPost('factor'),
            ];
            $model->insert($data);
            $msg = 'Factor de antigüedad creado';
        } else {
            return redirect()->back()->with('error', 'Tipo de factor no válido');
        }

        return redirect()->to('/factores-precio')->with('success', $msg ?? 'Factor guardado');
    }

    public function editar($id)
    {
        $this->requireAdmin();
        $tipo = $this->request->getGet('tipo');

        $data = [];
        $data['tipo'] = $tipo;

        if ($tipo === 'tipo_vehiculo') {
            $model = new FactorTipoModel();
            $data['factor'] = $model->find($id);
            $data['tipos_vehiculo'] = ['Sedan', 'SUV', 'Camioneta', 'Deportivo', 'Hatchback'];

        } elseif ($tipo === 'marca') {
            $model = new FactorMarcaModel();
            $data['factor'] = $model->find($id);
            $marcaModel = new MarcaModel();
            $data['marcas'] = $marcaModel->orderBy('nombre')->findAll();
        } elseif ($tipo === 'antiguedad') {
            $model = new FactorAntiguedadModel();
            $data['factor'] = $model->find($id);
        } else {
            return redirect()->to('/factores-precio')->with('error', 'Tipo no válido');
        }

        if (!$data['factor']) {
            return redirect()->to('/factores-precio')->with('error', 'Factor no encontrado');
        }

        return view('factores_precio/form', $data);
    }

    public function actualizar($id)
    {
        $this->requireAdmin();

        $tipo = $this->request->getPost('tipo_factor');

        if ($tipo === 'tipo_vehiculo') {
            $model = new FactorTipoModel();
            $data = [
                'tipo' => $this->request->getPost('tipo_vehiculo'),
                'factor' => (float) $this->request->getPost('factor'),
            ];
            $model->update($id, $data);
        } elseif ($tipo === 'marca') {

            $model = new FactorMarcaModel();
            $model->update($id, ['factor' => (float) $this->request->getPost('factor')]);
        } elseif ($tipo === 'antiguedad') {
            $model = new FactorAntiguedadModel();
            $model->update($id, [
                'anio_min' => (int) $this->request->getPost('anio_min'),
                'anio_max' => (int) $this->request->getPost('anio_max'),
                'factor' => (float) $this->request->getPost('factor'),
            ]);
        }

        return redirect()->to('/factores-precio')->with('success', 'Factor actualizado');
    }

    public function eliminar($id)
    {
        $this->requireAdmin();
        $tipo = $this->request->getGet('tipo');

        if ($tipo === 'tipo_vehiculo') {
            $model = new FactorTipoModel();
        } elseif ($tipo === 'marca') {

            $model = new FactorMarcaModel();
        } elseif ($tipo === 'antiguedad') {
            $model = new FactorAntiguedadModel();
        } else {
            return redirect()->to('/factores-precio')->with('error', 'Tipo no válido');
        }

        $model->delete($id);
        return redirect()->to('/factores-precio')->with('success', 'Factor eliminado');
    }

    public function preciosJson()
    {
        $vehiculoId = (int) $this->request->getGet('vehiculo_id');

        if ($vehiculoId <= 0) {
            return $this->response->setJSON([]);
        }

        $vehiculoModel = new \App\Models\VehiculoModel();
        $servicioModel = new ServicioModel();

        $vehiculo = $vehiculoModel->find($vehiculoId);
        if (!$vehiculo) {
            return $this->response->setJSON([]);
        }

        $servicios = $servicioModel->findAll();
        $resultado = [];

        foreach ($servicios as $s) {
            $precioFinal = $servicioModel->calcularPrecioFinalServicio($s, $vehiculo);
            $resultado[] = [
                'id' => (int) $s['id'],
                'nombre' => $s['nombre'],
                'precio_base' => (float) ($s['precio'] ?? 0),
                'precio_final' => $precioFinal,
            ];
        }

        return $this->response->setJSON($resultado);
    }
}