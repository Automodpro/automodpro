<?php
namespace App\Controllers;

use App\Models\VehiculoModel;
use App\Models\ServicioModel;
use App\Models\PedidoModel;
use App\Models\PagoModel;
use App\Models\UsuarioModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $vehiculoModel = new VehiculoModel();
        $servicioModel = new ServicioModel();
        $pedidoModel = new PedidoModel();
        $pagoModel = new PagoModel();
        $usuarioModel = new UsuarioModel();

        $data['vehiculos_count'] = $vehiculoModel->countAll();
        $data['servicios_count'] = $servicioModel->countAll();
        $data['pedidos_count'] = $pedidoModel->countAll();
        $data['pagos_count'] = $pagoModel->countAll();
        $data['usuarios_count'] = $usuarioModel->countAll();
        $data['pedidos_recientes'] = $pedidoModel
            // En tu BD la columna del usuario es `nombre_usuario` (no `nombre`).
            ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')

            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->orderBy('pedidos.id', 'DESC')
            ->limit(5)
            ->find();

        return view('dashboard/index', $data);
    }
}
