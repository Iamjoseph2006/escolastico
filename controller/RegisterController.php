<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Alumno.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';

    // Validaciones
    if (empty($email) || empty($usuario) || empty($password)) {
        $_SESSION['register_error'] = 'Todos los campos son obligatorios';
        header('Location: ../view/auth/register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'El correo no es valido';
        header('Location: ../view/auth/register.php');
        exit;
    }

    if ($password !== $confirmar_password) {
        $_SESSION['register_error'] = 'Las contraseñas no coinciden';
        header('Location: ../view/auth/register.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['register_error'] = 'La contraseña debe tener al menos 6 caracteres';
        header('Location: ../view/auth/register.php');
        exit;
    }

    if (strlen($usuario) < 3) {
        $_SESSION['register_error'] = 'El usuario debe tener al menos 3 caracteres';
        header('Location: ../view/auth/register.php');
        exit;
    }

    try {
        $db = Database::connect();

        // Buscar si el alumno existe en la base de datos
        $alumnoModel = new Alumno();
        $alumno = $alumnoModel->obtenerPorCorreo($email);

        if (!$alumno) {
            $_SESSION['register_error'] = 'El correo no esta registrado en el sistema. Contacta a la secretaria.';
            header('Location: ../view/auth/register.php');
            exit;
        }

        // Verificar si el usuario ya existe
        $sqlCheck = "SELECT COUNT(*) as count FROM usuarios WHERE usuario = ? OR (correo = ? AND id_alumno = ?)";
        $stmtCheck = $db->prepare($sqlCheck);
        $stmtCheck->execute([$usuario, $email, $alumno['id_alumno']]);
        $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $_SESSION['register_error'] = 'El usuario ya esta registrado';
            header('Location: ../view/auth/register.php');
            exit;
        }

        // Insertar nuevo usuario vinculado al alumno existente
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sqlUsuario = "INSERT INTO usuarios (nombre, usuario, correo, password, rol, id_alumno, estado) 
                       VALUES (?, ?, ?, ?, 'alumno', ?, 'activo')";

        $stmtUsuario = $db->prepare($sqlUsuario);
        $stmtUsuario->execute([$alumno['nombres'] . ' ' . $alumno['apellidos'], $usuario, $email, $passwordHash, $alumno['id_alumno']]);

        // Almacenar los datos del alumno en la sesión
        $_SESSION['alumno_registrado'] = $alumno;
        $_SESSION['register_success'] = 'Registro exitoso. Por favor inicia sesion';
        header('Location: ../view/auth/login.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['register_error'] = 'Error al registrar el usuario. Intenta mas tarde';
        header('Location: ../view/auth/register.php');
        exit;
    }
}

header('Location: ../view/auth/register.php');
exit;
?>
