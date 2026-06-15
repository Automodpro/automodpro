<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-car-front-fill"></i></div>
        <div class="brand-text">
            <strong>AutoMod Pro</strong>
            <span>Tecnología Automotriz</span>
        </div>
    </div>

    <div class="sidebar-user">
        <?php
            $rolId = (int) session('rol_id');
            $rolSlug = match ($rolId) {
                1 => 'admin',
                2 => 'mecanico',
                3 => 'usuario',
                default => (string) session('rol'),
            };

            $rolClass = $rolSlug === 'admin' ? 'admin' : ($rolSlug === 'mecanico' ? 'mecanico' : 'usuario');
            $bgColor = $rolSlug === 'admin' ? '#ef4444' : ($rolSlug === 'mecanico' ? '#f59e0b' : '#0ea5e9');
            $rolName = $rolSlug === 'admin' ? 'Administrador' : ($rolSlug === 'mecanico' ? 'Mecánico' : 'Usuario');
        ?>
        <div class="user-avatar" style="background:<?= $bgColor ?>;">
            <?= strtoupper(substr(session('nombre'), 0, 1)) ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?= session('nombre') ?></div>
            <span class="user-role role-<?= $rolClass ?>"><?= $rolName ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php $seg = service('uri')->getSegment(1) ?: 'dashboard'; ?>
        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= $seg === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <?php if ((int) session('rol_id') === 1): ?>
        <a href="<?= base_url('usuarios') ?>" class="nav-link <?= $seg === 'usuarios' ? 'active' : '' ?>">
            <i class="bi bi-people"></i> Usuarios
        </a>
        <?php endif; ?>
        <a href="<?= base_url('vehiculos') ?>" class="nav-link <?= $seg === 'vehiculos' ? 'active' : '' ?>">
            <i class="bi bi-truck"></i> Vehículos
        </a>
        <a href="<?= base_url('servicios') ?>" class="nav-link <?= $seg === 'servicios' ? 'active' : '' ?>">
            <i class="bi bi-tools"></i> Servicios
        </a>
        <a href="<?= base_url('pedidos') ?>" class="nav-link <?= $seg === 'pedidos' ? 'active' : '' ?>">
            <i class="bi bi-cart-check"></i> Pedidos
        </a>
        <a href="<?= base_url('pagos') ?>" class="nav-link <?= $seg === 'pagos' ? 'active' : '' ?>">
            <i class="bi bi-credit-card"></i> Pagos
        </a>

        <a href="<?= base_url('usuarios?view=perfil') ?>" class="nav-link <?= $seg === 'usuarios' && service('request')->getGet('view') === 'perfil' ? 'active' : '' ?>">
            <i class="bi bi-person-badge"></i> Mi Perfil
        </a>
        <?php if ((int) session('rol_id') !== 3): ?>
        <div class="nav-section">Administración</div>
        <a href="<?= base_url('configuracion') ?>" class="nav-link <?= $seg === 'configuracion' ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> Configuración
        </a>
        <a href="<?= base_url('factores-precio') ?>" class="nav-link <?= $seg === 'factores-precio' ? 'active' : '' ?>">
            <i class="bi bi-percent"></i> Factores de Precio
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= base_url('personalizador') ?>" class="btn btn-outline-primary">
            <i class="bi bi-palette"></i> Personalizador 2D
        </a>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>
</aside>
