<?php 
$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-people"></i> Usuarios</h2>
            <p>Gestión de usuarios registrados en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="' . base_url('reportes/usuariosGeneral') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">
                <i class="bi bi-file-earmark-pdf"></i> Reporte PDF
            </a>
            <a href="' . base_url('usuarios/crear') . '" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Nuevo usuario
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
                    <th><i class="bi bi-person"></i> Nombre</th>
                    <th><i class="bi bi-envelope"></i> Correo</th>
                    <th><i class="bi bi-lock"></i> Contraseña</th>
                    <th><i class="bi bi-shield-check"></i> Rol</th>
                    <th><i class="bi bi-calendar"></i> Registro</th>
                    <th style="width:110px"><i class="bi bi-gear"></i> Acción</th>
                </tr>
            </thead>
            <tbody>';
foreach (($usuarios ?? []) as $u) {
    $rol = $u['rol'] ?? 'Usuario';
    $badge = 'info';
    if ($u['rol_id'] == 1) $badge = 'danger';
    elseif ($u['rol_id'] == 2) $badge = 'warning';

    $content .= '<tr>
        <td class="fw-semibold" style="color:var(--primary-600);">' . $u['id'] . '</td>
        <td class="fw-semibold">' . $u['nombre'] . '</td>
        <td>' . $u['correo'] . '</td>
        <td class="text-muted" style="letter-spacing: 2px;">' . (isset($u['contrasena']) ? '********' : 'N/A') . '</td>
        <td><span class="badge badge-' . $badge . '">' . $rol . '</span></td>
        <td>' . (isset($u['creado_en']) ? date('d/m/Y', strtotime($u['creado_en'])) : 'N/A') . '</td>
        <td>
            <div class="d-flex gap-1">
                <a href="' . base_url('usuarios/editar/' . $u['id']) . '" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                <a href="' . base_url('reportes/usuarioDetalle/' . $u['id']) . '" class="btn btn-outline-danger btn-sm" target="_blank" rel="noopener" title="PDF del usuario"><i class="bi bi-file-earmark-pdf"></i></a>';
    if ($u['id'] != session('id')) {
        $content .= '<a href="' . base_url('usuarios/eliminar/' . $u['id']) . '" class="btn btn-danger btn-sm" data-confirm="¿Eliminar este usuario?" title="Eliminar"><i class="bi bi-trash"></i></a>';
    }
    $content .= '</div>
        </td>
    </tr>';
}
$content .= '     </tbody>
            </table>
        </div>
    </div>';

echo view('layout/main', ['titulo' => 'Usuarios', 'content' => $content]);
?>
