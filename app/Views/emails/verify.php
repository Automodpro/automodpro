<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f7fc; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; }
        .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #3361ff, #1a3fd9); padding: 36px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 24px; margin: 0; font-weight: 800; letter-spacing: -0.5px; }
        .header .icon { width: 56px; height: 56px; background: rgba(255,255,255,0.15); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 28px; }
        .body { padding: 32px; }
        .body h2 { color: #111; font-size: 18px; margin: 0 0 8px; }
        .body p { color: #555; font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .body .warning { background: #fff8e1; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 8px; margin: 16px 0; color: #92400e; font-size: 14px; }
        .btn { display: inline-block; background: #3361ff; color: #fff; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-weight: 700; font-size: 15px; }
        .btn:hover { background: #1a3fd9; }
        .footer { padding: 24px 32px; text-align: center; border-top: 1px solid #eee; }
        .footer p { color: #999; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="icon">🚗</div>
                <h1>AutoMod Pro</h1>
            </div>
            <div class="body">
                <h2>Hola, <?= esc($nombre) ?></h2>
                <p>Recibimos una solicitud de registro en <strong>AutoMod Pro</strong> con este correo electrónico.</p>
                <div class="warning">
                    ¿Eres tú quien está intentando registrarse? Si fuiste tú, haz clic en el botón para confirmar tu cuenta.
                </div>
                <p style="text-align:center; margin: 24px 0;">
                    <a href="<?= $verifyUrl ?>" class="btn">Sí, soy yo — Verificar cuenta</a>
                </p>
                <p>Si no solicitaste este registro, ignora este mensaje y no se creará ninguna cuenta.</p>
                <p style="color:#999; font-size:13px;">Este enlace expirará una vez usado.</p>
            </div>
            <div class="footer">
                <p>© <?= date('Y') ?> AutoMod Pro — Sistema de Gestión Automotriz</p>
            </div>
        </div>
    </div>
</body>
</html>
