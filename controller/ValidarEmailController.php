<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/Alumno.php';

$response = [
    'valido' => false,
    'mensaje' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['mensaje'] = 'Formato de correo inválido';
        echo json_encode($response);
        exit;
    }

    try {
        $db = Database::connect();

        $alumnoModel = new Alumno();
        $alumno = $alumnoModel->obtenerPorCorreo($email);

        // Primero revisa si el correo existe en la tabla alumnos.
        // Si no existe, significa que secretaría todavía no registró al alumno.
        if (!$alumno) {
            $response['mensaje'] = 'Correo no registrado. Contacta a la secretaria';
            echo json_encode($response);
            exit;
        }

        // Ahora revisa si ese alumno o correo ya tiene cuenta en usuarios.
        $sqlUsuario = "SELECT COUNT(*) AS total
                       FROM usuarios
                       WHERE correo = ? OR id_alumno = ?";

        $stmtUsuario = $db->prepare($sqlUsuario);
        $stmtUsuario->execute([
            $email,
            $alumno['id_alumno']
        ]);

        $usuarioExiste = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

        // Si ya existe una cuenta, no permite registrar otra vez.
        if ($usuarioExiste && $usuarioExiste['total'] > 0) {
            $response['valido'] = false;
            $response['mensaje'] = 'Este correo ya está registrado';
            echo json_encode($response);
            exit;
        }

        // Si el alumno existe y todavía no tiene usuario, permite registrarse.
        $response['valido'] = true;
        $response['mensaje'] = 'Correo válido. Puedes registrarte';

    } catch (PDOException $e) {
        $response['mensaje'] = 'Error al verificar correo';
    }
}

echo json_encode($response);
exit;
?>