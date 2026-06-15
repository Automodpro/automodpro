<?php
$factor = (isset($factor) && is_array($factor)) ? $factor : null;
$tipo = $tipo ?? 'tipo_vehiculo';
$esEdicion = $factor !== null;
$tipos_vehiculo = $tipos_vehiculo ?? ['Sedan', 'SUV', 'Camioneta', 'Deportivo', 'Hatchback'];
$marcas = $marcas ?? [];

$titulo = $esEdicion ? 'Editar Factor' : 'Nuevo Factor';
$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-percent"></i> ' . $titulo . '</h2>
            <p>Ajuste el multiplicador de precio para ' . str_replace('_', ' ', (string)$tipo) . '</p>
        </div>
    </div>
</div>';

if (session('error')) {
    $content .= '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' . esc(session('error')) . '</div>';
}

$action = ($esEdicion && isset($factor['id'])) ? base_url('factores-precio/actualizar/' . $factor['id']) : base_url('factores-precio/guardar');

$content .= '
<div class="card">
    <div class="card-body">
        <form action="' . $action . '" method="post">
            <input type="hidden" name="tipo_factor" value="' . esc($tipo) . '">';

if ($tipo === 'tipo_vehiculo') {
    // Factores globales por tipo (tabla: factores_tipo)
    $selectedTipo = $esEdicion ? ($factor['tipo'] ?? '') : '';
    $factorValue = $esEdicion ? ($factor['factor'] ?? 1.0) : 1.0;

    $content .= '
            <div class="form-group mb-3">
                <label class="form-label">Tipo de Vehículo <span class="required">*</span></label>
                <select name="tipo_vehiculo" class="form-select" required>';

    foreach ($tipos_vehiculo as $tv) {
        $sel = ($tv === $selectedTipo) ? ' selected' : '';
        $content .= '<option value="' . esc($tv) . '"' . $sel . '>' . esc($tv) . '</option>';
    }

    $content .= '
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Factor (multiplicador) <span class="required">*</span></label>
                <input type="number" step="0.01" min="0" name="factor" class="form-control" value="' . $factorValue . '" required>
                <small class="text-muted">Ej: 1.20 = +20%, 0.90 = -10%, 1.00 = sin cambio</small>
            </div>';
} elseif ($tipo === 'marca') {
    $marcas = $marcas ?? [];
    $selectedMarca = $esEdicion ? ($factor['marca_id'] ?? 0) : 0;
    $factorValue = $esEdicion ? ($factor['factor'] ?? 1.0) : 1.0;

    $content .= '
            <div class="form-group mb-3">
                <label class="form-label">Marca <span class="required">*</span></label>
                <select name="marca_id" class="form-select" required>
                    <option value="">Seleccione una marca</option>';

    foreach ($marcas as $m) {
        $sel = ((int)$m['id'] === (int)$selectedMarca) ? ' selected' : '';
        $content .= '<option value="' . $m['id'] . '"' . $sel . '>' . esc($m['nombre']) . '</option>';
    }

    $content .= '
                </select>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Factor (multiplicador) <span class="required">*</span></label>
                <input type="number" step="0.01" min="0" name="factor" class="form-control" value="' . $factorValue . '" required>
                <small class="text-muted">Ej: 1.25 = +25%, 0.95 = -5%</small>
            </div>';
} elseif ($tipo === 'antiguedad') {
    $anioMin = $esEdicion ? ($factor['anio_min'] ?? date('Y')) : date('Y');
    $anioMax = $esEdicion ? ($factor['anio_max'] ?? date('Y')) : date('Y');
    $factorValue = $esEdicion ? ($factor['factor'] ?? 1.0) : 1.0;

    $content .= '
            <div class="form-group mb-3">
                <label class="form-label">Año mínimo <span class="required">*</span></label>
                <input type="number" min="1900" max="2100" name="anio_min" class="form-control" value="' . $anioMin . '" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Año máximo <span class="required">*</span></label>
                <input type="number" min="1900" max="2100" name="anio_max" class="form-control" value="' . $anioMax . '" required>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">Factor (multiplicador) <span class="required">*</span></label>
                <input type="number" step="0.01" min="0" name="factor" class="form-control" value="' . $factorValue . '" required>
                <small class="text-muted">Ej: 1.20 = +20%, 0.90 = -10%</small>
            </div>';
}

$content .= '
            <hr>
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
            <a href="' . base_url('factores-precio') . '" class="btn btn-outline-secondary">Cancelar</a>
        </form>
    </div>
</div>';

echo view('layout/main', ['titulo' => $titulo, 'content' => $content]);
