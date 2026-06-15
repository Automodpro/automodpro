<?php
namespace App\Controllers;

use App\Models\VehiculoModel;
use App\Models\ServicioModel;
use App\Models\PedidoModel;
use App\Models\PagoModel;
use App\Models\UsuarioModel;

class DashboardPorRol extends BaseController
{
    private function getBaseData(): array
    {
        $vehiculoModel = new VehiculoModel();
        $servicioModel = new ServicioModel();
        $pedidoModel = new PedidoModel();
        $pagoModel = new PagoModel();
        $usuarioModel = new UsuarioModel();

        $data['vehiculos_count'] = $vehiculoModel->countAll();
        $data['servicios_count'] = $servicioModel->countAll();
        $data['pedidos_count'] = $pedidoModel->countAll();
        
        // Conteo de pagos exitosos (estado pagado)
        $data['pagos_count'] = $pagoModel->where('estado', 'pagado')->countAllResults();
        
        $data['usuarios_count'] = $usuarioModel->countAll();

        $data['pedidos_recientes'] = $pedidoModel
            ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->orderBy('pedidos.id', 'DESC')
            ->limit(5)
            ->find();

        return $data;
    }

    private function getRol(): string
    {
        $rolId = (int) session('rol_id');
        return match ($rolId) {
            1 => 'admin',
            2 => 'mecanico',
            3 => 'usuario',
            default => (string) session('rol'),
        };
    }

    public function index()
    {
        // Anti-cache: si el usuario hace "atrás", el navegador no debe mostrar una vista protegida en caché.
        $response = service('response');
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', '0');

        // Solo iniciar dashboard si el usuario ya hizo login (submit del formulario)
        if (!session('logueado')) {
            return redirect()->to('/');
        }


        $rol = $this->getRol();
        $data = $this->getBaseData();

        // Usuario: solo ver sus propios pedidos/estadísticas
        if ($rol === 'usuario') {
            $id = session('id');

            $data['vehiculos_count'] = (new VehiculoModel())
                ->where('usuario_id', $id)
                ->countAllResults();

            $data['pedidos_count'] = (new PedidoModel())
                ->where('usuario_id', $id)
                ->countAllResults();

            $data['pagos_count'] = (new PagoModel())
                ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
                ->where('pedidos.usuario_id', $id)
                ->where('pagos.estado', 'pagado')
                ->countAllResults();

            $data['usuarios_count'] = 0;
            $data['servicios_count'] = (new ServicioModel())->countAllResults();

            $data['pedidos_recientes'] = (new PedidoModel())
                ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
                ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
                ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                ->where('pedidos.usuario_id', $id)
                ->orderBy('pedidos.id', 'DESC')
                ->limit(5)
                ->find();
        }

        // Mecánico: ver pedidos pero no gestionar usuarios
        if ($rol === 'mecanico') {
            $data['usuarios_count'] = 0;
        }

        // Admin: validar rol (si llega otro valor, redirige)
        if ($rol !== 'admin' && $rol !== 'mecanico' && $rol !== 'usuario') {
            return redirect()->to('/');
        }

        // SIEMPRE renderizamos dashboard/index.php (según sesión se muestra el nombre del rol)
        return view('dashboard/index', $data);



    }
}
