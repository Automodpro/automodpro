<?php
$verified = session()->get('config_verified') || session('rol') === 'admin';

if (!$verified) {
    $content = '
    <div class="page-header text-center">
        <h2 style="justify-content:center;"><i class="bi bi-shield-lock" style="color:var(--warning-500);"></i> Configuración Protegida</h2>
        <p>Verifique su identidad para acceder a la configuración del sistema</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card" style="overflow:hidden;">
                <div class="text-center py-5" style="background:linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="bi bi-lock-fill text-white" style="font-size:3.5rem;"></i>
                </div>
                <div class="card-body p-4 text-center">
                    <h4 class="fw-bold mb-2">Acceso Restringido</h4>
                    <p class="text-muted mb-4">Ingresa la contraseña de administrador para continuar</p>
                    ' . (session("error") ? '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i> ' . session("error") . '</div>' : "") . '
                    <form action="' . base_url("configuracion/verify") . '" method="post">
                        <div class="form-group mb-4">
                            <input type="password" name="password" class="form-control text-center" placeholder="Contraseña de acceso" required style="padding:14px;">
                        </div>
                        <button type="submit" class="btn btn-warning w-100 fw-bold py-3" style="font-size:var(--text-base);"><i class="bi bi-unlock me-1"></i> Verificar acceso</button>
                    </form>
                    <a href="' . base_url("dashboard") . '" class="btn btn-outline-secondary mt-3">Volver al Dashboard</a>
                </div>
            </div>
        </div>
    </div>';
} else {
    $c = $config;
    $content = '
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="bi bi-gear"></i> Configuración del Sistema</h2>
                <p>Administra los parámetros generales del taller</p>
            </div>
            ' . (session("rol") !== "admin" ? '<a href="' . base_url("configuracion/lock") . '" class="btn btn-outline-warning"><i class="bi bi-lock me-1"></i> Bloquear</a>' : "") . '
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="' . base_url("configuracion/update") . '" method="post">
                <div class="grid-2 mb-4">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-building me-1"></i> Nombre del Taller</label>
                        <input type="text" name="nombre_taller" class="form-control" value="' . ($c["nombre_taller"] ?? "AutoMod Pro") . '">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-geo-alt me-1"></i> Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="' . ($c["direccion"] ?? "") . '">
                    </div>
                </div>
                <div class="grid-3 mb-4">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-telephone me-1"></i> Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="' . ($c["telefono"] ?? "") . '">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-envelope me-1"></i> Email de Contacto</label>
                        <input type="email" name="email_contacto" class="form-control" value="' . ($c["email_contacto"] ?? "") . '">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-currency-exchange me-1"></i> Moneda</label>
                        <input type="text" name="moneda" class="form-control" value="' . ($c["moneda"] ?? "COP") . '">
                    </div>
                </div>
                <div class="grid-3 mb-4">
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-percent me-1"></i> IVA (%)</label>
                        <input type="number" step="0.01" name="iva" class="form-control" value="' . ($c["iva"] ?? "19") . '">
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label"><i class="bi bi-clock me-1"></i> Horario de Atención</label>
                        <input type="text" name="horario" class="form-control" value="' . ($c["horario"] ?? "") . '">
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary px-5 py-2"><i class="bi bi-floppy me-1"></i> Guardar Configuración</button>
            </form>
        </div>
    </div>';
}

echo view('layout/main', ['titulo' => 'Configuración', 'content' => $content]);
?>
