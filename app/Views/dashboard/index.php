<?php 
$rol = (string) (session('rol') ?? '');
$rolId = (int) (session('rol_id') ?? 0);

// Toma la descripción del cargo en base al rol del usuario.
// Preferimos rol_id (más confiable), pero mantenemos compatibilidad con rol(slug).
// CI4 guarda rol en `rol` como slug (admin/mecanico/usuario) y en `rol_id` como número.
// Tu BD usa: 1=Administrador, 2=Mecánico, 3=Usuario.
$rolName = match ($rolId) {
    1 => 'Administrador',
    2 => 'Mecánico',
    3 => 'Usuario',
    default => ($rol === 'admin' ? 'Administrador' : ($rol === 'mecanico' ? 'Mecánico' : 'Usuario')),
};

// Si por cualquier razón no existe `rol_id` en la sesión, intentamos obtenerlo desde el slug.
if ($rolId === 0 && $rol !== '') {
    $rolId = ($rol === 'admin') ? 1 : (($rol === 'mecanico') ? 2 : 3);
    $rolName = ($rolId === 1) ? 'Administrador' : (($rolId === 2) ? 'Mecánico' : 'Usuario');
}

$inicial = strtoupper(substr(session('nombre'), 0, 1));

$content = '
<div class="page-header">
    <h2><i class="bi bi-speedometer2"></i> Dashboard</h2>
    <p>Bienvenido, <strong>' . session("nombre") . '</strong> · <span style="color:var(--gray-400);">' . $rolName . '</span></p>
</div>

';
// Por seguridad: si el controlador no envía estos datos, evitamos warnings/errores.
$vehiculos_count = $vehiculos_count ?? 0;
$servicios_count = $servicios_count ?? 0;
$pedidos_count = $pedidos_count ?? 0;
$pagos_count = $pagos_count ?? 0;
$usuarios_count = $usuarios_count ?? 0;
$pedidos_recientes = $pedidos_recientes ?? [];

// Definición de módulos con el orden del sidebar, colores y restricciones
$stats = [
    ['icon' => 'people', 'label' => 'Usuarios', 'count' => $usuarios_count, 'class' => 'usuarios', 'roles' => [1], 'color' => '#dc2626'],
    ['icon' => 'truck', 'label' => 'Vehículos', 'count' => $vehiculos_count, 'class' => 'vehiculos', 'roles' => [1, 2, 3], 'color' => '#2563eb'],
    ['icon' => 'tools', 'label' => 'Servicios', 'count' => $servicios_count, 'class' => 'servicios', 'roles' => [1, 2, 3], 'color' => '#059669'],
    ['icon' => 'cart-check', 'label' => 'Pedidos', 'count' => $pedidos_count, 'class' => 'pedidos', 'roles' => [1, 2, 3], 'color' => '#4f46e5'],
    ['icon' => 'credit-card', 'label' => 'Pagos', 'count' => $pagos_count, 'class' => 'pagos', 'roles' => [1, 2, 3], 'color' => '#ea580c'] 
];

// Filtrar stats según el rol del usuario
$visibleStats = array_filter($stats, function($s) use ($rolId) {
    return in_array($rolId, $s['roles']);
});

// Contenedor con grid dinámico para que quepan en una fila (columnas verticales)
$content .= '<div class="grid-' . count($visibleStats) . ' mb-5" style="display: grid; grid-template-columns: repeat(' . count($visibleStats) . ', 1fr); gap: 1.5rem;">';

foreach ($visibleStats as $s) {
    $url = '#';
    if ($s['class'] === 'usuarios') {
        $url = base_url('usuarios');
    } elseif ($s['class'] === 'vehiculos') {
        $url = base_url('vehiculos');
    } elseif ($s['class'] === 'servicios') {
        $url = base_url('servicios');
    } elseif ($s['class'] === 'pedidos') {
        $url = base_url('pedidos');
    } elseif ($s['class'] === 'pagos') {
        $url = base_url('pagos');
    }

    $content .= '
    <a href="' . $url . '" class="card stat-card text-decoration-none" style="overflow: hidden; border: none; transition: all 0.3s ease; box-shadow: 0 10px 15px -3px ' . $s['color'] . '20;">
        <div class="stat-bg" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: ' . $s['color'] . '; opacity: 0.25; z-index: 1;"></div>
        <div class="stat-body" style="position: relative; z-index: 2; padding: 1.25rem;">
            <div class="stat-icon-wrap" style="background-color: ' . $s['color'] . '; color: white; width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.75rem; box-shadow: 0 4px 12px ' . $s['color'] . '60;">
                <i class="bi bi-' . $s["icon"] . '" style="-webkit-text-stroke: 1.5px currentColor;"></i>
            </div>
            <div class="stat-info">
                <h6 style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: var(--gray-700); margin-bottom: 0.25rem;">' . $s["label"] . '</h6>
                <h3 style="font-weight: 900; font-size: 2rem; margin-bottom: 0; color: #000;">' . $s["count"] . '</h3>
                <div class="stat-trend" style="font-size: 0.75rem; color: ' . $s['color'] . '; font-weight: 800; margin-top: 0.6rem;">
                    <i class="bi bi-arrow-up-short"></i> Registrados
                </div>
            </div>
        </div>
    </a>';
}
$content .= '
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-clock-history" style="color:var(--primary-500);font-size:1.1rem;"></i>
        <h5>Pedidos recientes</h5>
    </div>
    <div class="table-container">
        <table class="table datatable" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vehículo</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>';
foreach ($pedidos_recientes as $p) {
    $badge = $p['estado'] === 'pendiente' ? 'warning' : ($p['estado'] === 'completado' ? 'success' : ($p['estado'] === 'en_proceso' ? 'info' : 'dark'));
    $fecha = $p['creado_en'] ?? ($p['created_at'] ?? null);
    $content .= '<tr>
        <td class="fw-bold">#' . $p['id'] . '</td>
        <td><span class="placa-badge">' . ($p['placa'] ?? "N/A") . '</span></td>
        <td>' . ($p['usuario_nombre'] ?? "N/A") . '</td>
        <td class="fw-bold">$ ' . number_format($p['total'], 0, ',', '.') . '</td>
        <td><span class="badge badge-' . $badge . '"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> ' . ucfirst($p['estado']) . '</span></td>
        <td>' . ($fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A') . '</td>
    </tr>';
}
$content .= '     </tbody>
            </table>
        </div>
    </div>';

$content = '<div class="d-flex justify-content-end" style="margin-bottom:16px;">
    <a href="' . base_url('reportes/dashboardGeneral') . '" class="btn btn-primary" target="_blank" rel="noopener">
        <i class="bi bi-printer"></i> Generar PDF (Dashboard + Todas las Tablas)
    </a>
</div>' . $content;

echo view('layout/main', ['titulo' => 'Dashboard', 'content' => $content]);

?>
