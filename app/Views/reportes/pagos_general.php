<?php
/*
 * Vista para reporte PDF de Pagos.
 * Variables esperadas:
 * - $titulo
 * - $registros (array)
 * - $columnas
 * - $mapping
 */

$titulo = $titulo ?? 'Pagos';
$registros = $registros ?? [];
$columnas = $columnas ?? [];
$mapping = $mapping ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 10px 0; }
        .meta { margin-bottom: 10px; font-size: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 6px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>

    <div class="meta">
        <div><span class="muted">Empresa:</span> <?= esc($empresa ?? '') ?></div>
        <div><span class="muted">Fecha:</span> <?= esc($fecha_generacion ?? '') ?></div>
        <div><span class="muted">Generado por:</span> <?= esc($generado_por ?? '') ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th><?= esc($col) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($registros)): ?>
            <?php foreach ($registros as $registro): ?>
                <tr>
                    <?php foreach ($mapping as $key): ?>
                        <td>
                            <?php
                                $value = is_array($registro)
                                    ? ($registro[$key] ?? '')
                                    : (isset($registro->$key) ? $registro->$key : '');
                            ?>
                            <?= esc((string)$value) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="<?= max(1, count($columnas)) ?>" class="muted">Sin registros</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

