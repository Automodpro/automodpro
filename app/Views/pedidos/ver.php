<?php 
$p = $pedido ?? [];
$detalles = $detalles ?? [];
$fecha = $p['creado_en'] ?? ($p['created_at'] ?? null);

$estado = $p['estado'] ?? '';
$badge = $estado === 'pendiente'
    ? 'warning'
    : ($estado === 'completado'
        ? 'success'
        : ($estado === 'en_proceso'
            ? 'info'
            : 'dark'));

$detRows = '';
foreach ($detalles as $d) {
    $servicioNombre = $d['servicio_nombre'] ?? '';
    $precioUnitario = $d['precio_unitario'] ?? 0;

    $detRows .= '<tr>
        <td style="padding:10px 0;border-bottom:1px solid var(--gray-100);">' . $servicioNombre . '</td>
        <td style="padding:10px 0;text-align:right;font-weight:600;border-bottom:1px solid var(--gray-100);">$ ' . number_format((float) $precioUnitario, 0, ',', '.') . '</td>

    </tr>';
}

$content = '

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-receipt"></i> Pedido #' . $p['id'] . '</h2>
            <p>Detalle completo de la orden de servicio</p>
        </div>
        <a href="' . base_url('pedidos') . '" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

' . (session('rol') === 'admin' ? '<div style="margin-top:-10px;margin-bottom:14px;">
    <a href="' . base_url('pedidos/editar/' . ($p['id'] ?? '')) . '" class="btn btn-warning">
        <i class="bi bi-pencil"></i> Editar
    </a>
</div>' : '');

$content .= '

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 style="font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-info-circle" style="color:var(--primary-500);"></i> Información
                </h6>
                <div style="display:grid;gap:12px;font-size:var(--text-sm);">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Estado</span>
                        <span class="badge badge-' . $badge . '"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> ' . ucfirst($p['estado']) . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Total</span>
                        <span class="fw-bold" style="color:var(--primary-600);font-size:var(--text-xl);">$ ' . number_format($p['total'], 0, ',', '.') . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Fecha</span>
                        <span style="font-weight:500;">' . ($fecha ? date('d/m/Y H:i', strtotime($fecha)) : 'N/A') . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Cliente</span>
                        <span class="fw-semibold">' . $p['usuario_nombre'] . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Pedido realizado por</span>
                        <span class="fw-semibold">' . (session('nombre') ?? '') . '</span>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 style="font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-truck" style="color:var(--primary-500);"></i> Vehículo
                </h6>
                <div style="display:grid;gap:12px;font-size:var(--text-sm);">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Placa</span>
                        <span class="placa-badge">' . $p['placa'] . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Marca / Modelo</span>
                        <span class="fw-semibold">' . $p['marca'] . ' ' . $p['modelo'] . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--gray-500);">Tipo</span>
                        <span class="badge badge-dark">' . $p['tipo'] . '</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 style="font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-500);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-tools" style="color:var(--primary-500);"></i> Servicios incluidos
                </h6>
                <table style="width:100%;font-size:var(--text-sm);">
                    <thead>
                        <tr><th style="text-align:left;padding-bottom:8px;border-bottom:2px solid var(--gray-200);color:var(--gray-500);font-weight:600;">Servicio</th><th style="text-align:right;padding-bottom:8px;border-bottom:2px solid var(--gray-200);color:var(--gray-500);font-weight:600;">Precio</th></tr>
                    </thead>
                    <tbody>' . $detRows . '</tbody>
                    <tfoot>
                        <tr>
                            <td style="padding-top:12px;font-weight:700;font-size:var(--text-base);">Total</td>
                            <td style="padding-top:12px;text-align:right;font-weight:700;color:var(--primary-600);font-size:var(--text-lg);">$ ' . number_format($p['total'], 0, ',', '.') . '</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>';

echo view('layout/main', ['titulo' => 'Pedido #' . ($p['id'] ?? ''), 'content' => $content]);
?>
