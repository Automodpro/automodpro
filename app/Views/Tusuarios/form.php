<?php
// Form admin de CRUD de usuarios
$u = $usuario ?? null;
$roles = $roles ?? [];
$accion = $accion ?? 'Gestionar usuario';
$actionUrl = $actionUrl ?? base_url('usuarios/guardar');
$titulo = (string)($titulo ?? $accion);
$titulo = $titulo === '' ? 'Editar usuario' : $titulo;

$errHtml = '';
if (session('error')) {
    $errHtml = '<div class="alert alert-danger"><i class="bi bi-x-circle"></i> ' . session('error') . '</div>';
}

$successHtml = '';
if (session('success')) {
    $successHtml = '<div class="alert alert-success"><i class="bi bi-check-circle"></i> ' . session('success') . '</div>';
}

$isEdit = $u !== null;
$usuarioId = (int)($u['id'] ?? 0);

$nombre = (string)($u['nombre_usuario'] ?? $u['nombre'] ?? '');
$correo = (string)($u['correo'] ?? '');
$currentRolId = (int)($u['rol_id'] ?? 0);
$formAction = $actionUrl ?? base_url('usuarios/guardar');

$accionGuardar = $isEdit ? 'Actualizar usuario' : 'Crear usuario';

ob_start();
?>
<div class="page-header">
    <h2><i class="bi bi-person-workspace"></i> <?= $titulo ?></h2>
    <p>Gestiona los datos del usuario</p>
</div>

<?= $successHtml . $errHtml ?>

<div class="card">
    <div class="card-body">
        <form action="<?= $formAction ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $usuarioId ?>" />
            <?php endif; ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de usuario <span class="required">*</span></label>
                    <input type="text" name="nombre" class="form-control" value="<?= esc($nombre) ?>" required />
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol <span class="required">*</span></label>
                    <select name="rol_id" class="form-select" required>
                        <option value="" <?= $currentRolId === 0 ? 'selected' : '' ?>>Selecciona un rol</option>
                        <?php foreach ($roles as $r):
                            $rid = (int)($r['id'] ?? 0);
                            $rnombre = (string)($r['nombre'] ?? '');
                            $selected = $rid === $currentRolId ? 'selected' : '';
                        ?>
                            <option value="<?= $rid ?>" <?= $selected ?>><?= esc($rnombre) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo <span class="required">*</span></label>
                    <input type="email" name="correo" class="form-control" value="<?= esc($correo) ?>" required <?= $isEdit ? 'readonly' : '' ?> />
                    <?php if ($isEdit): ?>
                        <div class="form-text">El correo es solo lectura al editar.</div>
                    <?php endif; ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña <?= $isEdit ? '' : '*' ?></label>
                    <?php if ($isEdit): ?>
                        <input type="text" class="form-control text-muted" value="********" readonly style="letter-spacing: 2px;" />
                        <div class="form-text">La contraseña está encriptada y no se puede editar desde este panel.</div>
                    <?php else: ?>
                        <input type="password" name="contrasena" class="form-control" required placeholder="Mínimo 6 caracteres" minlength="6" />
                    <?php endif; ?>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-floppy"></i> <?= $accionGuardar ?>
                </button>
                <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
echo view('layout/main', ['titulo' => $titulo, 'content' => $content]);
?>
