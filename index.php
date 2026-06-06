<?php
require_once __DIR__ . '/config/Auth.php';

if (auth_check()) {
    header('Location: view/dashboard/dashboard.php');
    exit;
}

header('Location: view/auth/login.php');
exit;
?>
