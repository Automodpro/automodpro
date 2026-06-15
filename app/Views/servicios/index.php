<?php
$canEdit = session('rol') !== 'usuario';
$servicios = $servicios ?? [];
$content = '

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-tools"></i> Servicios</h2>
            <p>Catalogo de servicios automotrices disponibles</p>
        </div>
        ' . ($canEdit
            ? '<div class="d-flex gap-2 flex-wrap">'
                . '<a href="' . base_url('reportes/serviciosGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">'
                . '<i class="bi bi-file-earmark-pdf"></i> Reporte PDF</a>'
                . '<a href="' . base_url('servicios/crear') . '" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Nuevo servicio</a>'
                . '<a href="' . base_url('pedidos/crear') . '" class="btn btn-success"><i class="bi bi-cart-plus"></i> Solicitar servicio</a>'
                . '</div>'
            : '<div class="d-flex gap-2"><a href="' . base_url('reportes/serviciosGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf"></i> Reporte PDF</a>'
                . '<a href="' . base_url('pedidos/crear') . '" class="btn btn-success"><i class="bi bi-cart-plus"></i> Solicitar servicio</a></div>') . '
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table datatable" style="width:100%">
            <thead>
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-tag"></i> Nombre</th>
                    <th><i class="bi bi-file-text"></i> Descripcion</th>
                    <th><i class="bi bi-currency-dollar"></i> Precio base</th>
                    <th style="width:1%; white-space:nowrap;"><i class="bi bi-gear"></i> Accion</th>
                </tr>
            </thead>
            <tbody>';

foreach ($servicios as $s) {
    $content .= '<tr>
        <td class="fw-semibold" style="color:var(--primary-600);">' . esc($s['id']) . '</td>
        <td class="fw-semibold">' . esc($s['nombre']) . '</td>
        <td style="color:var(--gray-500);max-width:300px;">' . esc($s['descripcion'] ?? "") . '</td>
        <td class="fw-semibold">$ ' . number_format((float) ($s['precio'] ?? 0), 0, ',', '.') . ' COP</td>
        <td>
            <div class="d-flex gap-1 flex-nowrap">';

    $content .= '
                <a href="' . base_url('reportes/servicioDetalle/' . $s['id']) . '" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener" title="PDF del servicio"><i class="bi bi-file-earmark-pdf"></i></a>';

    if ($canEdit) {
        $content .= '
                <a href="' . base_url('servicios/editar/' . $s['id']) . '" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                <a href="' . base_url('servicios/eliminar/' . $s['id']) . '" class="btn btn-danger btn-sm" data-confirm="Eliminar servicio?" title="Eliminar"><i class="bi bi-trash"></i></a>';
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

echo view('layout/main', ['titulo' => 'Servicios', 'content' => $content]);
?>
