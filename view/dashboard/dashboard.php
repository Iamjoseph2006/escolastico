<?php
require_once __DIR__ . '/../../config/Auth.php';
require_once __DIR__ . '/../../config/database.php';

require_login();

$usuario = auth_user();

$nombreUsuario = htmlspecialchars($usuario['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
$rolUsuarioRaw = $usuario['rol'] ?? 'usuario';
$rolUsuario = htmlspecialchars(ucfirst($rolUsuarioRaw), ENT_QUOTES, 'UTF-8');

$esSecretaria = function_exists('is_secretaria') && is_secretaria();
$idAlumno = auth_alumno_id();

$rutaRegistrarAlumnos = '../alumnos/formulario.php';
$rutaArchivos = '../archivos/formulario.php';
$rutaNotas = '../notas/formulario.php';
$rutaAsistencia = '../asistencia/formulario.php';

$rutaReporteAlumno = '#';

if ($idAlumno !== null && $idAlumno !== '') {
    $rutaReporteAlumno = '../../controller/ReporteAlumnoController.php?accion=reporte&id=' . urlencode((string)$idAlumno);
}

$resumenPanel = [
    'usuarios_activos' => 0,
    'notas' => 0,
    'asistencias' => 0,
    'documentos' => 0
];

function contarRegistrosPanel($db, $sql, $params = [])
{
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

try {
    $db = Database::connect();

    if ($esSecretaria) {
        $resumenPanel['usuarios_activos'] = contarRegistrosPanel(
            $db,
            "SELECT COUNT(*) FROM usuarios WHERE estado = 'activo'"
        );

        $resumenPanel['notas'] = contarRegistrosPanel(
            $db,
            "SELECT COUNT(*) FROM notas"
        );

        $resumenPanel['asistencias'] = contarRegistrosPanel(
            $db,
            "SELECT COUNT(*) FROM asistencias"
        );

        $resumenPanel['documentos'] = contarRegistrosPanel(
            $db,
            "SELECT COUNT(*) FROM archivos"
        );
    } else {
        if ($idAlumno !== null) {
            $resumenPanel['notas'] = contarRegistrosPanel(
                $db,
                "SELECT COUNT(*) FROM notas WHERE id_alumno = ?",
                [$idAlumno]
            );

            $resumenPanel['asistencias'] = contarRegistrosPanel(
                $db,
                "SELECT COUNT(*) FROM asistencias WHERE id_alumno = ?",
                [$idAlumno]
            );

            $resumenPanel['documentos'] = contarRegistrosPanel(
                $db,
                "SELECT COUNT(*) FROM archivos WHERE id_alumno = ?",
                [$idAlumno]
            );
        }
    }
} catch (PDOException $e) {
    $resumenPanel = [
        'usuarios_activos' => 0,
        'notas' => 0,
        'asistencias' => 0,
        'documentos' => 0
    ];
}
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
            --soft-red: #fff1f2;
            --shadow: 0 16px 38px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
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
            min-height: calc(100vh - 24px);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            display: inline-grid;
            place-items: center;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary), var(--sky));
            color: #ffffff;
            font-size: 1.6rem;
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.22);
            flex: 0 0 auto;
        }

        .brand-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 950;
            letter-spacing: -0.04em;
            line-height: 1.1;
        }

        .brand-subtitle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
            color: var(--muted);
            font-size: 0.94rem;
            min-width: 0;
            flex-wrap: wrap;
        }

        .brand-subtitle span:first-child {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--soft-green);
            color: var(--green);
            font-size: 0.8rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--text);
            text-decoration: none;
            font-weight: 900;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
            transition: 0.25s ease;
            white-space: nowrap;
        }

        .logout-btn:hover {
            color: var(--danger);
            transform: translateY(-1px);
            border-color: rgba(220, 38, 38, 0.22);
        }

        .dashboard-grid {
            flex: 1;
            min-height: 0;
            display: grid;
            grid-template-columns: minmax(0, 2.2fr) minmax(320px, 0.95fr);
            grid-template-rows: 225px minmax(0, 1fr);
            gap: 12px;
        }

        .hero-card,
        .profile-card,
        .card-box {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(219, 228, 240, 0.92);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .hero-card {
            padding: 28px;
            background: linear-gradient(135deg, #2b57e8 0%, #0ea5e9 100%);
            color: #ffffff;
        }

        .hero-card::before,
        .hero-card::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
        }

        .hero-card::before {
            width: 150px;
            height: 150px;
            right: -25px;
            top: -35px;
        }

        .hero-card::after {
            width: 170px;
            height: 170px;
            right: 150px;
            bottom: -90px;
        }

        .hero-badge {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.18);
            font-size: 0.82rem;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .hero-title {
            position: relative;
            z-index: 2;
            width: min(760px, 100%);
            margin: 0;
            font-size: clamp(1.65rem, 3.1vw, 2.25rem);
            font-weight: 950;
            line-height: 1.08;
            letter-spacing: -0.06em;
        }

        .hero-text {
            position: relative;
            z-index: 2;
            margin: 12px 0 0;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.92);
        }

        .profile-card {
            padding: 20px 22px;
            border-top: 5px solid var(--sky);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-icon {
            width: 54px;
            height: 54px;
            display: inline-grid;
            place-items: center;
            border-radius: 18px;
            background: var(--soft-blue);
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 12px;
            flex: 0 0 auto;
        }

        .profile-card h2 {
            margin: 0 0 7px;
            font-size: 1.16rem;
            font-weight: 950;
            letter-spacing: -0.04em;
            line-height: 1.16;
            word-break: break-word;
        }

        .profile-card p {
            margin: 0;
            color: var(--muted);
            font-size: 0.92rem;
            line-height: 1.3;
        }

        .session-ok {
            width: 100%;
            min-height: 42px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 10px 13px;
            border-radius: 15px;
            background: var(--soft-green);
            color: var(--green);
            font-size: 0.9rem;
            font-weight: 900;
            line-height: 1.15;
            overflow: hidden;
        }

        .session-ok i {
            flex: 0 0 auto;
            font-size: 1rem;
        }

        .card-box {
            padding: 18px;
            border-top: 5px solid var(--sky);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin: 0 0 14px;
            font-size: 1.05rem;
            font-weight: 950;
            letter-spacing: -0.03em;
        }

        .section-title i {
            color: var(--primary);
        }

        .modules-grid {
            height: calc(100% - 38px);
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .module {
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 16px;
            border: 1px solid rgba(219, 228, 240, 0.95);
            border-radius: 20px;
            background: linear-gradient(145deg, #ffffff 0%, #f8fbff 100%);
            color: var(--text);
            text-decoration: none;
            transition: 0.25s ease;
            overflow: hidden;
            min-width: 0;
        }

        .module:hover {
            transform: translateY(-2px);
            border-color: rgba(37, 99, 235, 0.35);
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.10);
        }

        .module-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            min-width: 0;
        }

        .module-header > div {
            min-width: 0;
        }

        .module-title {
            margin: 0 0 6px;
            font-size: 0.96rem;
            font-weight: 950;
            line-height: 1.12;
            letter-spacing: -0.04em;
            overflow-wrap: anywhere;
        }

        .module-text {
            margin: 0;
            color: var(--muted);
            font-size: 0.84rem;
            line-height: 1.28;
            overflow-wrap: anywhere;
        }

        .module-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 auto;
            display: inline-grid;
            place-items: center;
            border-radius: 15px;
            background: var(--soft-blue);
            color: var(--primary);
            font-size: 1.05rem;
        }

        .module-big-icon {
            width: 110px;
            height: 110px;
            display: grid;
            place-items: center;
            align-self: center;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.13), rgba(14, 165, 233, 0.12));
            border: 1px solid rgba(37, 99, 235, 0.12);
            color: var(--primary);
            font-size: 3.6rem;
        }

        .module-big-icon i {
            display: inline-grid;
            place-items: center;
            width: 68px;
            height: 68px;
            border-radius: 20px;
            border: 1px dashed rgba(37, 99, 235, 0.25);
        }

        .module-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            min-width: 0;
        }

        .module-chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(219, 228, 240, 0.95);
            background: rgba(255, 255, 255, 0.9);
            color: var(--muted);
            font-size: 0.72rem;
            font-weight: 900;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .module-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--primary);
            font-weight: 950;
            font-size: 0.82rem;
            white-space: nowrap;
            min-width: 0;
        }

        .summary-list {
            height: calc(100% - 38px);
            display: grid;
            grid-auto-rows: 1fr;
            gap: 10px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 14px 18px;
            border: 1px solid rgba(219, 228, 240, 0.95);
            border-radius: 18px;
            background: #ffffff;
            min-width: 0;
        }

        .summary-icon {
            width: 58px;
            height: 58px;
            flex: 0 0 auto;
            display: inline-grid;
            place-items: center;
            border-radius: 18px;
            background: var(--soft-blue);
            color: var(--primary);
            font-size: 1.55rem;
        }

        .summary-item div {
            min-width: 0;
        }

        .summary-number {
            display: inline-block;
            min-width: 42px;
            margin-right: 14px;
            color: var(--primary);
            font-size: 2.3rem;
            font-weight: 950;
            line-height: 1;
            vertical-align: middle;
        }

        .summary-label {
            display: inline-block;
            color: var(--text);
            font-size: 1rem;
            font-weight: 950;
            vertical-align: middle;
        }

        @media (max-width: 1100px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto auto;
            }

            .modules-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                height: auto;
            }

            .module {
                min-height: 250px;
            }

            .summary-list {
                height: auto;
            }
        }

        @media (max-width: 650px) {
            body {
                padding: 10px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .logout-btn {
                align-self: flex-end;
            }

            .dashboard-grid {
                gap: 10px;
            }

            .hero-card,
            .profile-card,
            .card-box {
                border-radius: 20px;
            }

            .hero-card {
                padding: 22px;
            }

            .modules-grid {
                grid-template-columns: 1fr;
            }

            .module {
                min-height: 230px;
            }

            .brand-title {
                font-size: 1.18rem;
            }

            .brand-subtitle {
                flex-wrap: wrap;
            }

            .summary-number {
                font-size: 2rem;
                min-width: 34px;
            }

            .summary-label {
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <main class="main-container">

        <header class="topbar">
            <div class="brand">
                <span class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>

                <div>
                    <h1 class="brand-title">Panel Escolástico</h1>
                    <div class="brand-subtitle">
                        <span><?= $nombreUsuario ?></span>
                        <span class="role-badge">
                            <i class="bi bi-person-badge-fill"></i>
                            <?= $rolUsuario ?>
                        </span>
                    </div>
                </div>
            </div>

            <a href="../../controller/AuthController.php?accion=logout" class="logout-btn">
                <i class="bi bi-box-arrow-right"></i>
                Salir
            </a>
        </header>

        <section class="dashboard-grid">

            <article class="hero-card">
                <span class="hero-badge">
                    <i class="bi bi-stars"></i>
                    Bienvenido/a al sistema académico
                </span>

                <h2 class="hero-title">
                    Accede a la información académica de forma rápida, clara y segura.
                </h2>

                <p class="hero-text">
                    Consulta o gestiona documentos, calificaciones y asistencia según tu rol dentro del sistema.
                </p>
            </article>

            <aside class="profile-card">
                <span class="profile-icon">
                    <i class="bi bi-person-circle"></i>
                </span>

                <h2><?= $nombreUsuario ?></h2>

                <p>Perfil activo dentro del Sistema Escolástico.</p>

                <div class="session-ok">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Sesión iniciada correctamente</span>
                </div>
            </aside>

            <section class="card-box">
                <h2 class="section-title">
                    <i class="bi bi-lightning-charge-fill"></i>
                    Accesos principales
                </h2>

                <div class="modules-grid">

                    <?php if ($esSecretaria): ?>

                        <a href="<?= $rutaRegistrarAlumnos ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Registrar alumnos</p>
                                    <p class="module-text">Ingreso y control de estudiantes.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-person-plus-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Alumnos</span>
                                <span class="module-action">Abrir <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaArchivos ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Archivos PDF</p>
                                    <p class="module-text">Carga y revisión de documentos.</p>
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
                                <span class="module-action">Abrir <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaNotas ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Notas</p>
                                    <p class="module-text">Registro de calificaciones.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-journal-check"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-journal-check"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Notas</span>
                                <span class="module-action">Abrir <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaAsistencia ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Asistencia</p>
                                    <p class="module-text">Control de faltas y reportes.</p>
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
                                <span class="module-action">Abrir <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                    <?php else: ?>

                        <a href="<?= $rutaReporteAlumno ?>" class="module" target="_blank">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mi reporte PDF</p>
                                    <p class="module-text">Imprime tus datos personales.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-file-earmark-person-fill"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-file-earmark-person-fill"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Reporte</span>
                                <span class="module-action">Imprimir <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaArchivos ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mis archivos</p>
                                    <p class="module-text">Consulta documentos PDF.</p>
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
                                <span class="module-action">Ver <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaNotas ?>" class="module">
                            <div class="module-header">
                                <div>
                                    <p class="module-title">Mis notas</p>
                                    <p class="module-text">Revisa tus calificaciones.</p>
                                </div>
                                <span class="module-icon">
                                    <i class="bi bi-journal-check"></i>
                                </span>
                            </div>

                            <div class="module-big-icon">
                                <i class="bi bi-journal-check"></i>
                            </div>

                            <div class="module-footer">
                                <span class="module-chip">Notas</span>
                                <span class="module-action">Ver <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                        <a href="<?= $rutaAsistencia ?>" class="module">
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
                                <span class="module-action">Ver <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </a>

                    <?php endif; ?>

                </div>
            </section>

            <aside class="card-box">
                <h2 class="section-title">
                    <i class="bi bi-info-circle-fill"></i>
                    Resumen del panel
                </h2>

                <div class="summary-list">

                    <?php if ($esSecretaria): ?>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-people-fill"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['usuarios_activos'] ?></span>
                                <span class="summary-label">Usuarios activos</span>
                            </div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-journal-check"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['notas'] ?></span>
                                <span class="summary-label">Notas registradas</span>
                            </div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-calendar-check-fill"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['asistencias'] ?></span>
                                <span class="summary-label">Asistencias</span>
                            </div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['documentos'] ?></span>
                                <span class="summary-label">Documentos</span>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-journal-check"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['notas'] ?></span>
                                <span class="summary-label">Mis notas</span>
                            </div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-calendar-check-fill"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['asistencias'] ?></span>
                                <span class="summary-label">Mi asistencia</span>
                            </div>
                        </div>

                        <div class="summary-item">
                            <span class="summary-icon">
                                <i class="bi bi-file-earmark-pdf-fill"></i>
                            </span>
                            <div>
                                <span class="summary-number"><?= $resumenPanel['documentos'] ?></span>
                                <span class="summary-label">Mis documentos</span>
                            </div>
                        </div>

                    <?php endif; ?>

                </div>
            </aside>

        </section>

    </main>
</body>

</html>