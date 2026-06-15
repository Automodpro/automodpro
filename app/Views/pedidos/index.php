<?php
$pedidos = $pedidos ?? [];
$isAdmin = session('rol') === 'admin';

$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-cart-check"></i> Pedidos</h2>
            <p>Historial de solicitudes de servicios</p>
        </div>
        <div class="d-flex gap-2">
            <a href="' . base_url('reportes/pedidosGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">
                <i class="bi bi-file-earmark-pdf"></i> Reporte PDF
            </a>
            <a href="' . base_url('pedidos/crear') . '" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo pedido
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table datatable" style="width:100%">
            <thead>
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-calendar"></i> Fecha</th>
                    <th><i class="bi bi-truck"></i> Vehículo</th>
                    <th><i class="bi bi-person"></i> Cliente</th>
                    <th><i class="bi bi-currency-dollar"></i> Total</th>
                    <th><i class="bi bi-info-circle"></i> Estado</th>
                    <th style="width:110px"><i class="bi bi-gear"></i> Acción</th>
                </tr>
            </thead>
            <tbody>';

foreach ($pedidos as $p) {
    $estadoBadge = match($p['estado']) {
        'pendiente' => 'warning',
        'aprobado'  => 'info',
        'en_proceso'=> 'primary',
        'completado'=> 'success',
        'cancelado' => 'danger',
        default     => 'secondary'
    };

    $content .= '<tr>
        <td class="fw-semibold" style="color:var(--primary-600);">' . $p['id'] . '</td>
        <td>' . (isset($p['creado_en']) ? date('d/m/Y H:i', strtotime($p['creado_en'])) : 'N/A') . '</td>
        <td><span class="placa-badge">' . esc($p['placa']) . '</span></td>
        <td>' . esc($p['usuario_nombre']) . '</td>
        <td class="fw-semibold">$ ' . number_format((float)($p['total'] ?? 0), 0, ',', '.') . '</td>
        <td><span class="badge badge-' . $estadoBadge . '">' . ucfirst($p['estado']) . '</span></td>
        <td>
            <div class="d-flex gap-1">
                <a href="' . base_url('reportes/pedidoDetalle/' . $p['id']) . '" class="btn btn-outline-danger btn-sm" title="PDF del pedido" target="_blank" rel="noopener">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
                <a href="' . base_url('pedidos/ver/' . $p['id']) . '" class="btn btn-info btn-sm" title="Ver Detalles"><i class="bi bi-eye"></i></a>';
    
    if ($isAdmin) {
        $content .= '
                <a href="' . base_url('pedidos/editar/' . $p['id']) . '" class="btn btn-warning btn-sm" title="Editar Estado"><i class="bi bi-pencil"></i></a>
                <a href="' . base_url('pedidos/eliminar/' . $p['id']) . '" class="btn btn-danger btn-sm" data-confirm="¿Eliminar este pedido?" title="Eliminar"><i class="bi bi-trash"></i></a>';
    }

    $content .= '
            </div>
        </td>
    </tr>';
}

$content .= '     </tbody>
        </table>
    </div>
</div>';

echo view('layout/main', ['titulo' => 'Pedidos', 'content' => $content]);
?>