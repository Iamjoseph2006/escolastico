<?php
require_once '../../config/Auth.php';
require_secretaria();

require_once '../../model/Asistencia.php';
require_once '../../model/Alumno.php';

$asistencia = new Asistencia();
$alumno = new Alumno();

$data = [
    'id_asistencia' => '',
    'materia' => '',
    'creditos' => '',
    'horas_credito' => '',
    'numero_faltas' => '',
    'horas_faltas' => '',
    'porcentaje_asistencia' => '',
    'porcentaje_inasistencia' => '',
    'id_alumno' => ''
];

if (isset($_GET['id'])) {
    $data = $asistencia->obtenerPorId($_GET['id']) ?? $data;
}

$asistencias = $asistencia->obtenerTodos();
$alumnos = $alumno->obtenerTodo();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
            --info: #0891b2;
            --info-soft: #ecfeff;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            padding: 0.7rem 1rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.76);
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            backdrop-filter: blur(16px);
            transition: color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .back-link:hover {
            border-color: rgba(37, 99, 235, 0.28);
            color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 14px 32px rgba(37, 99, 235, 0.12);
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

        .title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin: 0 0 1.6rem;
            color: var(--text);
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            text-align: center;
        }

        .title i {
            display: inline-grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 15px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 1.2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-group i {
            position: absolute;
            top: 50%;
            left: 1rem;
            z-index: 2;
            color: var(--muted);
            transform: translateY(-50%);
            transition: color 0.2s ease;
        }

        .form-group:focus-within i {
            color: var(--primary);
        }

        .form-control,
        .form-select {
            min-height: 52px;
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            background-color: rgba(255, 255, 255, 0.94);
            color: var(--text);
            font-size: 0.95rem;
            padding-left: 2.75rem;
            box-shadow: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .form-control::placeholder {
            color: #98a2b3;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(37, 99, 235, 0.55);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control[readonly] {
            background-color: #f8fafc;
            color: #64748b;
            cursor: not-allowed;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.85rem;
            margin-top: 0.35rem;
        }

        .btn-save,
        .btn-clear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 52px;
            border-radius: var(--radius-sm);
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
        }

        .btn-save {
            width: 100%;
            border: none;
            background: linear-gradient(135deg, var(--primary), #0ea5e9);
            color: #ffffff;
            padding: 0 1.4rem;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.22);
        }

        .btn-save:hover {
            background: linear-gradient(135deg, var(--primary-dark), #0284c7);
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
        }

        .btn-clear {
            width: auto;
            min-width: 150px;
            border: 1px solid var(--line) !important;
            background: #ffffff !important;
            color: #475467 !important;
            padding: 0 1.25rem;
        }

        .btn-clear:hover {
            border-color: #cbd5e1 !important;
            background: #f8fafc !important;
            color: var(--text) !important;
            transform: translateY(-1px);
        }

        .table-responsive {
            padding: 0.15rem;
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

        .table tbody tr {
            border-radius: var(--radius-md);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .table-hover tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.08);
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

        .badge-asistencia,
        .badge-inasistencia {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.7rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-asistencia {
            background: var(--success-soft);
            color: var(--success);
        }

        .badge-inasistencia {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .badge-asistencia::before,
        .badge-inasistencia::before {
            content: "";
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 999px;
            background: currentColor;
        }

        .action-btn {
            display: inline-grid;
            place-items: center;
            width: 40px;
            height: 40px;
            margin: 0 0.12rem;
            border: 1px solid transparent;
            border-radius: 12px;
            background: transparent;
            font-size: 1.05rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .edit-btn {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .delete-btn {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0.75rem 2rem;
            }

            .card-custom {
                padding: 1.15rem;
                border-radius: 22px;
            }

            .title {
                align-items: flex-start;
                justify-content: flex-start;
                text-align: left;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .btn-clear {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="main-container">

    <!-- Volver -->
    <a href="../dashboard/dashboard.php" class="back-link">
        <i class="bi bi-arrow-left-circle"></i> Volver al dashboard
    </a>

    <!-- Tarjeta formulario -->
    <div class="card-custom">

        <h4 class="title">
            <i class="bi bi-calendar-check-fill"></i>
            <?= isset($_GET['id']) ? 'Editar Asistencia' : 'Registro de Asistencia' ?>
        </h4>

        <form action="../../controller/AsistenciaController.php" method="POST" onsubmit="calcularAsistencia()">

            <input
                type="hidden"
                name="id_asistencia"
                value="<?= htmlspecialchars($data['id_asistencia']) ?>"
            >

            <?php if (isset($_GET['id'])): ?>
                <input type="hidden" name="accion" value="editar">
            <?php endif; ?>

            <div class="row">

                <!-- Materia -->
                <div class="col-md-4">
                    <div class="form-group">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="materia"
                            name="materia"
                            placeholder="Materia"
                            value="<?= htmlspecialchars($data['materia']) ?>"
                            required
                        >
                    </div>
                </div>

                <!-- Alumno -->
                <div class="col-md-4">
                    <div class="form-group">
                        <i class="bi bi-person-fill"></i>
                        <select
                            class="form-select"
                            id="id_alumno"
                            name="id_alumno"
                            required
                        >
                            <option value="">Seleccione alumno</option>
                            <?php foreach ($alumnos as $a): ?>
                                <option
                                    value="<?= htmlspecialchars($a['id_alumno']) ?>"
                                    <?= $data['id_alumno'] == $a['id_alumno'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Créditos -->
                <div class="col-md-2">
                    <div class="form-group">
                        <i class="bi bi-book-half"></i>
                        <input
                            type="number"
                            class="form-control"
                            id="creditos"
                            name="creditos"
                            placeholder="Créditos"
                            value="<?= htmlspecialchars($data['creditos']) ?>"
                            oninput="calcularAsistencia()"
                            required
                        >
                    </div>
                </div>

                <!-- Faltas -->
                <div class="col-md-2">
                    <div class="form-group">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <input
                            type="number"
                            class="form-control"
                            id="numero_faltas"
                            name="numero_faltas"
                            placeholder="Faltas"
                            value="<?= htmlspecialchars($data['numero_faltas']) ?>"
                            oninput="calcularAsistencia()"
                            required
                        >
                    </div>
                </div>

                <!-- Horas crédito -->
                <div class="col-md-3">
                    <div class="form-group">
                        <i class="bi bi-clock-history"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="horas_credito"
                            name="horas_credito"
                            placeholder="Horas crédito"
                            value="<?= htmlspecialchars($data['horas_credito']) ?>"
                            readonly
                        >
                    </div>
                </div>

                <!-- Horas faltas -->
                <div class="col-md-3">
                    <div class="form-group">
                        <i class="bi bi-clock-fill"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="horas_faltas"
                            name="horas_faltas"
                            placeholder="Horas faltas"
                            value="<?= htmlspecialchars($data['horas_faltas']) ?>"
                            readonly
                        >
                    </div>
                </div>

                <!-- % Asistencia -->
                <div class="col-md-3">
                    <div class="form-group">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="porcentaje_asistencia"
                            name="porcentaje_asistencia"
                            placeholder="% Asistencia"
                            value="<?= htmlspecialchars($data['porcentaje_asistencia']) ?>"
                            readonly
                        >
                    </div>
                </div>

                <!-- % Inasistencia -->
                <div class="col-md-3">
                    <div class="form-group">
                        <i class="bi bi-pie-chart-fill"></i>
                        <input
                            type="text"
                            class="form-control"
                            id="porcentaje_inasistencia"
                            name="porcentaje_inasistencia"
                            placeholder="% Inasistencia"
                            value="<?= htmlspecialchars($data['porcentaje_inasistencia']) ?>"
                            readonly
                        >
                    </div>
                </div>

            </div>

            <div class="actions-grid">
                <button type="submit" class="btn-save">
                    <i class="bi bi-save me-2"></i>
                    <?= isset($_GET['id']) ? 'Actualizar Asistencia' : 'Guardar Asistencia' ?>
                </button>

                <a href="formulario.php" class="btn btn-outline-secondary btn-clear">
                    <i class="bi bi-x-circle me-2"></i>
                    Limpiar
                </a>
            </div>

        </form>
    </div>

    <!-- Tarjeta tabla -->
    <div class="card-custom">

        <h4 class="title">
            <i class="bi bi-table"></i>
            Lista de Asistencias
        </h4>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID Registro</th>
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Créditos</th>
                        <th>Faltas</th>
                        <th>% Asistencia</th>
                        <th>% Inasistencia</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($asistencias as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['id_asistencia']) ?></td>
                            <td><?= htmlspecialchars($a['nombres'] . ' ' . $a['apellidos']) ?></td>
                            <td><?= htmlspecialchars($a['materia']) ?></td>
                            <td><?= htmlspecialchars($a['creditos']) ?></td>
                            <td><?= htmlspecialchars($a['numero_faltas']) ?></td>

                            <td>
                                <span class="badge-asistencia">
                                    <?= htmlspecialchars(number_format((float)$a['porcentaje_asistencia'], 2)) ?>%
                                </span>
                            </td>

                            <td>
                                <span class="badge-inasistencia">
                                    <?= htmlspecialchars(number_format((float)$a['porcentaje_inasistencia'], 2)) ?>%
                                </span>
                            </td>

                            <td class="text-center">
                                <a
                                    href="formulario.php?id=<?= $a['id_asistencia'] ?>"
                                    class="action-btn edit-btn"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a
                                    href="../../controller/AsistenciaController.php?eliminar=<?= $a['id_asistencia'] ?>"
                                    class="action-btn delete-btn"
                                    title="Eliminar"
                                    onclick="return confirm('¿Está seguro de eliminar este registro?');"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </a>

                                <a
                                    href="../../controller/ReporteAsistenciaController.php?accion=reporte&id=<?= $a['id_asistencia'] ?>"
                                    target="_blank"
                                    class="btn btn-sm btn-primary"
                                    title="Descargar PDF"
                                >
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<script>
function calcularAsistencia() {
    let creditos = parseFloat(document.getElementById('creditos').value) || 0;
    let faltas = parseFloat(document.getElementById('numero_faltas').value) || 0;

    let horas_credito = creditos * 20;
    let horas_faltas = faltas * 2;

    let porcentaje_inasistencia = 0;
    if (horas_credito > 0) {
        porcentaje_inasistencia = (horas_faltas * 100) / horas_credito;
    }

    let porcentaje_asistencia = 100 - porcentaje_inasistencia;

    if (porcentaje_asistencia < 0) porcentaje_asistencia = 0;
    if (porcentaje_inasistencia < 0) porcentaje_inasistencia = 0;

    document.getElementById('horas_credito').value = horas_credito.toFixed(2);
    document.getElementById('horas_faltas').value = horas_faltas.toFixed(2);
    document.getElementById('porcentaje_asistencia').value = porcentaje_asistencia.toFixed(2);
    document.getElementById('porcentaje_inasistencia').value = porcentaje_inasistencia.toFixed(2);
}

document.addEventListener('DOMContentLoaded', calcularAsistencia);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
