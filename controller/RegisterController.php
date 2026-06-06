<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email) || empty($usuario) || empty($password)) {
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

        // Verificar si el usuario ya existe
        $sqlCheck = "SELECT COUNT(*) as count FROM usuarios WHERE usuario = ? OR correo = ?";
        $stmtCheck = $db->prepare($sqlCheck);
        $stmtCheck->execute([$usuario, $email]);
        $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            $_SESSION['register_error'] = 'El usuario o correo ya esta registrado';
            header('Location: ../view/auth/register.php');
            exit;
        }

        // Iniciar transacción
        $db->beginTransaction();

        // 1. Insertar nuevo alumno
        $sqlAlumno = "INSERT INTO alumnos (nombres, apellidos, correo, estado) 
                      VALUES (?, ?, ?, 'activo')";
        $stmtAlumno = $db->prepare($sqlAlumno);
        $stmtAlumno->execute([$nombre, $apellido, $email]);
        
        $idAlumno = $db->lastInsertId();

        // 2. Insertar nuevo usuario vinculado al alumno
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sqlUsuario = "INSERT INTO usuarios (nombre, usuario, correo, password, rol, id_alumno, estado) 
                       VALUES (?, ?, ?, ?, 'alumno', ?, 'activo')";

        $stmtUsuario = $db->prepare($sqlUsuario);
        $stmtUsuario->execute([$nombre . ' ' . $apellido, $usuario, $email, $passwordHash, $idAlumno]);

        // Confirmar transacción
        $db->commit();

        $_SESSION['register_success'] = 'Registro exitoso. Por favor inicia sesión';
        header('Location: ../view/auth/login.php');
        exit;

    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        $_SESSION['register_error'] = 'Error al registrar el usuario. Intenta mas tarde';
        header('Location: ../view/auth/register.php');
        exit;
    }
}

header('Location: ../view/auth/register.php');
exit;
?>
