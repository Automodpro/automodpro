<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoMod Pro — Sistema de Gestión Inteligente y Personalización Automotriz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --accent: #06b6d4;
            --dark-950: #070a13;
            --dark-900: #0f172a;
            --dark-800: #1e293b;
            --dark-card: #131a2c;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
            --slate-600: #475569;
            --border-glow: rgba(99, 102, 241, 0.15);
            --radius-2xl: 24px;
            --radius-xl: 16px;
            --transition-premium: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--dark-950); 
            color: #f8fafc; 
            scroll-behavior: smooth;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Ambient Glowing Background Blobs */
        .glow-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.12) 0%, rgba(6, 182, 212, 0.02) 70%, transparent 100%);
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
            animation: floatGlow 12s infinite alternate ease-in-out;
        }
        .blob-1 { top: -10%; right: -10%; }
        .blob-2 { top: 40%; left: -15%; background: radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 100%); }
        .blob-3 { bottom: 10%; right: -10%; }

        @keyframes floatGlow {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(30px) scale(1.1); }
        }

        /* Glassmorphic Navbar */
        .glass-nav { 
            backdrop-filter: blur(20px) saturate(180%); 
            background: rgba(7, 10, 19, 0.75) !important; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            transition: var(--transition-premium); 
        }
        .navbar-brand { 
            font-weight: 800; 
            letter-spacing: -0.04em; 
            font-size: 1.4rem; 
        }
        .nav-link-custom {
            color: var(--slate-400);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-premium);
        }
        .nav-link-custom:hover {
            color: #fff;
        }

        /* Hero Layout */
        .hero-section { 
            padding: 180px 0 100px; 
            position: relative; 
            z-index: 1;
        }
        .badge-saas {
            background: rgba(79, 70, 229, 0.15);
            color: #a5b4fc;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.04em;
            padding: 8px 16px;
            border-radius: 100px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(99, 102, 241, 0.3);
            margin-bottom: 1.5rem;
        }
        .display-hero { 
            font-weight: 800; 
            letter-spacing: -0.05em; 
            line-height: 1.1; 
            font-size: clamp(2.8rem, 5vw, 4.2rem);
        }
        .text-gradient { 
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 50%, #10b981 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        /* SaaS Buttons */
        .btn-saas-primary { 
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff !important;
            border: none; 
            padding: 14px 32px; 
            border-radius: var(--radius-xl); 
            font-weight: 700; 
            box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.5);
            transition: var(--transition-premium);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-saas-primary:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 35px -5px rgba(79, 70, 229, 0.7); 
        }
        .btn-saas-secondary { 
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08); 
            color: #fff !important; 
            padding: 14px 32px; 
            border-radius: var(--radius-xl); 
            font-weight: 600; 
            transition: var(--transition-premium);
        }
        .btn-saas-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        /* Tech Mockup Elements */
        .saas-mockup {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border-radius: var(--radius-2xl);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 28px;
            position: relative;
        }

        /* Advanced Grid System for Features */
        .saas-card { 
            border: 1px solid rgba(255, 255, 255, 0.05); 
            border-radius: var(--radius-2xl); 
            transition: var(--transition-premium); 
            height: 100%; 
            padding: 2.5rem; 
            background: linear-gradient(145deg, rgba(19, 26, 44, 0.7) 0%, rgba(10, 15, 30, 0.7) 100%); 
            position: relative;
            overflow: hidden;
        }
        .saas-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, transparent, rgba(99, 102, 241, 0.05), transparent);
            transform: translateX(-100%);
            transition: all 0.6s ease;
        }
        .saas-card:hover::before {
            transform: translateX(100%);
        }
        .saas-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 30px 50px -10px rgba(79, 70, 229, 0.15); 
            border-color: rgba(99, 102, 241, 0.3); 
        }
        .card-icon-box { 
            width: 52px; height: 52px; 
            border-radius: var(--radius-xl); 
            display: flex; align-items: center; justify-content: center; 
            font-size: 1.4rem; margin-bottom: 1.5rem; 
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--primary-light);
        }

        /* Interactive Simulator Components */
        .simulator-box {
            background: rgba(7, 10, 19, 0.5);
            border-radius: var(--radius-xl);
            border: 1px solid rgba(255, 255, 255, 0.06);
            padding: 20px;
        }

        /* Timelines and Steps */
        .step-pill {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-tag { 
            font-weight: 700; font-size: 0.8rem; 
            letter-spacing: 0.12em; color: var(--primary-light); 
            text-transform: uppercase; margin-bottom: 0.75rem; display: block; 
        }
        .section-title {
            font-weight: 800; letter-spacing: -0.04em; color: #fff;
        }

        /* Custom Form elements for Tech design */
        .form-select-saas {
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.9rem;
        }
        .form-select-saas:focus {
            background-color: var(--dark-card);
            border-color: var(--primary);
            color: #fff;
            box-shadow: none;
        }

        /* Footer */
        .footer-link {
            color: var(--slate-400); text-decoration: none; transition: var(--transition-premium); font-size: 0.9rem;
        }
        .footer-link:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="glow-blob blob-1"></div>
    <div class="glow-blob blob-2"></div>
    <div class="glow-blob blob-3"></div>

    <nav class="navbar navbar-expand-lg navbar-dark glass-nav py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('/') ?>">
                <div class="bg-primary p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--accent));">
                    <i class="bi bi-cpu-fill text-white" style="font-size: 1.1rem;"></i>
                </div>
                <span class="text-white">AutoMod<span class="text-gradient fw-extrabold">Pro</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-3 mt-3 mt-lg-0">
                    <li class="nav-item"><a href="#arquitectura" class="nav-link nav-link-custom">Pilares ERP</a></li>
                    <li class="nav-item"><a href="#motores" class="nav-link nav-link-custom">Motores Core</a></li>
                    <li class="nav-item"><a href="#flujo" class="nav-link nav-link-custom">Flujo de Trabajo</a></li>
                    <li class="nav-item"><a href="#beneficios" class="nav-link nav-link-custom">Rendimiento</a></li>
                    <li class="nav-item ms-lg-2"><hr class="text-white-50 my-1 d-lg-none"></li>
                    <li class="nav-item"><a href="<?= base_url('auth/login') ?>" class="text-decoration-none fw-bold text-white opacity-75 px-2">Inicio Sesión</a></li>
                    <li class="nav-item"><a href="<?= base_url('auth/register') ?>" class="btn btn-saas-primary py-2 px-4 fs-6">Registrarse</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="badge-saas">
                        <i class="bi bi-shield-check-fill text-accent"></i> Arquitectura Conectada v8.0 Activa
                    </div>
                    <h1 class="display-hero mb-4">El ERP inteligente que gobierna la <span class="text-gradient">ingeniería y logística</span> automotriz</h1>
                    <p class="lead mb-5 text-slate-400 fs-5" style="line-height: 1.6;">
                        Control operativo absoluto. Desde cotizaciones dinámicas calculadas por matrices algorítmicas, hasta auditorías completas de estados y validación legal de modificaciones.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="<?= base_url('auth/register') ?>" class="btn btn-saas-primary">
                            Desplegar en mi taller <i class="bi bi-arrow-right-short fs-5"></i>
                        </a>
                        <a href="#arquitectura" class="btn btn-saas-secondary">
                            Ver Documentación Core
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="saas-mockup">
                        <div class="d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-20 pb-3 mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded" style="font-size:0.75rem;">
                                    <i class="bi bi-terminal-fill me-1"></i> Sandbox Interactiva
                                </span>
                                <small class="text-slate-400 fw-semibold">Simulador de Rol (RBAC)</small>
                            </div>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-light btn-sm active" id="btn-admin" onclick="switchRole('admin')">Admin (Lv80)</button>
                                <button type="button" class="btn btn-outline-light btn-sm" id="btn-mecanico" onclick="switchRole('mecanico')">Mecánico (Lv50)</button>
                                <button type="button" class="btn btn-outline-light btn-sm" id="btn-cliente" onclick="switchRole('cliente')">Cliente (Lv10)</button>
                            </div>
                        </div>
                        
                        <div id="role-viewport" class="p-3 bg-dark rounded-3 border border-secondary border-opacity-10">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0 text-white fw-bold"><i class="bi bi-sliders text-primary me-2"></i> Panel de Configuración Global</h6>
                                <span class="badge bg-danger">Acceso VIP Total</span>
                            </div>
                            <p class="text-slate-400 small mb-3">Como Administrador de nivel 80, controlas la configuración de impuestos globales, pasarelas de pago y los factores multiplicadores del motor financiero.</p>
                            <div class="row g-2">
                                <div class="col-6"><div class="p-2 bg-secondary bg-opacity-10 rounded text-center border border-white border-opacity-5"><small class="text-slate-500 d-block">Margen Base</small><strong class="text-success">+25% COP</strong></div></div>
                                <div class="col-6"><div class="p-2 bg-secondary bg-opacity-10 rounded text-center border border-white border-opacity-5"><small class="text-slate-500 d-block">Configuración IVA</small><strong class="text-info">19% Activo</strong></div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="arquitectura" class="py-5 relative z-1">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <span class="section-tag">Infraestructura de Datos</span>
                <h2 class="display-6 section-title mb-3">Ingeniería de datos diseñada para talleres de alto rendimiento</h2>
                <p class="text-slate-400">AutoMod Pro unifica el control administrativo, técnico y financiero bajo una estructura relacional robusta que elimina fugas de capital.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card">
                        <div class="card-icon-box"><i class="bi bi-shield-lock"></i></div>
                        <h4 class="h5 fw-bold mb-3">Seguridad Perimetral RBAC</h4>
                        <p class="text-slate-400 small mb-0">Jerarquías blindadas por nivel operativo (80, 50, 10). Restringe vistas críticas de facturación al personal operativo y protege los datos maestros del taller automáticamente.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card">
                        <div class="card-icon-box"><i class="bi bi-journal-code"></i></div>
                        <h4 class="h5 fw-bold mb-3">Trazabilidad Total (Audit Trail)</h4>
                        <p class="text-slate-400 small mb-0">Cada cambio de estado en la orden de servicio (`pendiente` &rarr; `en_proceso` &rarr; `completado`) queda registrado con marca de tiempo e identidad del responsable en el historial.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="saas-card">
                        <div class="card-icon-box"><i class="bi bi-cash-stack"></i></div>
                        <h4 class="h5 fw-bold mb-3">Liquidación Automatizada</h4>
                        <p class="text-slate-400 small mb-0">Vínculo directo entre órdenes operativas y pasarelas de pago. Control estricto de estados transaccionales, soporte de comprobantes digitales e impuestos adaptados a la moneda local.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="motores" class="py-5 bg-dark-900 border-top border-bottom border-secondary border-opacity-10 position-relative z-1">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="section-tag">Algoritmo Financiero</span>
                    <h2 class="section-title mb-4">Motor de Precios Dinámicos Inteligente</h2>
                    <p class="text-slate-400 mb-4">
                        Olvídate de las tarifas planas imprecisas. AutoMod Pro liquida de manera justa cruzando variables automáticas de tu base de datos: el precio base del servicio multiplicado por los factores de reputación de la **marca**, complejidad de la **carrocería** y desgaste por **antigüedad**.
                    </p>
                    
                    <div class="simulator-box">
                        <h6 class="text-white mb-3 fw-bold"><i class="bi bi-calculator me-2 text-accent"></i> Simulación de Tarificación Automatizada</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <label class="text-slate-500 d-block small mb-1">Marca Auto</label>
                                <select class="form-select form-select-saas w-100" id="calc-marca" onchange="calculatePrice()">
                                    <option value="1.0">Chevrolet (1.0x)</option>
                                    <option value="1.3">BMW Premium (1.3x)</option>
                                    <option value="1.5">Porsche Elite (1.5x)</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="text-slate-500 d-block small mb-1">Carrocería</label>
                                <select class="form-select form-select-saas w-100" id="calc-tipo" onchange="calculatePrice()">
                                    <option value="1.0">Sedán (1.0x)</option>
                                    <option value="1.2">Camioneta SUV (1.2x)</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="text-slate-500 d-block small mb-1">Antigüedad</label>
                                <select class="form-select form-select-saas w-100" id="calc-ano" onchange="calculatePrice()">
                                    <option value="1.0">Nuevo &lt; 3 años (1.0x)</option>
                                    <option value="1.25">Clásico/Viejo (1.25x)</option>
                                </select>
                            </div>
                        </div>
                        <div class="p-3 bg-dark rounded-3 d-flex align-items-center justify-content-between border border-white border-opacity-5">
                            <div>
                                <span class="text-slate-500 small d-block">Precio Base: $200,000 COP</span>
                                <strong class="text-slate-300 small">Detalle del Pedido Generado</strong>
                            </div>
                            <div class="text-end">
                                <span class="text-slate-400 small d-block">Costo Final Ajustado</span>
                                <h5 class="text-success fw-bold mb-0" id="final-price">$200,000 COP</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <span class="section-tag">Mitigación de Riesgos</span>
                    <h2 class="section-title mb-4">Validador de Cumplimiento Legal</h2>
                    <p class="text-slate-400 mb-4">
                        Protege la reputación de tu negocio y advierte a tus clientes. El sistema integra un motor de reglas de validación técnica que contrasta la personalización solicitada (como nivel de polarizados, altura de suspensión o decibelios de escape) contra los límites legales de la legislación de transportes vigente.
                    </p>
                    
                    <div class="simulator-box">
                        <h6 class="text-white mb-3 fw-bold"><i class="bi bi-exclamation-triangle me-2 text-warning"></i> Monitoreo de Reglas de Modificación</h6>
                        <label class="text-slate-400 d-block small mb-2">Opacidad de Vidrios Polarizados Frontales:</label>
                        <input type="range" class="form-range" min="10" max="100" value="75" id="legal-range" oninput="validateLegality()">
                        <div class="mt-3 p-3 rounded-3 d-flex align-items-center justify-content-between" id="legal-status-box" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2);">
                            <div>
                                <strong class="d-block small" id="legal-title" style="color: #10b981;">Modificación Permitida</strong>
                                <span class="text-slate-400 small" id="legal-desc">Cumple con la norma mínima de visibilidad (Mínimo 70%).</span>
                            </div>
                            <span class="badge bg-success" id="legal-badge">Legal</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="flujo" class="py-5 position-relative z-1">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <span class="section-tag">Flujo Operativo Lineal</span>
                <h2 class="display-6 section-title mb-3">La anatomía de una orden perfecta</h2>
                <p class="text-slate-400">Cómo se orquesta el viaje de los datos dentro del ERP desde que el coche cruza la puerta del taller.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="saas-card">
                        <div class="step-pill">1</div>
                        <h5 class="fw-bold text-white mb-2">Admisión y Ficha Clínica</h5>
                        <p class="text-slate-400 small mb-0">Captura de placas, VIN y modelo. El vehículo se mapea con su respectivo historial de propiedad unificado y alertas preventivas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="saas-card">
                        <div class="step-pill">2</div>
                        <h5 class="fw-bold text-white mb-2">Despacho y Previsualización 2D</h5>
                        <p class="text-slate-400 small mb-0">Se definen los servicios estéticos y mecánicos, se calculan tarifas con el motor matemático y se visualiza el boceto en el personalizador 2D.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="saas-card">
                        <div class="step-pill">3</div>
                        <h5 class="fw-bold text-white mb-2">Auditoría y Liquidación</h5>
                        <p class="text-slate-400 small mb-0">Monitoreo en tiempo real de fases, registro automático en el *Audit Trail* de mecánicos y cobro exacto con control de comprobantes digitales.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="beneficios" class="py-5 bg-dark-900 border-top position-relative z-1">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <span class="section-tag">Ventaja Estratégica</span>
                    <h2 class="display-6 section-title mb-4">Diseñado para rentabilizar talleres mecánicos modernos</h2>
                    <p class="text-slate-400 mb-4">
                        AutoMod Pro sustituye el desorden de los tableros manuales por una arquitectura web sincronizada que incrementa la velocidad de entrega, protege el dinero de tu negocio y eleva la satisfacción del conductor.
                    </p>
                    <a href="<?= base_url('auth/register') ?>" class="btn btn-saas-primary">Desplegar Sistema Ahora</a>
                </div>
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 border border-white border-opacity-5 bg-dark-card h-100">
                                <div class="text-primary-light mb-3 fs-3"><i class="bi bi-clock-history"></i></div>
                                <h6 class="fw-bold text-white mb-2">Cero Cuellos de Botella</h6>
                                <p class="text-slate-400 small mb-0">Reduce hasta un 35% los retrasos logísticos gracias al rastreo de tiempos exactos en el historial de estados de pedidos.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 border border-white border-opacity-5 bg-dark-card h-100">
                                <div class="text-accent mb-3 fs-3"><i class="bi bi-graph-up-arrow"></i></div>
                                <h6 class="fw-bold text-white mb-2">Eliminación de Fugas Monetarias</h6>
                                <p class="text-slate-400 small mb-0">El cálculo paramétrico algorítmico asegura que cada carrocería e imprevisto de marca sea cobrado con total precisión matemática.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 border border-white border-opacity-5 bg-dark-card h-100">
                                <div class="text-warning mb-3 fs-3"><i class="bi bi-shield-check"></i></div>
                                <h6 class="fw-bold text-white mb-2">Garantía Normativa Integral</h6>
                                <p class="text-slate-400 small mb-0">Evita líos legales y multas de tránsito costosas blindando al taller con reglas de validación técnica antes de ejecutar modificaciones.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-4 rounded-4 border border-white border-opacity-5 bg-dark-card h-100">
                                <div class="text-success mb-3 fs-3"><i class="bi bi-people-fill"></i></div>
                                <h6 class="fw-bold text-white mb-2">Tasa de Retención Elevada</h6>
                                <p class="text-slate-400 small mb-0">El acceso de clientes independientes (Nivel 10) genera transparencia absoluta, permitiéndoles auditar el estatus de sus autos online.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 position-relative z-1">
        <div class="container py-5 text-center">
            <div class="p-5 rounded-5 border border-primary border-opacity-30 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15,23,42,0.9) 0%, rgba(79,70,229,0.1) 100%);">
                <h2 class="display-6 fw-bold text-white mb-3">Lleva la ingeniería de software a los boxes de tu taller</h2>
                <p class="text-slate-400 mx-auto mb-4" style="max-width: 550px;">Automatiza procesos operativos y tarificaciones en una arquitectura nativa, limpia y de nivel empresarial.</p>
                <a href="<?= base_url('auth/register') ?>" class="btn btn-saas-primary btn-lg px-4 py-3">
                    Empezar Auditoría Gratis <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <footer class="py-5 border-top border-secondary border-opacity-10 bg-dark-950 position-relative z-1">
        <div class="container py-4">
            <div class="row g-4 mb-5 text-start">
                <div class="col-lg-5 text-center text-lg-start">
                    <a class="navbar-brand d-inline-flex align-items-center gap-2 text-white mb-3" href="#">
                        <div class="bg-primary p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: linear-gradient(135deg, var(--primary), var(--accent));">
                            <i class="bi bi-cpu-fill text-white" style="font-size: 1rem;"></i>
                        </div>
                        <span class="fw-bold text-white">AutoMod<span class="text-gradient fw-extrabold">Pro</span></span>
                    </a>
                    <p class="text-slate-500 small pe-lg-5">El ecosistema ERP definitivo para la estandarización operativa, mitigación de riesgos legales y optimización comercial de centros mecánicos automotrices de clase mundial.</p>
                </div>
                <div class="col-6 col-md-4 col-lg-2 offset-lg-1">
                    <h6 class="fw-bold small text-uppercase text-white mb-3" style="letter-spacing:0.06em;">Ecosistema</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#arquitectura" class="footer-link">Control RBAC</a></li>
                        <li><a href="#motores" class="footer-link">Motor Tarifario</a></li>
                        <li><a href="#motores" class="footer-link">Reglas de Validación</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-4 col-lg-2">
                    <h6 class="fw-bold small text-uppercase text-white mb-3" style="letter-spacing:0.06em;">Recursos</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#" class="footer-link">Documentación Técnica</a></li>
                        <li><a href="#" class="footer-link">Soporte Operativo</a></li>
                        <li><a href="#" class="footer-link">Auditoría DB v8</a></li>
                    </ul>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <h6 class="fw-bold small text-uppercase text-white mb-3" style="letter-spacing:0.06em;">Marco Legal</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="#" class="footer-link">Términos de Licencia</a></li>
                        <li><a href="#" class="footer-link">Protección de Datos</a></li>
                        <li><a href="#" class="footer-link">Normatividad Tránsito</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-top border-secondary border-opacity-10 pt-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <p class="mb-0 text-slate-500 small">© <?= date('Y') ?> AutoMod Pro Enterprise. Desarrollado bajo estándares globales de software.</p>
                <div class="d-flex gap-3 text-slate-500 fs-5">
                    <a href="#" class="footer-link"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-link"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="footer-link"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Interactive RBAC Simulator logic
        function switchRole(role) {
            const viewport = document.getElementById('role-viewport');
            const btns = ['admin', 'mecanico', 'cliente'];
            
            btns.forEach(b => document.getElementById('btn-' + b).classList.remove('active'));
            document.getElementById('btn-' + role).classList.add('active');

            if (role === 'admin') {
                viewport.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-sliders text-primary me-2"></i> Panel de Configuración Global</h6>
                        <span class="badge bg-danger">Acceso VIP Total</span>
                    </div>
                    <p class="text-slate-400 small mb-3">Como Administrador de nivel 80, controlas la configuración de impuestos globales, pasarelas de pago y los factores multiplicadores del motor financiero.</p>
                    <div class="row g-2">
                        <div class="col-6"><div class="p-2 bg-secondary bg-opacity-10 rounded text-center border border-white border-opacity-5"><small class="text-slate-500 d-block">Margen Base</small><strong class="text-success">+25% COP</strong></div></div>
                        <div class="col-6"><div class="p-2 bg-secondary bg-opacity-10 rounded text-center border border-white border-opacity-5"><small class="text-slate-500 d-block">Configuración IVA</small><strong class="text-info">19% Activo</strong></div></div>
                    </div>
                `;
            } else if (role === 'mecanico') {
                viewport.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-tools text-success me-2"></i> Monitor de Órdenes Operativas</h6>
                        <span class="badge bg-warning text-dark">Nivel Técnico 50</span>
                    </div>
                    <p class="text-slate-400 small mb-3">Tu rol te permite ingresar vehículos, despachar reparaciones y actualizar fases operativas. Los datos maestros de facturas se ocultan de tu terminal.</p>
                    <div class="p-2 bg-dark rounded border border-warning border-opacity-20 d-flex justify-content-between align-items-center">
                        <span class="small text-slate-300"><i class="bi bi-activity text-warning me-2"></i> Orden #1024 &rarr; <strong>Cambiar a Proceso</strong></span>
                        <button class="btn btn-warning btn-sm py-0 px-2 fw-bold" style="font-size:0.75rem;">Actualizar</button>
                    </div>
                `;
            } else if (role === 'cliente') {
                viewport.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0 text-white fw-bold"><i class="bi bi-person-circle text-info me-2"></i> Historial de mis Vehículos</h6>
                        <span class="badge bg-success">Vista Cliente (Lv10)</span>
                    </div>
                    <p class="text-slate-400 small mb-3">Transparencia y control. Mira en tiempo real los estados auditables de tus vehículos ingresados y los comprobantes digitales emitidos por administración.</p>
                    <div class="p-2 bg-secondary bg-opacity-10 rounded d-flex justify-content-between align-items-center">
                        <span class="small text-white">Audi RS Q8 [BKG-482]</span>
                        <span class="badge bg-primary">En Pintura 2D</span>
                    </div>
                `;
            }
        }

        // Interactive Pricing Engine Calculator logic
        function calculatePrice() {
            const base = 200000;
            const fMarca = parseFloat(document.getElementById('calc-marca').value);
            const fTipo = parseFloat(document.getElementById('calc-tipo').value);
            const fAno = parseFloat(document.getElementById('calc-ano').value);
            
            const total = base * fMarca * fTipo * fAno;
            
            document.getElementById('final-price').innerText = '$' + total.toLocaleString('es-CO') + ' COP';
        }

        // Interactive Legality Compliance slider logic
        function validateLegality() {
            const val = parseInt(document.getElementById('legal-range').value);
            const box = document.getElementById('legal-status-box');
            const title = document.getElementById('legal-title');
            const desc = document.getElementById('legal-desc');
            const badge = document.getElementById('legal-badge');
            
            if(val >= 70) {
                box.style.background = "rgba(16, 185, 129, 0.1)";
                box.style.borderColor = "rgba(16, 185, 129, 0.2)";
                title.style.color = "#10b981";
                title.innerText = "Modificación Permitida";
                desc.innerText = "Cumple con la norma mínima de visibilidad (Mínimo 70% de luminosidad).";
                badge.className = "badge bg-success";
                badge.innerText = "Legal";
            } else {
                box.style.background = "rgba(239, 68, 68, 0.1)";
                box.style.borderColor = "rgba(239, 68, 68, 0.2)";
                title.style.color = "#ef4444";
                title.innerText = "Riesgo de Multa Operativa";
                desc.innerText = "Opacidad no permitida por la norma legal de tránsito. Generará infracciones.";
                badge.className = "badge bg-danger";
                badge.innerText = "Infracción";
            }
        }
    </script>
</body>
</html>