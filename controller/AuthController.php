<?php
require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../model/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $usuarioModel = new Usuario();
    $data = $usuarioModel->autenticar($usuario, $password);

    if ($data) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $data;
        header('Location: ../view/dashboard/dashboard.php');
        exit;
    }

    $_SESSION['login_error'] = 'Usuario o contraseña incorrectos';
    header('Location: ../view/auth/login.php');
    exit;
}

if (isset($_GET['accion']) && $_GET['accion'] === 'logout') {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: ../view/auth/login.php');
    exit;
}

header('Location: ../view/auth/login.php');
exit;
?>
