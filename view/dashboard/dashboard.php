<?php
require_once __DIR__ . '/../../config/Auth.php';
require_once __DIR__ . '/../../model/Alumno.php';
require_once __DIR__ . '/../../model/Notas.php';
require_once __DIR__ . '/../../model/Asistencia.php';

require_login();

$usuario = auth_user();
$alumnoModel = new Alumno();
$notasModel = new Notas();
$asistenciaModel = new Asistencia();

$alumnos = $alumnoModel->obtenerTodo();
$notas = $notasModel->obtenerTodos();
$asistencias = $asistenciaModel->obtenerTodos();

if (is_alumno()) {
    $idAlumno = auth_alumno_id();
    $alumnoActual = $idAlumno ? $alumnoModel->obtenerPorId($idAlumno) : null;
    $notas = array_values(array_filter($notas, function ($nota) use ($idAlumno) {
        return (string)$nota['id_alumno'] === (string)$idAlumno;
    }));
    $asistencias = array_values(array_filter($asistencias, function ($asistencia) use ($idAlumno) {
        return (string)$asistencia['id_alumno'] === (string)$idAlumno;
    }));
} else {
    $alumnoActual = null;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Escolastico</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f4f7fb;
            --surface: rgba(255, 255, 255, 0.92);
            --text: #172033;
            --muted: #667085;
            --line: #e6eaf0;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #eaf1ff;
            --success: #16a34a;
            --success-soft: #e9f9ef;
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --warning: #d97706;
            --warning-soft: #fffbeb;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            --radius-lg: 28px;
            --radius-md: 18px;
            --radius-sm: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.13), transparent 32rem),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.11), transparent 30rem),
                var(--bg);
            color: var(--text);
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 2rem 1rem 3rem;
        }

        .main-container {
            width: min(1200px, 100%);
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .brand-icon {
            display: inline-grid;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 1.3rem;
        }

        .brand h1 {
            margin: 0;
            font-size: 1.45rem;
            font-weight: 900;
        }

        .brand p {
            margin: 0.1rem 0 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .logout-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 44px;
            padding: 0 1rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.76);
            color: var(--text);
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            transition: color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .logout-link:hover {
            color: var(--danger);
            transform: translateY(-1px);
            box-shadow: 0 14px 32px rgba(220, 38, 38, 0.12);
        }

        .card-custom {
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            border: 1px solid rgba(230, 234, 240, 0.95);
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .card-custom::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), #0ea5e9);
        }

        .stats-grid,
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .stat,
        .module {
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            background: #ffffff;
            padding: 1rem;
        }

        .stat span {
            display: block;
            color: var(--muted);
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .stat strong {
            display: block;
            margin-top: 0.25rem;
            color: var(--primary);
            font-size: 2rem;
            line-height: 1;
        }

        .module {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            text-decoration: none;
            color: var(--text);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .module:hover {
            border-color: rgba(37, 99, 235, 0.28);
            color: var(--text);
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
        }

        .module-icon {
            display: inline-grid;
            place-items: center;
            flex: 0 0 auto;
            width: 46px;
            height: 46px;
            border-radius: 15px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 1.25rem;
        }

        .module-title {
            margin: 0;
            font-weight: 900;
        }

        .module-text {
            margin: 0.15rem 0 0;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 0.55rem;
        }

        .table thead th {
            border: 0;
            background: transparent;
            color: var(--muted);
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            white-space: nowrap;
        }

        .table tbody td {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            background: #ffffff;
            color: #344054;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            border-left: 1px solid var(--line);
            border-top-left-radius: var(--radius-md);
            border-bottom-left-radius: var(--radius-md);
            color: var(--primary);
            font-weight: 800;
        }

        .table tbody td:last-child {
            border-right: 1px solid var(--line);
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }

        .title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0 0 1.2rem;
            font-size: 1.15rem;
            font-weight: 900;
        }

        .title i {
            color: var(--primary);
        }

        .badge-role {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
            background: var(--success-soft);
            color: var(--success);
            font-size: 0.82rem;
            font-weight: 800;
        }

        @media (max-width: 850px) {
            body {
                padding: 1rem 0.75rem 2rem;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .stats-grid,
            .modules-grid {
                grid-template-columns: 1fr;
            }

            .card-custom {
                padding: 1.15rem;
                border-radius: 22px;
            }
        }
    </style>
</head>

<body>

    <div class="main-container">
        <header class="topbar">
            <div class="brand">
                <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                <div>
                    <h1>Panel Escolastico</h1>
                    <p>
                        <?= htmlspecialchars($usuario['nombre']) ?>
                        <span class="badge-role ms-2">
                            <i class="bi bi-person-badge-fill"></i>
                            <?= htmlspecialchars(ucfirst($usuario['rol'])) ?>
                        </span>
                    </p>
                </div>
            </div>

            <a href="../../controller/AuthController.php?accion=logout" class="logout-link">
                <i class="bi bi-box-arrow-right"></i>
                Salir
            </a>
        </header>

        <section class="card-custom">
            <h2 class="title"><i class="bi bi-grid-1x2-fill"></i> Accesos</h2>

            <div class="modules-grid">
                <?php if (is_secretaria()): ?>
                    <a href="../alumnos/formulario.php" class="module">
                        <div>
                            <p class="module-title">Alumnos</p>
                            <p class="module-text">Registro y administracion</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-people-fill"></i></span>
                    </a>

                    <a href="../notas/formulario.php" class="module">
                        <div>
                            <p class="module-title">Notas</p>
                            <p class="module-text">Calificaciones y reportes</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-journal-check"></i></span>
                    </a>

                    <a href="../asistencia/formulario.php" class="module">
                        <div>
                            <p class="module-title">Asistencia</p>
                            <p class="module-text">Faltas y porcentajes</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-calendar-check-fill"></i></span>
                    </a>
                <?php else: ?>
                    <a href="../../controller/ReporteAlumnoController.php?accion=reporte&id=<?= htmlspecialchars(auth_alumno_id()) ?>" target="_blank" class="module">
                        <div>
                            <p class="module-title">Mi informacion</p>
                            <p class="module-text">Reporte personal</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-person-lines-fill"></i></span>
                    </a>

                    <a href="#mis-notas" class="module">
                        <div>
                            <p class="module-title">Mis notas</p>
                            <p class="module-text">Calificaciones disponibles</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-journal-check"></i></span>
                    </a>

                    <a href="#mi-asistencia" class="module">
                        <div>
                            <p class="module-title">Mi asistencia</p>
                            <p class="module-text">Porcentajes y faltas</p>
                        </div>
                        <span class="module-icon"><i class="bi bi-calendar-check-fill"></i></span>
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <section class="card-custom">
            <h2 class="title"><i class="bi bi-bar-chart-fill"></i> Resumen</h2>

            <div class="stats-grid">
                <div class="stat">
                    <span><?= is_secretaria() ? 'Alumnos' : 'Mi registro' ?></span>
                    <strong><?= is_secretaria() ? count($alumnos) : ($alumnoActual ? 1 : 0) ?></strong>
                </div>
                <div class="stat">
                    <span>Notas</span>
                    <strong><?= count($notas) ?></strong>
                </div>
                <div class="stat">
                    <span>Asistencias</span>
                    <strong><?= count($asistencias) ?></strong>
                </div>
            </div>
        </section>

        <?php if (is_alumno()): ?>
            <section class="card-custom" id="mis-notas">
                <h2 class="title"><i class="bi bi-journal-check"></i> Mis notas</h2>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Nota 1</th>
                                <th>Nota 2</th>
                                <th>Nota 3</th>
                                <th>Promedio</th>
                                <th>Reporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notas as $nota): ?>
                                <tr>
                                    <td><?= htmlspecialchars($nota['materia']) ?></td>
                                    <td><?= htmlspecialchars($nota['nota1']) ?></td>
                                    <td><?= htmlspecialchars($nota['nota2']) ?></td>
                                    <td><?= htmlspecialchars($nota['nota3']) ?></td>
                                    <td><strong><?= number_format($nota['npromedio'], 2) ?></strong></td>
                                    <td>
                                        <a href="../../controller/ReporteNotasController.php?accion=reporte&id=<?= htmlspecialchars($nota['id_nota']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="card-custom" id="mi-asistencia">
                <h2 class="title"><i class="bi bi-calendar-check-fill"></i> Mi asistencia</h2>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Creditos</th>
                                <th>Faltas</th>
                                <th>% Asistencia</th>
                                <th>% Inasistencia</th>
                                <th>Reporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($asistencias as $asistencia): ?>
                                <tr>
                                    <td><?= htmlspecialchars($asistencia['materia']) ?></td>
                                    <td><?= htmlspecialchars($asistencia['creditos']) ?></td>
                                    <td><?= htmlspecialchars($asistencia['numero_faltas']) ?></td>
                                    <td><strong><?= number_format($asistencia['porcentaje_asistencia'], 2) ?>%</strong></td>
                                    <td><?= number_format($asistencia['porcentaje_inasistencia'], 2) ?>%</td>
                                    <td>
                                        <a href="../../controller/ReporteAsistenciaController.php?accion=reporte&id=<?= htmlspecialchars($asistencia['id_asistencia']) ?>" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>