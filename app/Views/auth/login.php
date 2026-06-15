<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMod Pro — Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        body.auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #05060a 0%, #0f1420 40%, #1a1f35 100%);
            position: relative;
            overflow: hidden;
            padding: 24px;
        }
        body.auth-page::before {
            content: '';
            position: absolute;
            width: 900px;
            height: 900px;
            background: radial-gradient(circle, rgba(51,97,255,0.08) 0%, transparent 65%);
            top: -300px;
            right: -200px;
            border-radius: 50%;
            pointer-events: none;
        }
        body.auth-page::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(245,158,11,0.04) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-grid {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 60px;
            width: 100%;
            max-width: 1100px;
            position: relative;
            z-index: 1;
        }

        .auth-banner {
            flex: 1;
            max-width: 460px;
            display: none;
        }

        .auth-banner .banner-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3361ff, #1a3fd9);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.2rem;
            margin-bottom: 24px;
            box-shadow: 0 12px 40px rgba(51,97,255,0.3);
        }

        .auth-banner h1 {
            font-size: 2.4rem;
            font-weight: 800;
            color: white;
            line-height: 1.15;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .auth-banner p {
            font-size: var(--text-lg);
            color: rgba(255,255,255,0.5);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .auth-banner .feature-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .auth-banner .feature-list .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255,255,255,0.6);
            font-size: var(--text-sm);
            font-weight: 500;
        }

        .auth-banner .feature-list .feature-item i {
            width: 28px;
            height: 28px;
            background: rgba(51,97,255,0.15);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-400);
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        @media (min-width: 992px) {
            .auth-banner { display: block; }
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-card .card {
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 24px;
            overflow: hidden;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            animation: authIn 500ms ease;
        }

        @keyframes authIn {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-header {
            padding: 36px 32px 20px;
            text-align: center;
        }

        .auth-header .auth-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3361ff, #1a3fd9);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 24px rgba(51,97,255,0.25);
        }

        .auth-header h2 {
            font-size: var(--text-2xl);
            font-weight: 800;
            color: var(--gray-900);
            margin: 0 0 4px;
        }

        .auth-header p {
            color: var(--gray-500);
            font-size: var(--text-sm);
            margin: 0;
        }

        .auth-body { padding: 0 32px 28px; }

        .auth-body .form-group { margin-bottom: 20px; }

        .auth-body .form-group .form-label {
            font-size: var(--text-xs);
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
            display: block;
        }

        .auth-body .form-group .form-control {
            width: 100%;
            padding: 12px 16px;
            font-size: var(--text-sm);
            font-family: var(--font-primary);
            color: var(--gray-800);
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-md);
            transition: all var(--transition);
            outline: none;
        }

        .auth-body .form-group .form-control:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(51,97,255,0.1);
        }

        .auth-body .btn-primary {
            width: 100%;
            padding: 14px;
            font-size: var(--text-base);
            font-weight: 700;
            background: var(--primary-500);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .auth-body .btn-primary:hover {
            background: var(--primary-600);
            box-shadow: 0 4px 16px rgba(51,97,255,0.3);
        }

        .auth-footer {
            padding: 20px 32px;
            background: var(--gray-50);
            text-align: center;
            font-size: var(--text-sm);
            color: var(--gray-500);
            border-top: 1px solid var(--gray-100);
        }

        .auth-footer a {
            color: var(--primary-600);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-footer a:hover { text-decoration: underline; }

        .auth-demo {
            text-align: center;
            margin-top: 16px;
            color: rgba(255,255,255,0.2);
            font-size: var(--text-xs);
            letter-spacing: 0.3px;
        }

        .auth-demo span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255,255,255,0.04);
            border-radius: var(--radius-full);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .auth-demo i { color: var(--primary-400); }

        .social-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 16px;
            color: var(--gray-400);
            font-size: var(--text-xs);
            font-weight: 500;
        }
        .social-divider::before,
        .social-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }
        .google-btn {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }
        .social-buttons {
            display: flex;
            gap: 10px;
        }
        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border-radius: var(--radius-md);
            font-size: var(--text-sm);
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid var(--gray-200);
            transition: all var(--transition);
            cursor: pointer;
        }
        .social-btn:hover { text-decoration: none; }
        .facebook-btn { color: #1877f2; }
        .facebook-btn:hover { background: #1877f2; color: white; border-color: #1877f2; }
        .github-btn { color: #24292e; }
        .github-btn:hover { background: #24292e; color: white; border-color: #24292e; }
    </style>
</head>
<body class="auth-page">
    <div class="auth-grid">
        <div class="auth-banner">
            <div class="banner-icon"><i class="bi bi-car-front-fill"></i></div>
            <h1>Gestión Automotriz<br>Inteligente</h1>
            <p>La plataforma integral para la administración de talleres, vehículos, servicios y órdenes de trabajo.</p>
            <div class="feature-list">
                <div class="feature-item"><i class="bi bi-check-lg"></i> Gestión completa de vehículos y clientes</div>
                <div class="feature-item"><i class="bi bi-check-lg"></i> Catálogo de servicios con precios dinámicos</div>
                <div class="feature-item"><i class="bi bi-check-lg"></i> Órdenes de servicio y pagos en línea</div>
                <div class="feature-item"><i class="bi bi-check-lg"></i> Personalizador 2D de vehículos</div>
                <div class="feature-item"><i class="bi bi-check-lg"></i> Reportes y estadísticas del taller</div>
            </div>
        </div>
        <div class="auth-card">
            <div class="card">
                <div class="auth-header">
                    <div class="auth-icon"><i class="bi bi-car-front-fill"></i></div>
                    <h2>AutoMod Pro</h2>
                    <p>Sistema de Gestión Automotriz</p>
                </div>
                <div class="auth-body">
                    <?php if (session('error')): ?>
                        <div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> <?= session('error') ?></div>
                    <?php endif; ?>
                    <?php if (session('success')): ?>
                        <div class="alert alert-success"><i class="bi bi-check-circle"></i> <?= session('success') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/doLogin') ?>" method="post">
                        <div class="form-group">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" placeholder="admin@sistema.com" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
                        </div>
                        <button type="submit" class="btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                        </button>
                    </form>

                    <div class="social-divider"><span>O continúa con</span></div>

                    <div id="g_id_onload"
                         data-client_id="453597919435-n93knkb663g72j99menn83v6oohavles.apps.googleusercontent.com"
                         data-context="signin"
                         data-ux_mode="popup"
                         data-callback="handleGoogleLogin"
                         data-auto_prompt="false">
                    </div>
                    <div class="g_id_signin google-btn"
                         data-type="standard"
                         data-shape="rectangular"
                         data-theme="outline"
                         data-text="signin_with"
                         data-size="large"
                         data-width="280">
                    </div>
                    <form id="google-form" action="<?= base_url('auth/google') ?>" method="post" style="display:none">
                        <input type="hidden" name="credential" id="google-credential">
                    </form>

                    <div class="social-buttons">
                        <a href="#" class="social-btn facebook-btn" onclick="alert('Facebook próximamente')">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="#" class="social-btn github-btn" onclick="alert('GitHub próximamente')">
                            <i class="bi bi-github"></i> GitHub
                        </a>
                    </div>
                </div>
                <div class="auth-footer">
                    ¿No tienes cuenta? <a href="<?= base_url('auth/register') ?>">Crear cuenta</a>
                </div>
            </div>
            <div class="auth-demo">
                <span><i class="bi bi-shield-check"></i> Demo: admin@sistema.com / 123456</span>
            </div>
        </div>
    </div>
    <script>
        function handleGoogleLogin(response) {
            if (response.credential) {
                document.getElementById('google-credential').value = response.credential;
                document.getElementById('google-form').submit();
            }
        }
    </script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
</body>
</html>
