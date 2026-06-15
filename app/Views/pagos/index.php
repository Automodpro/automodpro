<?php
$pagos = $pagos ?? [];
$isAdmin = session('rol') === 'admin';

$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-cash-stack"></i> Pagos</h2>
            <p>Registro de transacciones financieras</p>
        </div>
        <div class="d-flex gap-2">
            <a href="' . base_url('reportes/pagosGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">
                <i class="bi bi-file-earmark-pdf"></i> Reporte PDF
            </a>
            ' . (session('rol') !== 'usuario' ? '<a href="' . base_url('pagos/crear') . '" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Registrar Pago</a>' : '') . '
        </div>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table datatable" style="width:100%">
            <thead>
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-calendar-event"></i> Fecha</th>
                    <th><i class="bi bi-person"></i> Cliente</th>
                    <th><i class="bi bi-truck"></i> Vehículo</th>
                    <th><i class="bi bi-currency-dollar"></i> Monto</th>
                    <th><i class="bi bi-credit-card"></i> Método</th>
                    <th style="width:110px"><i class="bi bi-gear"></i> Acción</th>
                </tr>
            </thead>
            <tbody>';

foreach ($pagos as $p) {
    $content .= '<tr>
        <td class="fw-semibold" style="color:var(--primary-600);">' . $p['id'] . '</td>
        <td>' . date('d/m/Y H:i', strtotime($p['fecha_pago'])) . '</td>
        <td>' . esc($p['usuario_nombre']) . '</td>
        <td><span class="placa-badge">' . esc($p['placa']) . '</span></td>
        <td class="fw-bold text-success">$ ' . number_format((float)$p['monto'], 0, ',', '.') . '</td>
        <td>
            <span class="badge badge-light text-dark">
                <i class="bi bi-wallet2"></i> ' . ucfirst($p['metodo_pago']) . '
            </span>
        </td>
        <td>
            <div class="d-flex gap-1">
                <a href="' . base_url('reportes/pagoDetalle/' . $p['id']) . '" class="btn btn-outline-danger btn-sm" title="Comprobante PDF" target="_blank" rel="noopener">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>';
    
    if ($isAdmin) {
        $content .= '<a href="' . base_url('pagos/eliminar/' . $p['id']) . '" class="btn btn-danger btn-sm" data-confirm="¿Eliminar este registro de pago?" title="Eliminar"><i class="bi bi-trash"></i></a>';
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

echo view('layout/main', ['titulo' => 'Pagos', 'content' => $content]);
?>