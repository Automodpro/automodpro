<?php
$isAdmin = (int)session('rol_id') === 1;
$factores_tipo = $factores_tipo ?? [];
$factores_marca = $factores_marca ?? [];
$factores_antiguedad = $factores_antiguedad ?? [];

$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-percent"></i> Factores de Precio</h2>
            <p>Administre los multiplicadores de precio según Marca, Tipo y Año</p>
        </div>
        ' . ($isAdmin ? '
            <a href="' . base_url('reportes/factoresPrecioGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">
                <i class="bi bi-file-earmark-pdf"></i> Reporte General PDF
            </a>
        ' : '') . '
    </div>
</div>';

if (session('error')) {
    $content .= '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' . esc(session('error')) . '</div>';
}
if (session('success')) {
    $content .= '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' . esc(session('success')) . '</div>';
}

// --- FACTORES POR TIPO DE VEHÍCULO ---
$content .= '
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="mb-0"><i class="bi bi-truck" style="color:var(--primary-500);"></i> Factores por Tipo de Vehículo</h5>
            <div class="d-flex gap-2">
                ' . ($isAdmin ? '
                    <a href="' . base_url('reportes/factoresTipo') . '" class="btn btn-sm btn-outline-danger" target="_blank" rel="noopener">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="' . base_url('factores-precio/crear?tipo=tipo_vehiculo') . '" class="btn btn-sm btn-primary px-3"><i class="bi bi-plus-lg"></i> Nuevo</a>
                ' : '') . '
            </div>
        </div>
    </div>
    <div class="table-container" style="' . (!$isAdmin ? 'user-select: none; -webkit-user-select: none;' : '') . '">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th><i class="bi bi-grid-3x3"></i> Tipo</th>
                        <th><i class="bi bi-graph-up"></i> Factor</th>
                        ' . ($isAdmin ? '<th style="width:110px" class="text-end">Acciones</th>' : '') . '
                    </tr>
                </thead>
                <tbody>';

if (empty($factores_tipo)) {
    $content .= '<tr><td colspan="3" class="text-muted text-center">Sin factores configurados</td></tr>';
} else {
    foreach ($factores_tipo as $f) {
        $content .= '<tr>
            <td class="fw-semibold">' . esc($f['tipo'] ?? '') . '</td>
            <td class="fw-bold text-primary">' . number_format((float)$f['factor'], 2) . 'x</td>
            ' . ($isAdmin ? '<td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="' . base_url('factores-precio/editar/' . $f['id'] . '?tipo=tipo_vehiculo') . '" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                    <a href="' . base_url('factores-precio/eliminar/' . $f['id'] . '?tipo=tipo_vehiculo') . '" class="btn btn-danger" onclick="return confirm(\'¿Eliminar este factor?\')"><i class="bi bi-trash"></i></a>
                </div>
            </td>' : '') . '
        </tr>';
    }
}

$content .= '
                </tbody>
            </table>
        </div>
    </div>
</div>';


// --- FACTORES POR MARCA ---
$content .= '
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="mb-0"><i class="bi bi-tag" style="color:var(--primary-500);"></i> Factores por Marca</h5>
            <div class="d-flex gap-2">
                ' . ($isAdmin ? '
                    <a href="' . base_url('reportes/factoresMarca') . '" class="btn btn-sm btn-outline-danger" target="_blank" rel="noopener">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="' . base_url('factores-precio/crear?tipo=marca') . '" class="btn btn-sm btn-primary px-3"><i class="bi bi-plus-lg"></i> Nuevo</a>
                ' : '') . '
            </div>
        </div>
    </div>
    <div class="table-container" style="' . (!$isAdmin ? 'user-select: none; -webkit-user-select: none;' : '') . '">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th><i class="bi bi-building"></i> Marca</th>
                        <th><i class="bi bi-graph-up"></i> Factor</th>
                        ' . ($isAdmin ? '<th style="width:110px" class="text-end">Acciones</th>' : '') . '
                    </tr>
                </thead>
                <tbody>';

if (empty($factores_marca)) {
    $content .= '<tr><td colspan="3" class="text-muted text-center">Sin factores configurados</td></tr>';
} else {
    foreach ($factores_marca as $f) {
        $content .= '<tr>
            <td class="fw-semibold">' . esc($f['marca_nombre'] ?? 'Marca #' . $f['marca_id']) . '</td>
            <td class="fw-bold text-primary">' . number_format((float)$f['factor'], 2) . 'x</td>
            ' . ($isAdmin ? '<td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="' . base_url('factores-precio/editar/' . $f['id'] . '?tipo=marca') . '" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                    <a href="' . base_url('factores-precio/eliminar/' . $f['id'] . '?tipo=marca') . '" class="btn btn-danger" onclick="return confirm(\'¿Eliminar este factor?\')"><i class="bi bi-trash"></i></a>
                </div>
            </td>' : '') . '
        </tr>';
    }
}

$content .= '
                </tbody>
            </table>
        </div>
    </div>
</div>';

// --- FACTORES POR ANTIGÜEDAD ---
$content .= '
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h5 class="mb-0"><i class="bi bi-calendar-event" style="color:var(--primary-500);"></i> Factores por Año (Antigüedad)</h5>
            <div class="d-flex gap-2">
                ' . ($isAdmin ? '
                    <a href="' . base_url('reportes/factoresAntiguedad') . '" class="btn btn-sm btn-outline-danger" target="_blank" rel="noopener">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="' . base_url('factores-precio/crear?tipo=antiguedad') . '" class="btn btn-sm btn-primary px-3"><i class="bi bi-plus-lg"></i> Nuevo</a>
                ' : '') . '
            </div>
        </div>
    </div>
    <div class="table-container" style="' . (!$isAdmin ? 'user-select: none; -webkit-user-select: none;' : '') . '">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar-minus"></i> Desde (Año)</th>
                        <th><i class="bi bi-calendar-plus"></i> Hasta (Año)</th>
                        <th><i class="bi bi-graph-up"></i> Factor</th>
                        ' . ($isAdmin ? '<th style="width:110px" class="text-end">Acciones</th>' : '') . '
                    </tr>
                </thead>
                <tbody>';

if (empty($factores_antiguedad)) {
    $content .= '<tr><td colspan="4" class="text-muted text-center">Sin factores configurados</td></tr>';
} else {
    foreach ($factores_antiguedad as $f) {
        $content .= '<tr>
            <td>' . (int)$f['anio_min'] . '</td>
            <td>' . (int)$f['anio_max'] . '</td>
            <td class="fw-bold text-primary">' . number_format((float)$f['factor'], 2) . 'x</td>
            ' . ($isAdmin ? '<td class="text-end">
                <div class="btn-group btn-group-sm">
                    <a href="' . base_url('factores-precio/editar/' . $f['id'] . '?tipo=antiguedad') . '" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                    <a href="' . base_url('factores-precio/eliminar/' . $f['id'] . '?tipo=antiguedad') . '" class="btn btn-danger" onclick="return confirm(\'¿Eliminar este factor?\')"><i class="bi bi-trash"></i></a>
                </div>
            </td>' : '') . '
        </tr>';
    }
}

$content .= '
                </tbody>
            </table>
        </div>
    </div>
</div>';

echo view('layout/main', ['titulo' => 'Factores de Precio', 'content' => $content]);