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
        $response['mensaje'] = 'Formato de correo invalido';
        echo json_encode($response);
        exit;
    }

    try {
        $alumnoModel = new Alumno();
        $alumno = $alumnoModel->obtenerPorCorreo($email);

        if ($alumno) {
            $response['valido'] = true;
            $response['mensaje'] = 'Correo valido';
        } else {
            $response['mensaje'] = 'Correo no registrado. Contacta a la secretaria';
        }
    } catch (PDOException $e) {
        $response['mensaje'] = 'Error al verificar correo';
    }
}

echo json_encode($response);
exit;
?>
