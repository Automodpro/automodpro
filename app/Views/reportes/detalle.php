<?php
/*
 * Vista genérica para ficha técnica (detalle) en PDF.
 * Variables esperadas:
 * - $titulo (string)
 * - $campos (array asociativo: Etiqueta => Valor)
 */
$titulo = $titulo ?? 'Detalle';
$campos = $campos ?? [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 15px 0; color: #333; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .meta { margin-bottom: 20px; font-size: 10px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; vertical-align: top; }
        th { width: 30%; background: #f9f9f9; font-weight: bold; color: #555; }
        .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #999; }
    </style>
</head>
<body>
    <h1><?= esc($titulo) ?></h1>

    <div class="meta">
        <div><strong>Empresa:</strong> <?= esc($empresa ?? 'AutoMod Pro') ?></div>
        <div><strong>Fecha de generación:</strong> <?= esc($fecha_generacion ?? date('d/m/Y H:i')) ?></div>
        <div><strong>Generado por:</strong> <?= esc($generado_por ?? 'Sistema') ?></div>
    </div>

    <table>
        <tbody>
            <?php foreach ($campos as $label => $valor): ?>
                <tr>
                    <th><?= esc($label) ?></th>
                    <td><?= nl2br(esc((string)$valor)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de gestión AutoMod Pro.
    </div>
</body>
</html>