<?php
/*
 * Vista para reporte PDF de Factores de Precio.
 * Variables esperadas:
 * - $titulo
 * - $data: array con llaves: tipos, marcas, antiguedad (opcionales)
 */

$titulo = $titulo ?? 'Factores de Precio';
$tipos = $tipos ?? [];
$marcas = $marcas ?? [];
$antiguedad = $antiguedad ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 10px 0; }
        .meta { margin-bottom: 10px; font-size: 10px; color: #333; }
        .section-title { margin: 14px 0 8px 0; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #ddd; padding: 6px 6px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        .muted { color: #666; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>

    <div class="meta">
        <div><span class="muted">Empresa:</span> <?= esc($empresa ?? '') ?></div>
        <div><span class="muted">Fecha:</span> <?= esc($fecha_generacion ?? '') ?></div>
        <div><span class="muted">Generado por:</span> <?= esc($generado_por ?? '') ?></div>
    </div>

    <div class="section-title">Factores por Tipo de Vehículo</div>
    <table>
        <thead>
        <tr>
            <th>Tipo</th>
            <th>Factor</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($tipos)): ?>
            <?php foreach ($tipos as $f): ?>
                <tr>
                    <td><?= esc((string)($f['tipo'] ?? '')) ?></td>
                    <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">Factores por Marca</div>
    <table>
        <thead>
        <tr>
            <th>Marca</th>
            <th>Factor</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($marcas)): ?>
            <?php foreach ($marcas as $f): ?>
                <tr>
                    <td><?= esc((string)($f['marca_nombre'] ?? $f['marca_id'] ?? '')) ?></td>
                    <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">Factores por Antigüedad</div>
    <table>
        <thead>
        <tr>
            <th>Año mínimo</th>
            <th>Año máximo</th>
            <th>Factor</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($antiguedad)): ?>
            <?php foreach ($antiguedad as $f): ?>
                <tr>
                    <td><?= esc((string)($f['anio_min'] ?? '')) ?></td>
                    <td><?= esc((string)($f['anio_max'] ?? '')) ?></td>
                    <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
