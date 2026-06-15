<?php

namespace App\Controllers;

use App\Libraries\PdfService;
use App\Models\UsuarioModel;
use App\Models\VehiculoModel;
use App\Models\ServicioModel;
use App\Models\PedidoModel;
use App\Models\PagoModel;
use App\Models\FactorTipoModel;
use App\Models\FactorMarcaModel;
use App\Models\FactorAntiguedadModel;

class Reportes extends BaseController
{
    protected $pdf;
    protected $rolId;
    protected $userId;

    /**
     * Inicializa datos y verifica que el usuario esté logueado.
     * El filtro 'auth' ya protege la ruta, pero aquí aseguramos disponibilidad de servicios.
     */
    protected function checkAuth(): ?\CodeIgniter\HTTP\RedirectResponse
    {
        if (!session('logueado')) {
            return redirect()->to('/auth/login')->with('error', 'Debe iniciar sesión para acceder a los reportes.');
        }
        
        $this->rolId = (int) session('rol_id');
        $this->userId = (int) session('id');
        $this->pdf = new PdfService();

        return null;
    }

    // --- REPORTES DE USUARIOS ---
    public function usuariosGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        if ($this->rolId !== 1) return redirect()->to('/dashboard')->with('error', 'No autorizado');

        $model = new UsuarioModel();
        $data['titulo'] = "Reporte General de Usuarios";
        $data['columnas'] = ['ID', 'Usuario', 'Correo', 'Rol', 'Registro'];
        $data['registros'] = $model->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, roles.nombre as rol_nombre, usuarios.creado_en')
                                   ->join('roles', 'roles.id = usuarios.rol_id', 'left')
                                   ->findAll();
        $data['mapping'] = ['id', 'nombre_usuario', 'correo', 'rol_nombre', 'creado_en'];

        $this->pdf->generate('reportes/general', $data, 'usuarios_automod.pdf');
    }

    public function usuarioDetalle($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new UsuarioModel();

        // Admin ve cualquier usuario, usuario autenticado solo su propio usuario.
        if ($this->rolId !== 1 && (int)$id !== $this->userId) {
            return redirect()->to('/usuarios')->with('error', 'No autorizado');
        }

        $usuario = $model->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, roles.nombre as rol_nombre, usuarios.creado_en')
            ->join('roles', 'roles.id = usuarios.rol_id', 'left')
            ->find((int)$id);

        if (!$usuario) {
            return redirect()->to('/usuarios')->with('error', 'Usuario no encontrado');
        }

        $data['titulo'] = 'Ficha Técnica de Usuario';
        $data['registro'] = $usuario;
        $data['campos'] = [
            'ID' => $usuario['id'],
            'Nombre' => $usuario['nombre_usuario'],
            'Correo' => $usuario['correo'],
            'Rol' => $usuario['rol_nombre'] ?? 'Usuario',
            'Registro' => isset($usuario['creado_en']) ? date('d/m/Y', strtotime($usuario['creado_en'])) : 'N/A',
        ];

        $this->pdf->generate('reportes/detalle', $data, "usuario_{$usuario['id']}.pdf");
    }

    // --- REPORTES DE VEHÍCULOS ---

    public function vehiculosGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new VehiculoModel();
        $query = $model->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
                       ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
                       ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
                       ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left');

        // Si no es Admin (1) ni Mecánico (2), filtrar por el ID del cliente
        if (!in_array($this->rolId, [1, 2])) {
            $query->where('vehiculos.usuario_id', $this->userId);
        }

        $data['titulo'] = "Listado de Vehículos Registrados";
        $data['registros'] = $query->findAll();
        $data['columnas'] = ['Placa', 'Marca', 'Modelo', 'Año', 'Propietario'];
        $data['mapping'] = ['placa', 'marca', 'modelo', 'año', 'propietario'];

        $this->pdf->generate('reportes/general', $data, 'vehiculos_reporte.pdf');
    }

    public function vehiculoDetalle($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new VehiculoModel();
        $vehiculo = $model->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
                          ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
                          ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
                          ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left')
                          ->find($id);

        // Validación de propiedad: Solo el dueño o personal del taller pueden ver el detalle
        if (!$vehiculo || (!in_array($this->rolId, [1, 2]) && $vehiculo['usuario_id'] != $this->userId)) {
            return redirect()->to('/vehiculos')->with('error', 'Acceso denegado o vehículo no encontrado.');
        }

        $data['titulo'] = "Ficha Técnica de Vehículo";
        $data['registro'] = $vehiculo;
        $data['campos'] = [
            'Placa' => $vehiculo['placa'],
            'Marca' => $vehiculo['marca'],
            'Modelo' => $vehiculo['modelo'],
            'Tipo' => $vehiculo['tipo'],
            'Año' => $vehiculo['año'],
            'Propietario' => $vehiculo['propietario']
        ];

        $this->pdf->generate('reportes/detalle', $data, "vehiculo_{$vehiculo['placa']}.pdf");
    }

    // --- REPORTES DE PEDIDOS ---
    public function pedidosGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $model = new PedidoModel();
        $query = $model->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario')
                       ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
                       ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');

        if (!in_array($this->rolId, [1, 2])) {
            $query->where('pedidos.usuario_id', $this->userId);
        }

        $registros = $query->findAll();
        $totalRecaudado = 0;

        foreach ($registros as &$r) {
            $totalRecaudado += (float)($r['total'] ?? 0);
            $r['total_fmt'] = '$' . number_format((float)($r['total'] ?? 0), 0, ',', '.');
            $r['fecha_fmt'] = isset($r['creado_en']) ? date('d/m/Y H:i', strtotime($r['creado_en'])) : 'N/A';
        }

        $data['titulo'] = "Historial General de Pedidos";
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Fecha', 'Vehículo', 'Cliente', 'Total', 'Estado'];
        $data['mapping'] = ['id', 'fecha_fmt', 'placa', 'nombre_usuario', 'total_fmt', 'estado'];

        $data['resumen'] = "Total de registros: " . count($registros) . " | Recaudación total: $" . number_format($totalRecaudado, 0, ',', '.');

        $this->pdf->generate('reportes/general', $data, 'pedidos_automod.pdf');
    }

    public function pedidoDetalle($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $model = new PedidoModel();
        $pedido = $model->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario, usuarios.correo')
                        ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
                        ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
                        ->find($id);

        if (!$pedido || (!in_array($this->rolId, [1, 2]) && $pedido['usuario_id'] != $this->userId)) {
            return redirect()->to('/pedidos')->with('error', 'No tienes permiso para ver este reporte.');
        }

        // Obtener detalles del pedido
        $db = \Config\Database::connect();
        $detalles = $db->table('detalles_pedido')
                       ->select('servicios.nombre as servicio_nombre, detalles_pedido.precio_unitario')
                       ->join('servicios', 'servicios.id = detalles_pedido.servicio_id')
                       ->where('pedido_id', $id)->get()->getResultArray();

        $data['titulo'] = "Comprobante de Pedido #" . $id;
        $data['pedido'] = $pedido;
        $data['detalles'] = $detalles;

        $this->pdf->generate('reportes/pedido_detalle', $data, "pedido_{$id}.pdf");
    }

    // --- REPORTES DE SERVICIOS ---
    public function serviciosGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        $model = new ServicioModel();

        $registros = $model->findAll();
        foreach ($registros as &$r) {
            $r['precio'] = '$' . number_format($r['precio'], 0, ',', '.');
            $r['duracion_estimada'] = $r['duracion_estimada'] . ' min';
        }

        $data['titulo'] = "Catálogo General de Servicios";
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Servicio', 'Descripción', 'Precio Base', 'Duración'];
        $data['mapping'] = ['id', 'nombre', 'descripcion', 'precio', 'duracion_estimada'];

        $this->pdf->generate('reportes/general', $data, 'catalogo_servicios.pdf');
    }

    public function servicioDetalle($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new ServicioModel();
        $servicio = $model->find((int)$id);

        if (!$servicio) {
            return redirect()->to('/servicios')->with('error', 'Servicio no encontrado');
        }

        $data['titulo'] = 'Ficha Técnica de Servicio';
        $data['registro'] = $servicio;
        $data['campos'] = [
            'ID' => $servicio['id'],
            'Nombre' => $servicio['nombre'],
            'Descripción' => $servicio['descripcion'] ?? 'Sin descripción',
            'Precio Base' => '$' . number_format($servicio['precio'], 0, ',', '.'),
            'Duración Estimada' => $servicio['duracion_estimada'] . ' min',
        ];

        $this->pdf->generate('reportes/detalle', $data, "servicio_{$id}.pdf");
    }

    // --- REPORTES DE PAGOS ---
    public function pagosGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new PagoModel();
        $query = $model->select('pagos.*, pedidos.total as pedido_total, vehiculos.placa, usuarios.nombre_usuario as cliente')
            ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');

        if (!in_array($this->rolId, [1, 2])) {
            $query->where('pedidos.usuario_id', $this->userId);
        }

        $registros = $query->findAll();
        $totalRecaudado = 0;
        foreach ($registros as &$r) {
            $totalRecaudado += (float)($r['monto'] ?? 0);
            $r['monto_fmt'] = '$' . number_format((float)($r['monto'] ?? 0), 0, ',', '.');
            $r['fecha_fmt'] = isset($r['fecha_pago']) ? date('d/m/Y H:i', strtotime($r['fecha_pago'])) : 'N/A';
            $r['metodo_fmt'] = ucfirst($r['metodo_pago'] ?? '');
        }

        $data['titulo'] = 'Reporte General de Pagos';
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Pedido', 'Cliente', 'Placa', 'Monto', 'Método', 'Estado', 'Fecha'];
        $data['mapping'] = ['id', 'pedido_id', 'cliente', 'placa', 'monto_fmt', 'metodo_fmt', 'estado', 'fecha_fmt'];
        $data['resumen'] = "Total recaudado: $" . number_format($totalRecaudado, 0, ',', '.') . " | Cantidad de transacciones: " . count($registros);

        $this->pdf->generate('reportes/pagos_general', $data, 'pagos_reporte.pdf');
    }

    public function pagoDetalle($id)
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $model = new PagoModel();
        $pago = $model->select('pagos.*, pedidos.total as pedido_total, vehiculos.placa, usuarios.nombre_usuario as cliente')
            ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->find((int)$id);

        if (!$pago || (!in_array($this->rolId, [1, 2]) && $pago['usuario_id'] != $this->userId)) {
            return redirect()->to('/pagos')->with('error', 'Pago no encontrado o acceso denegado');
        }

        $data['titulo'] = 'Comprobante de Pago #' . $id;
        $data['campos'] = [
            'ID Transacción' => $pago['id'],
            'Pedido Relacionado' => '#' . $pago['pedido_id'],
            'Cliente' => $pago['cliente'],
            'Vehículo (Placa)' => $pago['placa'],
            'Monto Pagado' => '$' . number_format($pago['monto'], 0, ',', '.'),
            'Método de Pago' => ucfirst($pago['metodo_pago']),
            'Referencia' => $pago['referencia'] ?: 'Sin referencia',
            'Fecha de Pago' => date('d/m/Y H:i', strtotime($pago['fecha_pago'])),
            'Estado' => strtoupper($pago['estado'])
        ];

        $this->pdf->generate('reportes/detalle', $data, "pago_{$id}.pdf");
    }

    // --- REPORTES DE FACTORES DE PRECIO ---
    public function factoresPrecioGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        // Factores se administran; aplicamos filtro por rol cuando NO es admin.
        // Si tu lógica de negocio quiere que usuario vea solo algunos, ajustamos aquí.
        if (!in_array($this->rolId, [1, 2])) {
            return redirect()->to('/dashboard')->with('error', 'No autorizado');
        }

        $factorTipoModel = new FactorTipoModel();
        $factorMarcaModel = new FactorMarcaModel();
        $factorAntiguedadModel = new FactorAntiguedadModel();

        $data['titulo'] = 'Reporte General de Factores de Precio';
        $data['tipos'] = $factorTipoModel->select('factores_tipo.*')->orderBy('tipo')->findAll();
        $data['marcas'] = $factorMarcaModel->select('factores_marca.*, marcas.nombre as marca_nombre')
            ->join('marcas', 'marcas.id = factores_marca.marca_id', 'left')
            ->orderBy('marca_id')
            ->findAll();
        $data['antiguedad'] = $factorAntiguedadModel->orderBy('anio_min')->findAll();

        $this->pdf->generate('reportes/factores_precio_general', $data, 'factores_precio_reporte.pdf');
    }

    public function factoresTipo()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        if (!in_array($this->rolId, [1, 2])) return redirect()->to('/dashboard');

        $model = new FactorTipoModel();
        $registros = $model->orderBy('tipo')->findAll();
        foreach ($registros as &$r) {
            $r['factor_fmt'] = number_format((float)$r['factor'], 2) . 'x';
        }

        $data['titulo'] = "Reporte de Factores por Tipo de Vehículo";
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Tipo de Vehículo', 'Factor Multiplicador'];
        $data['mapping'] = ['id', 'tipo', 'factor_fmt'];

        $this->pdf->generate('reportes/general', $data, 'factores_tipo.pdf');
    }

    public function factoresMarca()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        if (!in_array($this->rolId, [1, 2])) return redirect()->to('/dashboard');

        $model = new FactorMarcaModel();
        $registros = $model->select('factores_marca.*, marcas.nombre as marca_nombre')
            ->join('marcas', 'marcas.id = factores_marca.marca_id', 'left')
            ->orderBy('marca_nombre')
            ->findAll();

        foreach ($registros as &$r) {
            $r['factor_fmt'] = number_format((float)$r['factor'], 2) . 'x';
        }

        $data['titulo'] = "Reporte de Factores por Marca";
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Marca', 'Factor Multiplicador'];
        $data['mapping'] = ['id', 'marca_nombre', 'factor_fmt'];

        $this->pdf->generate('reportes/general', $data, 'factores_marca.pdf');
    }

    public function factoresAntiguedad()
    {
        if ($redirect = $this->checkAuth()) return $redirect;
        if (!in_array($this->rolId, [1, 2])) return redirect()->to('/dashboard');

        $model = new FactorAntiguedadModel();
        $registros = $model->orderBy('anio_min')->findAll();

        foreach ($registros as &$r) {
            $r['factor_fmt'] = number_format((float)$r['factor'], 2) . 'x';
            $r['rango'] = $r['anio_min'] . ' - ' . $r['anio_max'];
        }

        $data['titulo'] = "Reporte de Factores por Antigüedad (Años)";
        $data['registros'] = $registros;
        $data['columnas'] = ['ID', 'Rango de Años', 'Factor Multiplicador'];
        $data['mapping'] = ['id', 'rango', 'factor_fmt'];

        $this->pdf->generate('reportes/general', $data, 'factores_antiguedad.pdf');
    }

    // --- REPORTES DE DASHBOARD ---
    public function dashboardGeneral()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $usuarioModel = new UsuarioModel();
        $vehiculoModel = new VehiculoModel();
        $servicioModel = new ServicioModel();
        $pedidoModel = new PedidoModel();
        $pagoModel = new PagoModel();

        // Usuarios
        $usuarios = ($this->rolId === 1)
            ? $usuarioModel->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, roles.nombre as rol_nombre, usuarios.creado_en')
                ->join('roles', 'roles.id = usuarios.rol_id', 'left')
                ->findAll()
            : $usuarioModel->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, roles.nombre as rol_nombre, usuarios.creado_en')
                ->join('roles', 'roles.id = usuarios.rol_id', 'left')
                ->where('usuarios.id', $this->userId)
                ->findAll();

        // Vehículos
        $vehiculosQuery = $vehiculoModel->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
            ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
            ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
            ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left');
        if ($this->rolId !== 1) {
            $vehiculosQuery->where('vehiculos.usuario_id', $this->userId);
        }
        $vehiculos = $vehiculosQuery->findAll();

        // Servicios (no dependen de usuario en tu modelo actual)
        $servicios = $servicioModel->findAll();

        // Pedidos
        $pedidosQuery = $pedidoModel
            ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre, usuarios.correo as usuario_correo')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');
        if ($this->rolId !== 1) {
            $pedidosQuery->where('pedidos.usuario_id', $this->userId);
        }
        $pedidos = $pedidosQuery->findAll();

        // Pagos (filtrar por usuario vía pedidos)
        $pagosQuery = $pagoModel
            ->select('pagos.*, pedidos.estado as pedido_estado, pedidos.total as pedido_total, vehiculos.placa, usuarios.nombre_usuario as cliente')
            ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left');
        if ($this->rolId !== 1) {
            $pagosQuery->where('pedidos.usuario_id', $this->userId);
        }
        $pagos = $pagosQuery->findAll();

        // Recolectar factores solo si es admin
        $factores_tipo = $factores_marca = $factores_antiguedad = [];
        if ($this->rolId === 1) {
            $factores_tipo = (new FactorTipoModel())->orderBy('tipo')->findAll();
            $factores_marca = (new FactorMarcaModel())->select('factores_marca.*, marcas.nombre as marca_nombre')
                ->join('marcas', 'marcas.id = factores_marca.marca_id', 'left')
                ->orderBy('marca_id')->findAll();
            $factores_antiguedad = (new FactorAntiguedadModel())->orderBy('anio_min')->findAll();
        }

        $data = [
            'titulo' => 'Reporte General - Todas las Tablas',
            'usuarios' => $usuarios,
            'vehiculos' => $vehiculos,
            'servicios' => $servicios,
            'pedidos' => $pedidos,
            'pagos' => $pagos,
            'factores_tipo' => $factores_tipo,
            'factores_marca' => $factores_marca,
            'factores_antiguedad' => $factores_antiguedad,
        ];

        // Además incluimos resumen rápido
        $data['resumen'] = [
            'usuarios' => count($usuarios),
            'vehiculos' => count($vehiculos),
            'servicios' => count($servicios),
            'pedidos' => count($pedidos),
            'pagos' => count($pagos),
        ];

        $this->pdf->generate('reportes/dashboard_general', $data, 'reporte_todas_las_tablas.pdf');
    }

    /**
     * Genera un reporte consolidado para el perfil del usuario actual.
     * Incluye datos de perfil, vehículos, pedidos y pagos.
     */
    public function miReporte()
    {
        if ($redirect = $this->checkAuth()) return $redirect;

        $usuarioModel = new UsuarioModel();
        $vehiculoModel = new VehiculoModel();
        $pedidoModel = new PedidoModel();
        $pagoModel = new PagoModel();

        // 1. Datos del Perfil (Solo el suyo)
        $data['usuarios'] = $usuarioModel->select('usuarios.id, usuarios.nombre_usuario, usuarios.correo, roles.nombre as rol_nombre, usuarios.creado_en')
            ->join('roles', 'roles.id = usuarios.rol_id', 'left')
            ->where('usuarios.id', $this->userId)
            ->findAll();

        // 2. Sus Vehículos
        $data['vehiculos'] = $vehiculoModel->select('vehiculos.*, usuarios.nombre_usuario as propietario, marcas.nombre as marca, modelos.nombre as modelo')
            ->join('usuarios', 'usuarios.id = vehiculos.usuario_id', 'left')
            ->join('marcas', 'marcas.id = vehiculos.marca_id', 'left')
            ->join('modelos', 'modelos.id = vehiculos.modelo_id', 'left')
            ->where('vehiculos.usuario_id', $this->userId)
            ->findAll();

        // 3. Sus Pedidos
        $data['pedidos'] = $pedidoModel
            ->select('pedidos.*, vehiculos.placa, usuarios.nombre_usuario as usuario_nombre')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->where('pedidos.usuario_id', $this->userId)
            ->findAll();

        // 4. Sus Pagos (filtrar por usuario vía pedidos)
        $data['pagos'] = $pagoModel
            ->select('pagos.*, pedidos.estado as pedido_estado, pedidos.total as pedido_total, vehiculos.placa, usuarios.nombre_usuario as cliente')
            ->join('pedidos', 'pedidos.id = pagos.pedido_id', 'left')
            ->join('vehiculos', 'vehiculos.id = pedidos.vehiculo_id', 'left')
            ->join('usuarios', 'usuarios.id = pedidos.usuario_id', 'left')
            ->where('pedidos.usuario_id', $this->userId)
            ->findAll();

        $data['titulo'] = 'Mi Resumen de Actividad - AutoMod Pro';
        $data['resumen'] = [
            'usuarios' => count($data['usuarios']),
            'vehiculos' => count($data['vehiculos']),
            'pedidos' => count($data['pedidos']),
            'pagos' => count($data['pagos'])
        ];

        $this->pdf->generate('reportes/dashboard_general', $data, 'mi_resumen_automod.pdf');
    }
}
