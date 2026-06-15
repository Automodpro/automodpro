<?php
$u = $usuarios[0] ?? null;

if (!$u) {
    echo view('layout/main', [
        'titulo' => 'Mi Perfil',
        'content' => '<div class="alert alert-danger">Usuario no encontrado</div>'
    ]);
    return;
}

$rol = $u['rol'] ?? null;

$mapRol = [
    'admin' => ['badge' => 'danger', 'name' => 'Administrador', 'bg' => '#ef4444'],
    'mecanico' => ['badge' => 'warning', 'name' => 'Mecánico', 'bg' => '#f59e0b'],
];

$rolInfo = $mapRol[$rol] ?? ['badge' => 'info', 'name' => 'Usuario', 'bg' => '#0ea5e9'];

$badge = $rolInfo['badge'];
$rolName = $rolInfo['name'];
$bgColor = $rolInfo['bg'];

$nombre = $u['nombre'] ?? '';
$correo = $u['correo'] ?? '';
$initial = $nombre ? strtoupper(substr($nombre, 0, 1)) : '?';
$fechaRegistro = $u['creado_en'] ?? ($u['created_at'] ?? null);

$content = '
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-person-badge"></i> Mi Perfil</h2>
            <p>Información de tu cuenta</p>
        </div>
        <a href="' . base_url('reportes/miReporte') . '" class="btn btn-outline-danger" target="_blank" rel="noopener">
            <i class="bi bi-file-earmark-pdf"></i> Reporte de Actividad
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card" style="overflow:hidden;">
            <div class="text-center py-5" style="background:linear-gradient(135deg, ' . $bgColor . 'cc, ' . $bgColor . '88);">
                <div style="width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2rem;font-weight:800;color:white;border:3px solid rgba(255,255,255,0.3);">' . $initial . '</div>
                <h3 style="color:white;font-weight:700;margin:0 0 4px;font-size:var(--text-xl);">' . $nombre . '</h3>
                <span class="badge badge-' . $badge . '" style="font-size:var(--text-sm);padding:6px 16px;"><i class="bi bi-shield-check"></i> ' . $rolName . '</span>
            </div>
            <div class="card-body p-4">
                <div style="display:grid;gap:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--gray-50);border-radius:var(--radius-lg);">
                        <span style="color:var(--gray-500);font-weight:600;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:0.5px;"><i class="bi bi-envelope me-1"></i> Correo</span>
                        <span style="font-weight:500;">' . $correo . '</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:var(--gray-50);border-radius:var(--radius-lg);">
                        <span style="color:var(--gray-500);font-weight:600;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:0.5px;"><i class="bi bi-calendar me-1"></i> Registro</span>
                        <span style="font-weight:500;">' . ($fechaRegistro ? date('d/m/Y', strtotime($fechaRegistro)) : 'N/A') . '</span>
                    </div>
                </div>
                <hr>
                <a href="' . base_url('usuarios/editar/' . ($u['id'] ?? '')) . '" class="btn btn-primary w-100 py-2"><i class="bi bi-pencil"></i> Editar perfil</a>
            </div>
        </div>
    </div>
</div>';

echo view('layout/main', ['titulo' => 'Mi Perfil', 'content' => $content]);
?>
