<?php 
$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-truck"></i> Vehículos</h2>
            <p>Gestión de vehículos registrados en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="' . base_url('reportes/vehiculosGeneral') . '" class="btn btn-outline-danger" target="_blank">
                <i class="bi bi-file-pdf"></i> Reporte PDF
            </a>
        <a href="' . base_url('vehiculos/crear') . '" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo vehículo
        </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table class="table datatable" style="width:100%">
            <thead>
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-upc-scan"></i> Placa</th>
                    <th><i class="bi bi-tag"></i> Marca</th>
                    <th><i class="bi bi-info-circle"></i> Modelo</th>
                    <th><i class="bi bi-grid-3x3"></i> Tipo</th>
                    <th><i class="bi bi-calendar"></i> Año</th>
                    <th><i class="bi bi-person"></i> Propietario</th>
                    <th style="width:110px"><i class="bi bi-gear"></i> Acción</th>
                </tr>
            </thead>
            <tbody>';
foreach (($vehiculos ?? []) as $v) {

    $content .= '<tr>
        <td class="fw-semibold" style="color:var(--primary-600);">' . $v['id'] . '</td>
        <td><span class="placa-badge">' . $v['placa'] . '</span></td>
        <td class="fw-semibold">' . $v['marca'] . '</td>
        <td>' . $v['modelo'] . '</td>
        <td><span class="badge badge-dark">' . $v['tipo'] . '</span></td>
        <td>' . ($v['año'] ?? '') . '</td>
        <td>' . ($v['propietario'] ?? "N/A") . '</td>
        <td>
            <div class="d-flex gap-1">
                <a href="' . base_url('reportes/vehiculoDetalle/' . $v['id']) . '" class="btn btn-outline-danger btn-sm" title="PDF del vehículo" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf"></i></a>
                <a href="' . base_url('vehiculos/editar/' . $v['id']) . '" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                <a href="' . base_url('vehiculos/eliminar/' . $v['id']) . '" class="btn btn-danger btn-sm" data-confirm="¿Eliminar este vehículo?" title="Eliminar"><i class="bi bi-trash"></i></a>
            </div>
        </td>
    </tr>';
}
$content .= '     </tbody>
            </table>
        </div>
    </div>';

echo view('layout/main', ['titulo' => 'Vehículos', 'content' => $content]);
?>
