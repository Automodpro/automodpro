<?php
namespace App\Controllers;

use App\Models\PagoModel;
use App\Models\PedidoModel;

class Pagos extends BaseController
{
    public function index()
    {
        $model = new PagoModel();
        $model->select('pagos.*, pedidos.total as pedido_total, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
            ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');

        if (session('rol') === 'usuario') {
            $model->where('pedidos.usuario_id', session('id'));
        }

        $data['pagos'] = $model->orderBy('pagos.id', 'DESC')->findAll();
        return view('pagos/index', $data);
    }

    public function crear()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/pagos')->with('error', 'No tienes permisos para registrar pagos');
        }

        $pedidoModel = new PedidoModel();
        $data['pedidos'] = $pedidoModel
            ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->where('pedidos.estado !=', 'cancelado')
            ->findAll();

        return view('pagos/form', $data);
    }

    public function guardar()
    {
        if (session('rol') === 'usuario') {
            return redirect()->to('/pagos')->with('error', 'No tienes permisos');
        }

        $model = new PagoModel();
        $data = [
            'pedido_id' => $this->request->getPost('pedido_id'),
            'monto' => $this->request->getPost('monto'),
            'metodo_pago' => $this->request->getPost('metodo_pago'),
            'referencia' => $this->request->getPost('referencia') ?? '',
            'estado' => 'pagado',
        ];

        // Depuración funcional temporal para verificar por qué falla insert()
        $idPago = $model->insert($data);

        if (!$idPago) {
            dd([
                'data' => $data,
                'insert_id' => $idPago,
                'errors' => $model->errors(),
            ]);
        }

        $pedidoModel = new PedidoModel();
        $pedidoModel->update($data['pedido_id'], ['estado' => 'completado']);

        return redirect()->to('/pagos')->with('success', 'Pago registrado');
    }

    public function eliminar($id)
    {
        if (session('rol') !== 'admin') {
            return redirect()->to('/pagos')->with('error', 'Solo el administrador puede eliminar pagos');
        }

        $model = new PagoModel();
        $model->delete($id);
        return redirect()->to('/pagos')->with('success', 'Pago eliminado');
    }
}

