<?php
require_once __DIR__ . '/../../config/Auth.php';

if (auth_check()) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

$error = $_SESSION['register_error'] ?? '';
$success = $_SESSION['register_success'] ?? '';
unset($_SESSION['register_error']);
unset($_SESSION['register_success']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Escolastico</title>

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
            --success: #16a34a;
            --success-soft: #f0fdf4;
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

        .register-card {
            position: relative;
            overflow: hidden;
            width: min(500px, 100%);
            padding: 1.6rem;
            border: 1px solid rgba(230, 234, 240, 0.95);
            border-radius: var(--radius-lg);
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
        }

        .register-card::before {
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-row.full {
            grid-template-columns: 1fr;
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

        .form-control:invalid {
            border-color: rgba(220, 38, 38, 0.4);
        }

        .form-control:invalid:focus {
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.12);
        }

        .btn-register {
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
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, var(--primary-dark), #0284c7);
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.28);
        }

        .alert-custom {
            border-radius: var(--radius-sm);
            font-weight: 700;
        }

        .alert-danger-custom {
            border: 1px solid rgba(220, 38, 38, 0.18);
            background: var(--danger-soft);
            color: var(--danger);
        }

        .alert-success-custom {
            border: 1px solid rgba(22, 163, 74, 0.18);
            background: var(--success-soft);
            color: var(--success);
        }

        .form-text {
            display: block;
            margin-top: 0.75rem;
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

        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .password-strength.weak {
            color: var(--danger);
        }

        .password-strength.fair {
            color: #f59e0b;
        }

        .password-strength.good {
            color: #3b82f6;
        }

        .password-strength.strong {
            color: var(--success);
        }
    </style>
</head>
<body>

<main class="register-card">
    <h1 class="title">
        <i class="bi bi-person-plus-fill"></i>
        Crear Cuenta
    </h1>
    <p class="subtitle">Registrate en Escolastico</p>

    <?php if ($error): ?>
        <div class="alert alert-custom alert-danger-custom py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill me-1"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-custom alert-success-custom py-2" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i>
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="../../controller/RegisterController.php" method="POST" id="registerForm">
        <div class="form-row">
            <div class="form-group">
                <i class="bi bi-person-fill"></i>
                <input
                    type="text"
                    class="form-control"
                    name="nombre"
                    placeholder="Nombre"
                    autocomplete="given-name"
                    required
                >
            </div>
            <div class="form-group">
                <i class="bi bi-person-fill"></i>
                <input
                    type="text"
                    class="form-control"
                    name="apellido"
                    placeholder="Apellido"
                    autocomplete="family-name"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <i class="bi bi-envelope-fill"></i>
            <input
                type="email"
                class="form-control"
                name="email"
                placeholder="Correo electronico"
                autocomplete="email"
                required
            >
        </div>

        <div class="form-group">
            <i class="bi bi-at"></i>
            <input
                type="text"
                class="form-control"
                name="usuario"
                placeholder="Usuario"
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
                id="password"
                placeholder="Contrasena"
                autocomplete="new-password"
                required
                minlength="6"
            >
        </div>

        <div class="form-group">
            <i class="bi bi-key-fill"></i>
            <input
                type="password"
                class="form-control"
                name="confirmar_password"
                id="confirmar_password"
                placeholder="Confirmar contrasena"
                autocomplete="new-password"
                required
                minlength="6"
            >
        </div>

        <button type="submit" class="btn-register">
            <i class="bi bi-person-check-fill"></i>
            Registrarse
        </button>
    </form>

    <p class="form-text">
        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
    </p>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Validación de contraseñas coincidentes
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmar = document.getElementById('confirmar_password').value;

        if (password !== confirmar) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }

        if (password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            return false;
        }
    });
</script>
</body>
</html>
