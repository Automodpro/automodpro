<?php
/*
 * Vista genérica para reportes en PDF.
 * Variables esperadas:
 * - $titulo (string)
 * - $registros (array)
 * - $columnas (array de strings)
 * - $mapping (array de strings: keys del registro)
 * - $resumen (string, opcional)
 */

// Para evitar errores si no se pasan variables
$titulo   = $titulo   ?? 'Reporte';
$registros = $registros ?? [];
$columnas = $columnas ?? [];
$mapping  = $mapping  ?? [];
$resumen  = $resumen  ?? null;
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
        .resumen { margin: 8px 0; font-size: 12px; font-weight: bold; }
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

    <?php if (!empty($resumen)): ?>
        <div class="resumen"><?= esc($resumen) ?></div>
    <?php endif; ?>

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
                                    // Si el registro es array o objeto, lo soportamos
                                    $value = is_array($registro)
                                        ? ($registro[$key] ?? '')
                                        : (isset($registro->$key) ? $registro->$key : '');
                                    echo esc((string)$value);
                                ?>
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

