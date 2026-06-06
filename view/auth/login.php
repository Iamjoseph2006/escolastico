<?php
require_once __DIR__ . '/../../config/Auth.php';

if (auth_check()) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Escolastico</title>

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
            --danger: #dc2626;
            --danger-soft: #fff1f2;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            --radius-lg: 28px;
            --radius-sm: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.13), transparent 32rem),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.11), transparent 30rem),
                var(--bg);
            color: var(--text);
            font-family: "Segoe UI", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            padding: 1rem;
        }

        .login-card {
            position: relative;
            overflow: hidden;
            width: min(440px, 100%);
            padding: 1.6rem;
            border: 1px solid rgba(230, 234, 240, 0.95);
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .login-card::before {
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
            margin: 0 0 0.35rem;
            font-size: 1.45rem;
            font-weight: 800;
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

        .subtitle {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: 0.95rem;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 1rem;
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

        .form-control {
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

        .form-control:focus {
            border-color: rgba(37, 99, 235, 0.55);
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            width: 100%;
            min-height: 52px;
            border: none;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--primary), #0ea5e9);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), #0284c7);
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
        }

        .alert-custom {
            border: 1px solid rgba(220, 38, 38, 0.18);
            border-radius: var(--radius-sm);
            background: var(--danger-soft);
            color: var(--danger);
            font-weight: 700;
        }
    </style>
</head>
<body>

<main class="login-card">
    <h1 class="title">
        <i class="bi bi-shield-lock-fill"></i>
        Escolastico
    </h1>
    <p class="subtitle">Acceso al sistema academico</p>

    <?php if ($error): ?>
        <div class="alert alert-custom py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill me-1"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="../../controller/AuthController.php" method="POST">
        <div class="form-group">
            <i class="bi bi-person-fill"></i>
            <input
                type="text"
                class="form-control"
                name="usuario"
                placeholder="Usuario o correo"
                autocomplete="username"
                required
            >
        </div>

        <div class="form-group">
            <i class="bi bi-key-fill"></i>
            <input
                type="password"
                class="form-control"
                name="password"
                placeholder="Contrasena"
                autocomplete="current-password"
                required
            >
        </div>

        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right"></i>
            Ingresar
        </button>
    </form>

    <style>
        .form-text {
            display: block;
            margin-top: 1rem;
            text-align: center;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .form-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .form-text a:hover {
            color: var(--primary-dark);
        }
    </style>

    <p class="form-text">
        ¿No tienes cuenta? <a href="register.php">Registrate aquí</a>
    </p>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
