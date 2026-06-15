<?php
/*
 * Vista para reporte PDF del Dashboard.
 * Variables esperadas:
 * - $titulo
 * - $resumen: array con counts
 * - $pedidos_recientes: array
 */

$titulo = $titulo ?? 'Dashboard';
$resumen = $resumen ?? [];
$pedidos_recientes = $pedidos_recientes ?? [];
$factores_tipo = $factores_tipo ?? [];
$factores_marca = $factores_marca ?? [];
$factores_antiguedad = $factores_antiguedad ?? [];
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
        .grid { display: grid; gap: 10px; }
        .card { border: 1px solid #ddd; padding: 10px; }
        .card .label { font-size: 11px; color: #666; margin-bottom: 6px; }
        .card .value { font-size: 16px; font-weight: bold; }
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

    <div class="section-title">Resumen</div>
    <div class="grid" style="grid-template-columns: repeat(<?= count($resumen) ?>, 1fr);">
        <?php if(isset($resumen['usuarios'])): ?>
            <div class="card"><div class="label">Usuarios</div><div class="value"><?= esc((string)$resumen['usuarios']) ?></div></div>
        <?php endif; ?>
        <?php if(isset($resumen['vehiculos'])): ?>
            <div class="card"><div class="label">Vehículos</div><div class="value"><?= esc((string)$resumen['vehiculos']) ?></div></div>
        <?php endif; ?>
        <?php if(isset($resumen['servicios'])): ?>
            <div class="card"><div class="label">Servicios</div><div class="value"><?= esc((string)$resumen['servicios']) ?></div></div>
        <?php endif; ?>
        <?php if(isset($resumen['pedidos'])): ?>
            <div class="card"><div class="label">Pedidos</div><div class="value"><?= esc((string)$resumen['pedidos']) ?></div></div>
        <?php endif; ?>
        <?php if(isset($resumen['pagos'])): ?>
            <div class="card"><div class="label">Pagos</div><div class="value"><?= esc((string)$resumen['pagos']) ?></div></div>
        <?php endif; ?>
    </div>

    <div class="section-title">Todas las tablas (filtradas por el usuario logueado)</div>

    <div class="section-title">Usuarios</div>
    <table>
        <thead><tr><th>ID</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Registro</th></tr></thead>
        <tbody>
        <?php foreach (($usuarios ?? []) as $u): ?>
            <tr>
                <td><?= esc((string)($u['id'] ?? '')) ?></td>
                <td><?= esc((string)($u['nombre_usuario'] ?? '')) ?></td>
                <td><?= esc((string)($u['correo'] ?? '')) ?></td>
                <td><?= esc((string)($u['rol_nombre'] ?? '')) ?></td>
                <td><?= esc((string)($u['creado_en'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($usuarios)): ?>
            <tr><td colspan="5" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">Vehículos</div>
    <table>
        <thead><tr><th>ID</th><th>Placa</th><th>Marca</th><th>Modelo</th><th>Tipo</th><th>Año</th><th>Propietario</th></tr></thead>
        <tbody>
        <?php foreach (($vehiculos ?? []) as $v): ?>
            <tr>
                <td><?= esc((string)($v['id'] ?? '')) ?></td>
                <td><?= esc((string)($v['placa'] ?? '')) ?></td>
                <td><?= esc((string)($v['marca'] ?? '')) ?></td>
                <td><?= esc((string)($v['modelo'] ?? '')) ?></td>
                <td><?= esc((string)($v['tipo'] ?? '')) ?></td>
                <td><?= esc((string)($v['año'] ?? ($v['anio'] ?? ''))) ?></td>
                <td><?= esc((string)($v['propietario'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($vehiculos)): ?>
            <tr><td colspan="7" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($servicios)): ?>
    <div class="section-title">Servicios</div>
    <table>
        <thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th></tr></thead>
        <tbody>
        <?php foreach (($servicios ?? []) as $s): ?>
            <tr>
                <td><?= esc((string)($s['id'] ?? '')) ?></td>
                <td><?= esc((string)($s['nombre'] ?? '')) ?></td>
                <td><?= esc((string)($s['descripcion'] ?? '')) ?></td>
                <td><?= esc((string)($s['precio'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($servicios)): ?>
            <tr><td colspan="4" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="section-title">Pedidos</div>
    <table>
        <thead><tr><th>ID</th><th>Vehículo</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead>
        <tbody>
        <?php foreach (($pedidos ?? []) as $p): ?>
            <tr>
                <td><?= esc((string)($p['id'] ?? '')) ?></td>
                <td><?= esc((string)($p['placa'] ?? '')) ?></td>
                <td><?= esc((string)($p['usuario_nombre'] ?? '')) ?></td>
                <td><?= esc((string)($p['total'] ?? '')) ?></td>
                <td><?= esc((string)($p['estado'] ?? '')) ?></td>
                <td><?= esc((string)($p['creado_en'] ?? ($p['created_at'] ?? ''))) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($pedidos)): ?>
            <tr><td colspan="6" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="section-title">Pagos</div>
    <table>
        <thead><tr><th>ID</th><th>Pedido</th><th>Cliente</th><th>Placa</th><th>Monto</th><th>Método</th><th>Estado</th><th>Fecha</th></tr></thead>
        <tbody>
        <?php foreach (($pagos ?? []) as $g): ?>
            <tr>
                <td><?= esc((string)($g['id'] ?? '')) ?></td>
                <td><?= esc((string)($g['pedido_id'] ?? '')) ?></td>
                <td><?= esc((string)($g['cliente'] ?? ($g['usuario_nombre'] ?? ''))) ?></td>
                <td><?= esc((string)($g['placa'] ?? '')) ?></td>
                <td><?= esc((string)($g['monto'] ?? '')) ?></td>
                <td><?= esc((string)($g['metodo_pago'] ?? '')) ?></td>
                <td><?= esc((string)($g['estado'] ?? '')) ?></td>
                <td><?= esc((string)($g['fecha_pago'] ?? ($g['creado_en'] ?? ''))) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($pagos)): ?>
            <tr><td colspan="8" class="muted">Sin registros</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($factores_tipo)): ?>
    <div class="section-title">Factores por Tipo de Vehículo</div>
    <table>
        <thead>
        <tr>
            <th>Tipo</th>
            <th>Factor</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($factores_tipo as $f): ?>
            <tr>
                <td><?= esc((string)($f['tipo'] ?? '')) ?></td>
                <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($factores_marca)): ?>
    <div class="section-title">Factores por Marca</div>
    <table>
        <thead>
        <tr>
            <th>Marca</th>
            <th>Factor</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($factores_marca as $f): ?>
            <tr>
                <td><?= esc((string)($f['marca_nombre'] ?? $f['marca_id'] ?? '')) ?></td>
                <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <?php if (!empty($factores_antiguedad)): ?>
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
        <?php foreach ($factores_antiguedad as $f): ?>
            <tr>
                <td><?= esc((string)($f['anio_min'] ?? '')) ?></td>
                <td><?= esc((string)($f['anio_max'] ?? '')) ?></td>
                <td class="right"><?= esc((string)($f['factor'] ?? '')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</body>
</html>
