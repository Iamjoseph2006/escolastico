<?php
require_once __DIR__ . '/../../config/Auth.php';
require_secretaria();

require_once __DIR__ . '/../../model/Alumno.php';

$alumno = new Alumno();

$alumnoEditar = null;

if (isset($_GET['editar'])) {
    $alumnoEditar = $alumno->obtenerPorId($_GET['editar']);
}

$alumnos = $alumno->obtenerTodo();

function limpiar($valor)
{
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

$modoEditar = $alumnoEditar !== null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Alumnos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f4f7fb;
            --surface: rgba(255, 255, 255, 0.94);
            --text: #172033;
            --muted: #667085;
            --line: #dbe4ef;
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-soft: #eaf1ff;
            --success: #16a34a;
            --success-soft: #e9f9ef;
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --pdf: #ef4444;
            --pdf-soft: #fff1f2;
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
            padding: 1.4rem 0 3rem;
        }

        .main-container {
            width: min(1160px, 100%);
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-bottom: 1.4rem;
            padding: 0.85rem 1.2rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.85);
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            backdrop-filter: blur(16px);
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
            border-color: rgba(37, 99, 235, 0.28);
            transform: translateY(-1px);
        }

        .card-custom {
            position: relative;
            overflow: hidden;
            margin-bottom: 1.9rem;
            padding: 2rem 1.8rem 1.5rem;
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
            height: 6px;
            background: linear-gradient(90deg, var(--primary), #0ea5e9);
        }

        .title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            margin: 0 0 1.8rem;
            color: var(--text);
            font-size: 1.45rem;
            font-weight: 900;
            letter-spacing: -0.03em;
            text-align: center;
        }

        .title i {
            display: inline-grid;
            place-items: center;
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 1.3rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-group > i.icon-left {
            position: absolute;
            top: 50%;
            left: 1rem;
            z-index: 3;
            color: var(--muted);
            transform: translateY(-50%);
            transition: color 0.2s ease;
        }

        .form-group:focus-within > i.icon-left {
            color: var(--primary);
        }

        .form-control,
        .form-select {
            min-height: 60px;
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            background-color: rgba(255, 255, 255, 0.96);
            color: var(--text);
            font-size: 0.98rem;
            padding-left: 3rem;
            box-shadow: none;
            transition: all 0.2s ease;
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

        .btn-save,
        .btn-clear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 60px;
            border-radius: var(--radius-sm);
            font-weight: 850;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.98rem;
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
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
        }

        .btn-clear {
            width: 100%;
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
            padding: 0.2rem;
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 0.7rem;
        }

        .table thead th {
            border: 0;
            background: transparent;
            color: var(--muted);
            font-size: 0.8rem;
            font-weight: 850;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 0.85rem 1rem;
            white-space: nowrap;
        }

        .table tbody tr {
            border-radius: var(--radius-md);
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
            transition: all 0.2s ease;
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
            font-weight: 850;
        }

        .table tbody td:last-child {
            border-right: 1px solid var(--line);
            border-top-right-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }

        .badge-state {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.48rem 0.78rem;
            border-radius: 999px;
            font-size: 0.83rem;
            font-weight: 850;
            white-space: nowrap;
        }

        .badge-active {
            background: var(--success-soft);
            color: var(--success);
        }

        .badge-inactive {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            flex-wrap: nowrap;
        }

        .action-btn {
            display: inline-grid;
            place-items: center;
            width: 40px;
            height: 40px;
            border: 1px solid transparent;
            border-radius: 12px;
            background: transparent;
            font-size: 1.05rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .edit-btn {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .pdf-btn {
            background: var(--pdf-soft);
            color: var(--pdf);
        }

        .delete-btn {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
        }

        .edit-btn:hover {
            color: var(--primary);
        }

        .pdf-btn:hover {
            color: var(--pdf);
        }

        .delete-btn:hover {
            color: var(--danger);
        }

        .empty-row {
            text-align: center;
            color: var(--muted);
            font-weight: 700;
            padding: 1.4rem !important;
        }

        @media (max-width: 1200px) {
            .main-container {
                width: min(100%, calc(100% - 48px));
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0 2rem;
            }

            .main-container {
                width: calc(100% - 24px);
            }

            .card-custom {
                padding: 1.15rem;
                border-radius: 22px;
            }

            .title {
                font-size: 1.2rem;
                justify-content: flex-start;
                text-align: left;
            }
        }
    </style>
</head>

<body>

<div class="main-container">

    <a href="../dashboard/dashboard.php" class="back-link">
        <i class="bi bi-arrow-left-circle"></i>
        Volver al dashboard
    </a>

    <div class="card-custom">
        <h2 class="title">
            <i class="bi <?= $modoEditar ? 'bi-pencil-square' : 'bi-person-plus-fill' ?>"></i>
            <?= $modoEditar ? 'Editar Alumno' : 'Registro de Alumnos' ?>
        </h2>

        <form action="../../controller/AlumnoController.php" method="POST">

            <?php if ($modoEditar): ?>
                <input type="hidden" name="accion" value="editar">
            <?php else: ?>
                <input type="hidden" name="accion" value="crear">
            <?php endif; ?>

            <div class="row g-3">

                <div class="col-lg-2 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-hash icon-left"></i>
                        <input
                            type="number"
                            name="id_alumno"
                            class="form-control"
                            placeholder="ID del alumno"
                            value="<?= limpiar($alumnoEditar['id_alumno'] ?? '') ?>"
                            <?= $modoEditar ? 'readonly' : 'required' ?>>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-person-fill icon-left"></i>
                        <input
                            type="text"
                            name="nombres"
                            class="form-control"
                            placeholder="Nombres"
                            value="<?= limpiar($alumnoEditar['nombres'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-person-vcard-fill icon-left"></i>
                        <input
                            type="text"
                            name="apellidos"
                            class="form-control"
                            placeholder="Apellidos"
                            value="<?= limpiar($alumnoEditar['apellidos'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-envelope-fill icon-left"></i>
                        <input
                            type="email"
                            name="correo"
                            class="form-control"
                            placeholder="Correo electrónico"
                            value="<?= limpiar($alumnoEditar['correo'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-telephone-fill icon-left"></i>
                        <input
                            type="text"
                            name="telefono"
                            class="form-control"
                            placeholder="Teléfono"
                            value="<?= limpiar($alumnoEditar['telefono'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="form-group">
                        <i class="bi bi-toggle-on icon-left"></i>

                        <?php $estadoActual = strtolower($alumnoEditar['estado'] ?? 'activo'); ?>

                        <select name="estado" class="form-select" required>
                            <option value="activo" <?= $estadoActual === 'activo' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="inactivo" <?= $estadoActual === 'inactivo' ? 'selected' : '' ?>>
                                Inactivo
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <button type="submit" class="btn-save">
                        <i class="bi <?= $modoEditar ? 'bi-check-circle-fill' : 'bi-save-fill' ?>"></i>
                        <?= $modoEditar ? 'Actualizar Alumno' : 'Guardar Alumno' ?>
                    </button>
                </div>

                <div class="col-lg-2 col-md-6">
                    <a href="formulario.php" class="btn-clear">
                        <i class="bi bi-x-circle"></i>
                        Limpiar
                    </a>
                </div>

            </div>
        </form>
    </div>

    <div class="card-custom">
        <h2 class="title">
            <i class="bi bi-table"></i>
            Lista de Alumnos
        </h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($alumnos)): ?>
                        <?php foreach ($alumnos as $a): ?>
                            <?php $estado = strtolower($a['estado'] ?? 'inactivo'); ?>

                            <tr>
                                <td><?= limpiar($a['id_alumno']) ?></td>
                                <td><?= limpiar($a['nombres']) ?></td>
                                <td><?= limpiar($a['apellidos']) ?></td>
                                <td><?= limpiar($a['correo']) ?></td>
                                <td><?= limpiar($a['telefono'] ?? '') ?></td>
                                <td>
                                    <span class="badge-state <?= $estado === 'activo' ? 'badge-active' : 'badge-inactive' ?>">
                                        <i class="bi <?= $estado === 'activo' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?>"></i>
                                        <?= limpiar(ucfirst($estado)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-group">

                                        <a
                                            href="formulario.php?editar=<?= urlencode($a['id_alumno']) ?>"
                                            class="action-btn edit-btn"
                                            title="Editar alumno">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <a
                                            href="../../controller/ReporteAlumnoController.php?accion=reporte&id=<?= urlencode($a['id_alumno']) ?>"
                                            class="action-btn pdf-btn"
                                            title="Imprimir reporte PDF"
                                            target="_blank">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>

                                        <a
                                            href="../../controller/AlumnoController.php?eliminar=<?= urlencode($a['id_alumno']) ?>"
                                            class="action-btn delete-btn"
                                            title="Eliminar alumno"
                                            onclick="return confirm('¿Seguro que deseas eliminar este alumno?');">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-row">
                                No hay alumnos registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>