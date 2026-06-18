<?php
require_once __DIR__ . '/../../config/Auth.php';

require_login();

$usuario = auth_user();

$nombreUsuario = htmlspecialchars($usuario['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
$rolUsuarioRaw = $usuario['rol'] ?? 'usuario';
$rolUsuario = htmlspecialchars(ucfirst($rolUsuarioRaw), ENT_QUOTES, 'UTF-8');

$esSecretaria = function_exists('is_secretaria') && is_secretaria();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Escolástico</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sky: #0ea5e9;
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe4f0;
            --white: #ffffff;
            --soft-blue: #eaf1ff;
            --soft-green: #e9f9ef;
            --green: #16a34a;
            --danger: #dc2626;
            --shadow: 0 16px 38px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.15), transparent 32rem),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.12), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, #eef4fb 100%);
            padding: 12px 16px;
        }

        .main-container {
            width: min(1180px, 100%);
            height: calc(100vh - 24px);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .topbar {
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            display: inline-grid;
            place-items: center;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--sky));
            color: #fff;
            font-size: 1.35rem;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.24);
        }

        .brand h1 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 950;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .brand p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
            background: var(--soft-green);
            color: var(--green);
            font-size: 0.76rem;
            font-weight: 850;
            vertical-align: middle;
        }

        .logout-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            min-height: 42px;
            padding: 0 1rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text);
            font-weight: 850;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
            transition: 0.25s ease;
        }

        .logout-link:hover {
            color: var(--danger);
            transform: translateY(-1px);
        }

        .dashboard-grid {
            flex: 1 1 auto;
            min-height: 0;
            display: grid;
            grid-template-columns: 2.2fr 1fr;
            grid-template-rows: auto 1fr;
            gap: 12px;
        }

        .card-box {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(219, 228, 240, 0.95);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow);
        }

        .card-box::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--sky));
            z-index: 1;
        }

        .hero-card {
            padding: 18px 22px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb, #0ea5e9);
            color: #fff;
            min-height: 150px;
        }

        .hero-card::after {
            content: "";
            position: absolute;
            right: -40px;
            top: -40px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
        }

        .hero-card .bubble2 {
            position: absolute;
            right: 140px;
            bottom: -55px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.10);
        }

        .welcome-label {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-bottom: 8px;
            padding: 0.32rem 0.7rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            font-size: 0.76rem;
            font-weight: 800;
        }

        .hero-card h2 {
            position: relative;
            z-index: 2;
            margin: 0;
            font-size: clamp(1.4rem, 2vw, 1.9rem);
            line-height: 1.08;
            font-weight: 950;
            letter-spacing: -0.04em;
            max-width: 720px;
        }

        .hero-card p {
            position: relative;
            z-index: 2;
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            line-height: 1.45;
            max-width: 640px;
        }

        .profile-card {
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 150px;
        }

        .avatar {
            width: 56px;
            height: 56px;
            display: inline-grid;
            place-items: center;
            border-radius: 18px;
            background: var(--soft-blue);
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 12px;
        }

        .profile-card h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 950;
            line-height: 1.2;
        }

        .profile-card p {
            margin: 8px 0 12px;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.35;
        }

        .profile-state {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 10px 14px;
            border-radius: 16px;
            background: var(--soft-green);
            color: var(--green);
            font-size: 0.88rem;
            font-weight: 850;
        }

        .section-card {
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            margin: 0 0 12px;
            font-size: 1rem;
            font-weight: 950;
            line-height: 1.2;
            flex: 0 0 auto;
        }

        .section-title i {
            color: var(--primary);
        }

        .modules-grid {
            flex: 1 1 auto;
            min-height: 0;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .module {
            position: relative;
            overflow: hidden;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 10px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.12), transparent 8rem),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            color: var(--text);
            text-decoration: none;
            transition: 0.25s ease;
        }

        .module:hover {
            color: var(--text);
            border-color: rgba(37, 99, 235, 0.38);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.09);
        }

        .module-header {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .module-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 950;
            line-height: 1.15;
        }

        .module-text {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.35;
        }

        .module-icon {
            width: 44px;
            height: 44px;
            display: inline-grid;
            place-items: center;
            flex: 0 0 auto;
            border-radius: 14px;
            background: var(--soft-blue);
            color: var(--primary);
            font-size: 1.1rem;
        }

        .module-big-icon {
            position: relative;
            z-index: 1;
            width: 118px;
            height: 118px;
            display: grid;
            place-items: center;
            align-self: center;
            margin: 4px 0;
            border-radius: 34px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.14), rgba(14, 165, 233, 0.12));
            color: var(--primary);
            font-size: 3.9rem;
            box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.08);
        }

        .module-big-icon i,
        .module-icon i {
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .module-big-icon::after {
            content: "";
            position: absolute;
            inset: 12px;
            border-radius: 26px;
            border: 1px dashed rgba(37, 99, 235, 0.22);
        }

        .module-footer {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .module-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.55rem;
            border-radius: 999px;
            background: #f8fafc;
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 800;
            border: 1px solid #edf2f7;
        }

        .module-action {
            display: inline-flex;
            align-items: center;
            gap: 0.34rem;
            color: var(--primary);
            font-size: 0.84rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .summary-list {
            flex: 1 1 auto;
            min-height: 0;
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 12px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #fff;
            min-height: 112px;
            transition: 0.25s ease;
        }

        .summary-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.07);
        }

        .summary-icon {
            width: 64px;
            height: 64px;
            min-width: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            background: linear-gradient(135deg, #eaf1ff 0%, #dbeafe 100%);
            color: var(--primary);
            font-size: 1.85rem;
            overflow: hidden;
        }

        .summary-icon i {
            width: 100%;
            height: 100%;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            font-size: 1.85rem;
            text-align: center;
        }

        .summary-icon i::before {
            display: block;
            line-height: 1 !important;
            margin: 0 !important;
        }

        .summary-item strong {
            display: block;
            font-size: 0.96rem;
            font-weight: 950;
            line-height: 1.15;
            margin-bottom: 4px;
        }

        .summary-item span {
            display: block;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.35;
        }

        @media (max-width: 992px) {

            html,
            body {
                height: auto;
            }

            body {
                padding: 12px;
            }

            .main-container {
                height: auto;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }

            .modules-grid {
                grid-template-columns: 1fr;
            }

            .module {
                min-height: 210px;
            }

            .module-big-icon {
                width: 95px;
                height: 95px;
                font-size: 3rem;
            }

            .summary-list {
                grid-template-rows: auto;
            }
        }
    </style>
</head>

<body>

    <div class="main-container">

        <header class="topbar">
            <div class="brand">
                <span class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <div>
                    <h1>Panel Escolástico</h1>
                    <p>
                        <?= $nombreUsuario ?>
                        <span class="badge-role ms-2">
                            <i class="bi bi-person-badge-fill"></i>
                            <?= $rolUsuario ?>
                        </span>
                    </p>
                </div>
            </div>

            <a href="../../controller/AuthController.php?accion=logout" class="logout-link">
                <i class="bi bi-box-arrow-right"></i>
                Salir
            </a>
        </header>

        <main class="dashboard-grid">

            <section class="card-box hero-card">
                <span class="welcome-label">
                    <i class="bi bi-stars"></i>
                    Bienvenido/a al sistema académico
                </span>

                <h2>Gestiona tu información escolar desde un panel más visual.</h2>

                <p>
                    Accede a documentos, calificaciones y asistencia de forma rápida,
                    ordenada.
                </p>

                <span class="bubble2"></span>
            </section>

            <aside class="card-box profile-card">
                <div class="avatar">
                    <i class="bi bi-person-circle"></i>
                </div>

                <h3><?= $nombreUsuario ?></h3>
                <p>Perfil activo dentro del Sistema Escolástico.</p>

                <div class="profile-state">
                    <i class="bi bi-check-circle-fill"></i>
                    Sesión iniciada correctamente
                </div>
            </aside>

            <section class="card-box section-card">
                <h2 class="section-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Accesos principales
                </h2>

                <div class="modules-grid">

                    <?php if ($esSecretaria): ?>

                        <a href="../alumnos/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Archivos PDF</p>
                                    <p class="module-text">Carga y revisión de documentos escolares.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Documentos</span>
                                <span class="module-action">Abrir módulo <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="../notas/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Notas</p>
                                    <p class="module-text">Registro y consulta de calificaciones.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-journal-check"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-journal-check"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Calificaciones</span>
                                <span class="module-action">Abrir módulo <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="../asistencia/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Asistencia</p>
                                    <p class="module-text">Control de faltas, porcentajes y reportes.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Registros</span>
                                <span class="module-action">Abrir módulo <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                    <?php else: ?>

                        <a href="../alumnos/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mis archivos</p>
                                    <p class="module-text">Consulta documentos PDF disponibles.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Documentos</span>
                                <span class="module-action">Ver archivos <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="../notas/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mis notas</p>
                                    <p class="module-text">Revisa tus calificaciones y reportes.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-journal-check"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-journal-check"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Calificaciones</span>
                                <span class="module-action">Ver notas <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="../asistencia/formulario.php" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mi asistencia</p>
                                    <p class="module-text">Consulta faltas y porcentajes.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-calendar-check-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Registros</span>
                                <span class="module-action">Ver asistencia <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                    <?php endif; ?>

                </div>
            </section>

            <aside class="card-box section-card">
                <h2 class="section-title">
                    <i class="bi bi-info-circle-fill"></i>
                    Resumen del panel
                </h2>

                <div class="summary-list">
                    <div class="summary-item">
                        <span class="summary-icon">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </span>
                        <div>
                            <strong>Documentos</strong>
                            <span>Acceso rápido a archivos académicos cargados en el sistema.</span>
                        </div>
                    </div>

                    <div class="summary-item">
                        <span class="summary-icon">
                            <i class="bi bi-journal-check"></i>
                        </span>
                        <div>
                            <strong>Rendimiento</strong>
                            <span>Consulta de notas y reportes para revisar el avance académico.</span>
                        </div>
                    </div>

                    <div class="summary-item">
                        <span class="summary-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </span>
                        <div>
                            <strong>Asistencia</strong>
                            <span>Verifica faltas, porcentajes y registros de presencia.</span>
                        </div>
                    </div>
                </div>
            </aside>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>