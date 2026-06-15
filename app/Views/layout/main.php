<?php
$content = $content ?? '';
?>
<?= view('layout/header') ?>
<div class="d-flex" id="app">
    <?= view('layout/sidebar') ?>

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>
            <div class="breadcrumb">
                <i class="bi bi-house"></i> AutoMod Pro
                <i class="bi bi-chevron-right"></i>
                <span><?= $titulo ?? 'Dashboard' ?></span>
            </div>
        </div>
        <div class="topbar-right">
            <div class="user-dropdown">
                <div class="user-dropdown-toggle">
                    <div class="d-flex align-items-center gap-2" style="cursor:pointer;">
                        <div class="avatar" style="background:<?= session('rol') === 'admin' ? '#ef4444' : (session('rol') === 'mecanico' ? '#f59e0b' : '#0ea5e9') ?>;">
                            <?= strtoupper(substr(session('nombre'), 0, 1)) ?>
                        </div>
                        <div class="user-meta">
                            <div class="name"><?= session('nombre') ?></div>
                            <div class="role"><?= (int)session('rol_id') === 1 ? 'Administrador' : ((int)session('rol_id') === 2 ? 'Mecánico' : 'Usuario') ?></div>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size:0.7rem;color:var(--gray-400);"></i>
                    </div>
                </div>
                <div class="dropdown-menu">
                    <a href="<?= base_url('usuarios') ?>"><i class="bi bi-person-badge"></i> Mi Perfil</a>
                    <a href="<?= base_url('personalizador') ?>"><i class="bi bi-palette"></i> Personalizador 2D</a>
                    <div class="divider"></div>
                    <a href="<?= base_url('logout') ?>" class="danger"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <?php if (session('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> <?= session('success') ?>
                <button class="btn-close">×</button>
            </div>
        <?php endif; ?>
        <?php if (session('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle"></i> <?= session('error') ?>
                <button class="btn-close">×</button>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
