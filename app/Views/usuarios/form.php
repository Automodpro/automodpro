<?php
$titulo = 'Editar perfil';
$u = $usuario ?? [];
$roles = $roles ?? [];
$action = base_url('usuarios/actualizar/' . ($u['id'] ?? ''));

$errHtml = '';
if (session('error')) {
    $errHtml = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ' . session('error') . '</div>';
}

$successHtml = '';
if (session('success')) {
    $successHtml = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' . session('success') . '</div>';
}

$currentRolId = isset($u['rol_id']) ? (int)$u['rol_id'] : 0;
$correoActual = (string)($u['correo'] ?? '');
$rolNombre = (string)($u['rol'] ?? '');

$rolInput = '';
if (session('rol') === 'admin') {
    $rolInput = '<select name="rol_id" class="form-select" required>
        <option value=""' . ($currentRolId === 0 ? ' selected' : '') . '>Selecciona un rol</option>
        ' . (function () use ($roles, $currentRolId) {
            $opts = '';
            foreach ($roles as $r) {
                $id = (int)($r['id'] ?? 0);
                $nombre = htmlspecialchars((string)($r['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
                $selected = $id === $currentRolId ? ' selected' : '';
                $opts .= '<option value="' . $id . '"' . $selected . '>' . $nombre . '</option>';
            }
            return $opts;
        })() . '
    </select>';
} else {
    $rolInput = '<input type="text" class="form-control" value="' . htmlspecialchars($rolNombre, ENT_QUOTES, 'UTF-8') . '" readonly>';
}

$content = '
<div class="page-header">
    <h2><i class="bi bi-pencil"></i> ' . $titulo . '</h2>
    <p>Actualiza tu información personal</p>
</div>

' . $successHtml . $errHtml . '

<div class="card">
    <div class="card-body">
        <form action="' . $action . '" method="post">
            <div class="grid-2 mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="' . htmlspecialchars((string)($u['nombre_usuario'] ?? $u['nombre'] ?? ''), ENT_QUOTES, 'UTF-8') . '" required>
                </div>

                <div class="form-group mb-0">
                    <label class="form-label">Rol</label>
                    ' . $rolInput . '
                </div>
            </div>

            <div class="mb-4">
                <div class="form-group mb-0">
                    <label class="form-label">Correo actual</label>
                    <input type="email" class="form-control" value="' . htmlspecialchars($correoActual, ENT_QUOTES, 'UTF-8') . '" readonly>
                </div>
            </div>

            <hr>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-none border" style="background:var(--gray-50);">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-shield-lock me-2"></i>
                                <h5 class="m-0">Contraseña</h5>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña actual</label>
                                <input type="password" name="contraseña_actual" class="form-control" placeholder="Se requiere si cambias correo o contraseña">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input type="password" name="nueva_contraseña" class="form-control" placeholder="Dejar vacío para mantener" minlength="6">
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" name="confirmar_nueva_contraseña" class="form-control" placeholder="Confirmar" minlength="6">
                            </div>

                            <div class="form-text mt-2">Si escribes una contraseña nueva, debe coincidir con la confirmación.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-none border" style="background:var(--gray-50);">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-envelope me-2"></i>
                                <h5 class="m-0">Correo</h5>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nuevo correo</label>
                                <input type="email" name="nuevo_correo" class="form-control" placeholder="Escribe tu nuevo correo" autocomplete="off">
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Confirmar nuevo correo</label>
                                <input type="email" name="confirmar_nuevo_correo" class="form-control" placeholder="Repite tu nuevo correo" autocomplete="off">
                            </div>

                            <div class="form-text mt-2">Para cambiar el correo debes completar ambos campos (y validar con tu contraseña actual).</div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary px-5"><i class="bi bi-floppy"></i> Guardar cambios</button>
            <a href="' . base_url("usuarios") . '" class="btn btn-outline-secondary px-4 ms-2">Cancelar</a>
        </form>
    </div>
</div>';

echo view('layout/main', ['titulo' => $titulo, 'content' => $content]);
?>



