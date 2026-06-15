<?php
namespace App\Controllers;

use App\Models\DetallePedidoModel;
use App\Models\PedidoModel;
use App\Models\ServicioModel;
use App\Models\VehiculoModel;

class Pedidos extends BaseController
{
    private function requireAdmin(): void
    {
        if (session('rol') !== 'admin') {
            throw new \RuntimeException('No autorizado');
        }
    }

    public function index()
    {
        $model = new PedidoModel();
        $model->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');

        if (session('rol') === 'usuario') {
            $model->where('pedidos.usuario_id', session('id'));
        }

        $data['pedidos'] = $model->orderBy('pedidos.id', 'DESC')->findAll();
        return view('pedidos/index', $data);
    }

    public function crear()
    {
        $vehiculoModel = new VehiculoModel();
        $servicioModel = new ServicioModel();

        $vehiculosQuery = $vehiculoModel
            ->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
            ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
            ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
            ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left');

        if (session('rol') === 'usuario') {
            $vehiculosQuery->where('vehiculos.usuario_id', session('id'));
        }

        $data['vehiculos'] = $vehiculosQuery->findAll();
        $data['servicios'] = $servicioModel->findAll();
        $data['tipos_vehiculo'] = ['Sedan', 'SUV', 'Camioneta', 'Deportivo', 'Hatchback'];
        $data['servicioSeleccionado'] = (int) $this->request->getGet('servicio_id');

        return view('pedidos/form', $data);
    }

    public function guardar()
    {
        $pedidoModel = new PedidoModel();
        $detalleModel = new DetallePedidoModel();
        $servicioModel = new ServicioModel();
        $vehiculoModel = new VehiculoModel();

        $vehiculoId = (int) $this->request->getPost('vehiculo_id');
        if ($vehiculoId <= 0) {
            return redirect()->back()->with('error', 'Debe seleccionar un vehiculo valido')->withInput();
        }

        $vehiculo = $vehiculoModel->find($vehiculoId);
        if (!$vehiculo) {
            return redirect()->back()->with('error', 'Vehiculo no encontrado')->withInput();
        }

        // El usuario_id se obtiene FORZOSAMENTE del dueño real del vehículo
        // Ignorando cualquier valor enviado desde el navegador
        $usuarioId = (int) ($vehiculo['usuario_id'] ?? 0);

        $servicios = $this->request->getPost('servicios') ?? [];

        if (empty($servicios)) {
            return redirect()->back()->with('error', 'Debe seleccionar al menos un servicio')->withInput();
        }

        $total = 0;
        $precios = [];

        foreach ($servicios as $servicioId) {
            $servicio = $servicioModel->find((int) $servicioId);
            if (!$servicio) {
                continue;
            }

            $precioFinal = $servicioModel->calcularPrecioFinalServicio($servicio, $vehiculo);
            $precios[(int) $servicioId] = $precioFinal;
            $total += $precioFinal;
        }

        if (empty($precios)) {
            return redirect()->back()->with('error', 'Debe seleccionar al menos un servicio valido')->withInput();
        }

        $pedidoData = [
            'usuario_id' => $usuarioId,
            'vehiculo_id' => $vehiculoId,
            'estado' => 'pendiente',
            'total' => $total,
        ];

        if ($pedidoModel->insert($pedidoData)) {
            $pedidoId = $pedidoModel->getInsertID();

            foreach ($precios as $servicioId => $precioFinal) {
                $detalleModel->insert([
                    'pedido_id' => $pedidoId,
                    'servicio_id' => $servicioId,
                    'precio_unitario' => $precioFinal,
                    'cantidad' => 1,
                ]);
            }

            return redirect()->to('/pedidos')->with('success', 'Pedido creado. Total: $ ' . number_format($total, 0, ',', '.') . ' COP');
        }

        return redirect()->back()->with('errores', $pedidoModel->errors())->withInput();
    }

    public function ver($id)
    {
        $pedidoModel = new PedidoModel();
        $detalleModel = new DetallePedidoModel();

        $pedido = $pedidoModel
            ->select('pedidos.*, vehiculos.placa, vehiculos.tipo, usuarios.nombre_usuario as usuario_nombre, marcas.nombre as marca, modelos.nombre as modelo')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
            ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left')
            ->find($id);

        if (!$pedido) {
            return redirect()->to('/pedidos')->with('error', 'Pedido no encontrado');
        }

        if (session('rol') === 'usuario' && $pedido['usuario_id'] != session('id')) {
            return redirect()->to('/pedidos')->with('error', 'No puedes ver este pedido');
        }

        $data['pedido'] = $pedido;
        $data['detalles'] = $detalleModel
            ->select('detalles_pedido.*, servicios.nombre as servicio_nombre')
            ->join('servicios', 'servicios.id = detalles_pedido.servicio_id', 'left')
            ->where('pedido_id', $id)
            ->findAll();

        return view('pedidos/ver', $data);
    }

    public function editar($id)
    {
        $this->requireAdmin();

        $pedidoModel = new PedidoModel();
        $pedido = $pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('/pedidos')->with('error', 'Pedido no encontrado');
        }

        $data['pedido'] = $pedido;
        $data['estados'] = ['pendiente', 'aprobado', 'en_proceso', 'completado', 'cancelado'];

        return view('pedidos/editar', $data);
    }

    public function actualizar($id)
    {
        $this->requireAdmin();

        $pedidoModel = new PedidoModel();
        $pedido = $pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('/pedidos')->with('error', 'Pedido no encontrado');
        }

        $data = [
            'estado' => $this->request->getPost('estado'),
            'total' => $this->request->getPost('total'),
            'notas' => $this->request->getPost('notas'),
        ];

        // Validación manual mínima (estado en lista permitida)
        $permitidos = ['pendiente', 'aprobado', 'en_proceso', 'completado', 'cancelado'];
        if (!in_array($data['estado'], $permitidos, true)) {
            return redirect()->back()->with('error', 'Estado no válido')->withInput();
        }

        if ($pedidoModel->update($id, $data)) {
            return redirect()->to('/pedidos/ver/' . $id)->with('success', 'Pedido actualizado');
        }

        return redirect()->back()->with('errores', $pedidoModel->errors())->withInput();
    }

    public function eliminar($id)
    {
        if (session('rol') !== 'admin') {
            return redirect()->to('/pedidos')->with('error', 'Solo el administrador puede eliminar pedidos');
        }

        $pedidoModel = new PedidoModel();
        $pedido = $pedidoModel->find($id);

        if (!$pedido) {
            return redirect()->to('/pedidos')->with('error', 'Pedido no encontrado');
        }

        $detalleModel = new DetallePedidoModel();
        $detalleModel->where('pedido_id', $id)->delete();
        $pedidoModel->delete($id);

        return redirect()->to('/pedidos')->with('success', 'Pedido eliminado');
    }
}
