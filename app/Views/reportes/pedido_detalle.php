<?php
/*
 * Vista de detalle de pedido para reportes en PDF.
 * Variables esperadas:
 * - $titulo
 * - $pedido (array)
 * - $detalles (array)
 */

$titulo = $titulo ?? 'Pedido';
$pedido = $pedido ?? [];
$detalles = $detalles ?? [];
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
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 6px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        .grid { margin-top: 8px; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>

    <div class="meta">
        <div><span style="color:#666">Empresa:</span> <?= esc($empresa ?? '') ?></div>
        <div><span style="color:#666">Fecha:</span> <?= esc($fecha_generacion ?? '') ?></div>
        <div><span style="color:#666">Generado por:</span> <?= esc($generado_por ?? '') ?></div>
    </div>

    <div class="section-title">Información del pedido</div>
    <table>
        <tbody>
            <?php
                // Mostramos campos comunes si existen
                $rows = [
                    'ID' => $pedido['id'] ?? ($pedido['pedido_id'] ?? ''),
                    'Cliente' => $pedido['nombre_usuario'] ?? ($pedido['cliente'] ?? ''),
                    'Vehículo' => $pedido['placa'] ?? ($pedido['vehiculo'] ?? ''),
                    'Estado' => $pedido['estado'] ?? '',
                    'Total' => $pedido['total'] ?? ''
                ];
            ?>
            <?php foreach ($rows as $label => $value): ?>
                <tr>
                    <td style="width:35%; background:#f2f2f2; font-weight:bold;"><?= esc((string)$label) ?></td>
                    <td><?= esc((string)$value) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="section-title">Servicios</div>
    <table>
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Precio unitario</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($detalles)): ?>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?= esc((string)($d['servicio_nombre'] ?? $d['servicio'] ?? '')) ?></td>
                        <td><?= esc((string)($d['precio_unitario'] ?? $d['precio'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="muted">Sin detalles</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

