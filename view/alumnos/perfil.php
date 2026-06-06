<?php
require_once __DIR__ . '/../../config/Auth.php';
require_login();

if (!is_alumno()) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

require_once __DIR__ . '/../../model/Alumno.php';

$alumnoModel = new Alumno();
$idAlumno = auth_alumno_id();
$alumno = $idAlumno ? $alumnoModel->obtenerPorId($idAlumno) : null;

if (!$alumno) {
    $_SESSION['error_message'] = 'Información del estudiante no encontrada';
    header('Location: ../dashboard/dashboard.php');
    exit;
}

$usuario = auth_user();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Escolastico</title>

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
            width: min(800px, 100%);
            margin: 0 auto;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.76);
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
            transform: translateY(-1px);
        }

        .card-custom {
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
            padding: 2rem;
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

        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--line);
        }

        .profile-avatar {
            display: inline-grid;
            place-items: center;
            flex: 0 0 auto;
            width: 100px;
            height: 100px;
            border-radius: 20px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 2.5rem;
        }

        .profile-info h2 {
            margin: 0 0 0.25rem;
            font-size: 1.5rem;
            font-weight: 900;
        }

        .profile-info p {
            margin: 0.25rem 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .info-item {
            padding: 1rem;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--line);
        }

        .info-label {
            display: block;
            color: var(--muted);
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }

        .info-value {
            display: block;
            color: var(--text);
            font-size: 1.1rem;
            font-weight: 700;
        }

        .info-item.status.active {
            border-color: rgba(22, 163, 74, 0.2);
            background: var(--success-soft);
        }

        .status-badge {
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

        .title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0 0 1.5rem;
            font-size: 1.25rem;
            font-weight: 900;
        }

        .title i {
            color: var(--primary);
            font-size: 1.4rem;
        }

        @media (max-width: 600px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .card-custom {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="topbar">
        <a href="../dashboard/dashboard.php" class="back-link">
            <i class="bi bi-arrow-left-circle"></i> Volver al dashboard
        </a>
    </div>

    <div class="card-custom">
        <h1 class="title">
            <i class="bi bi-person-circle"></i>
            Mi Perfil
        </h1>

        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="profile-info">
                <h2><?= htmlspecialchars($alumno['nombres']) ?> <?= htmlspecialchars($alumno['apellidos']) ?></h2>
                <p>
                    <i class="bi bi-hash me-1"></i>
                    Código: <strong><?= htmlspecialchars($alumno['id_alumno']) ?></strong>
                </p>
                <p>
                    <span class="status-badge">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= ucfirst($alumno['estado'] ?? 'activo') ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Correo Electrónico</span>
                <span class="info-value">
                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                    <?= htmlspecialchars($alumno['correo']) ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Teléfono</span>
                <span class="info-value">
                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                    <?= htmlspecialchars($alumno['telefono'] ?? 'No registrado') ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Usuario</span>
                <span class="info-value">
                    <i class="bi bi-at text-primary me-2"></i>
                    <?= htmlspecialchars($usuario['usuario']) ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Rol</span>
                <span class="info-value">
                    <i class="bi bi-person-badge-fill text-primary me-2"></i>
                    <?= ucfirst($usuario['rol']) ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Registrado</span>
                <span class="info-value">
                    <i class="bi bi-calendar-fill text-primary me-2"></i>
                    <?= date('d/m/Y', strtotime($alumno['creado_en'] ?? 'now')) ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label">Estado Actual</span>
                <span class="info-value">
                    <i class="bi bi-circle-fill text-success me-2"></i>
                    Activo en el sistema
                </span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
