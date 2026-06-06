<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_BASE_URL', '/escolastico');

function auth_user() {
    return $_SESSION['usuario'] ?? null;
}

function auth_check() {
    return isset($_SESSION['usuario']);
}

function auth_role() {
    return $_SESSION['usuario']['rol'] ?? null;
}

function is_secretaria() {
    return auth_role() === 'secretaria';
}

function is_alumno() {
    return auth_role() === 'alumno';
}

function auth_alumno_id() {
    return $_SESSION['usuario']['id_alumno'] ?? null;
}

function redirect_to_login() {
    header('Location: ' . APP_BASE_URL . '/view/auth/login.php');
    exit;
}

function require_login() {
    if (!auth_check()) {
        redirect_to_login();
    }
}

function require_secretaria() {
    require_login();

    if (!is_secretaria()) {
        header('Location: ' . APP_BASE_URL . '/view/dashboard/dashboard.php');
        exit;
    }
}

function require_alumno_or_secretaria() {
    require_login();

    if (!is_secretaria() && !is_alumno()) {
        header('Location: ' . APP_BASE_URL . '/view/dashboard/dashboard.php');
        exit;
    }
}

function can_access_alumno($id_alumno) {
    return is_secretaria() || (is_alumno() && (string)auth_alumno_id() === (string)$id_alumno);
}

function deny_access() {
    http_response_code(403);
    die('Acceso no autorizado');
}
?>
