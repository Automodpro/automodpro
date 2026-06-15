<?php
namespace App\Controllers;

use App\Models\ServicioModel;
use App\Models\FactorMarcaModel;
use App\Models\FactorAntiguedadModel;
use Throwable;

class Servicios extends BaseController
{
    public function index()
    {
        $model = new ServicioModel();
        $data['servicios'] = $model->orderBy('id', 'DESC')->findAll();
        return view('servicios/index', $data);
    }

    public function crear()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/servicios')->with('error', 'No tienes permisos');
        }

        return view('servicios/form');

    }

    public function guardar()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/servicios')->with('error', 'No tienes permisos');
        }

        $model = new ServicioModel();
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
        ];

        if (!$model->insert($data)) {
            return redirect()->back()->with('errores', $model->errors())->withInput();
        }

        // Factores por servicio eliminados: el precio se ajusta globalmente en el sistema de Factores de Precio.


        return redirect()->to('/servicios')->with('success', 'Servicio creado');
    }

    public function editar($id)
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/servicios')->with('error', 'No tienes permisos');
        }

        $model = new ServicioModel();
        $servicio = $model->find($id);


        if (!$servicio) {
            return redirect()->to('/servicios')->with('error', 'Servicio no encontrado');
        }

        return view('servicios/form', [
            'servicio' => $servicio,
        ]);

    }

    public function actualizar($id)
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/servicios')->with('error', 'No tienes permisos');
        }

        $model = new ServicioModel();
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'precio' => $this->request->getPost('precio'),
        ];

        if (!$model->update($id, $data)) {
            return redirect()->back()->with('errores', $model->errors())->withInput();
        }

        return redirect()->to('/servicios')->with('success', 'Servicio actualizado');

    }

    public function eliminar($id)
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/servicios')->with('error', 'No tienes permisos');
        }

        $model = new ServicioModel();

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException('No se pudo eliminar el servicio');
            }
        } catch (Throwable $e) {
            return redirect()->to('/servicios')->with('error', 'No se pudo eliminar el servicio porque tiene registros relacionados');
        }

        return redirect()->to('/servicios')->with('success', 'Servicio eliminado');
    }


    public function costos()
    {
        $servicioId = (int) $this->request->getGet('servicio_id');
        $vehiculoId = (int) $this->request->getGet('vehiculo_id');

        if ($servicioId > 0 && $vehiculoId > 0) {
            $vehiculoModel = new \App\Models\VehiculoModel();
            $servicioModel = new ServicioModel();

            $vehiculo = $vehiculoModel->find($vehiculoId);
            if (!$vehiculo) {
                return $this->response->setJSON(['costo' => 0]);
            }

            $servicio = $servicioModel->find($servicioId);
            if (!$servicio) {
                return $this->response->setJSON(['costo' => 0]);
            }

            $precioBase = (float) ($servicio['precio'] ?? 0);
            $precioFinal = $servicioModel->calcularPrecioFinalServicio($servicio, $vehiculo);

            // factor devuelto como multiplicador total para UI
            $factorTotal = $precioBase > 0 ? ($precioFinal / $precioBase) : 1.0;

            return $this->response->setJSON([
                'costo' => $precioFinal,
                'precio_base' => $precioBase,
                'factor' => $factorTotal,
                'tipo_vehiculo' => $vehiculo['tipo'] ?? '',
            ]);

        }

        return $this->response->setJSON([]);
    }

    public function precios()
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

    // Factores por servicio eliminados completamente.

}
