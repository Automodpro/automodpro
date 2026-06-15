<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        .header { border-bottom: 2px solid #dc2626; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 22px; font-weight: bold; color: #dc2626; }
        .report-title { font-size: 16px; text-transform: uppercase; margin-top: 5px; font-weight: bold; }
        .meta-info { float: right; text-align: right; font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; }
        th { background-color: #f3f4f6; color: #1f2937; padding: 8px; border: 1px solid #d1d5db; text-align: left; font-size: 10px; }
        td { padding: 6px; border: 1px solid #d1d5db; word-wrap: break-word; vertical-align: top; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { position: fixed; bottom: -20px; width: 100%; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 5px; }
        .resumen { margin-top: 20px; padding: 10px; background: #eff6ff; border-left: 4px solid #2563eb; font-weight: bold; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="meta-info">
            Fecha: <?= esc((string)($fecha_generacion ?? '')) ?><br>
            Generado por: <?= esc((string)($generado_por ?? 'Sistema')) ?>
        </div>
        <div class="company-name"><?= esc((string)($empresa ?? 'AutoMod Pro')) ?></div>
        <div class="report-title"><?= esc((string)($titulo ?? 'Reporte General')) ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <?php if (isset($columnas) && is_array($columnas)): ?>
                    <?php foreach($columnas as $col): ?>
                        <th><?= esc((string)$col) ?></th>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($registros) && is_array($registros)): ?>
                <?php foreach($registros as $reg): 
                    $regArray = (array)$reg; ?>
                    <tr>
                        <?php if(!empty($mapping) && is_array($mapping)): 
                            foreach($mapping as $key): 
                                $val = $regArray[$key] ?? 'N/A';
                                $isMoney = in_array($key, ['total', 'precio', 'monto', 'precio_unitario', 'monto_total', 'pedido_total', 'pedido_total']);
                                ?>
                                <td>
                                    <?php if ($isMoney && is_numeric($val)): ?>
                                        $<?= number_format((float)$val, 0, ',', '.') ?>
                                    <?php else: ?>
                                        <?= esc(is_scalar($val) ? (string)$val : (is_null($val) ? '—' : '...')) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach;
                        else: 
                            foreach($regArray as $val): ?>
                                <td><?= esc(is_scalar($val) ? (string)$val : '...') ?></td>
                            <?php endforeach;
                        endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="100%">No se encontraron registros.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if(!empty($resumen)): ?>
        <div class="resumen"><?= esc((string)$resumen) ?></div>
    <?php endif; ?>

    <div class="footer">
        AutoMod Pro - Sistema de Gestión Automotriz Especializada - Generado Automáticamente
    </div>
</body>
</html>