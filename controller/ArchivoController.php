<?php
require_once __DIR__ . '/../config/Auth.php';
require_secretaria();

require_once __DIR__ . '/../model/Archivo.php';

$archivo = new Archivo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        empty($_POST['nombre_archivo']) ||
        empty($_POST['id_alumno']) ||
        empty($_FILES['archivo_pdf']['name'])
    ) {
        header('Location: ../view/archivos/formulario.php');
        exit;
    }

    $nombreOriginal = $_FILES['archivo_pdf']['name'];
    $tipoArchivo = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));

    if ($tipoArchivo !== 'pdf') {
        header('Location: ../view/archivos/formulario.php');
        exit;
    }

    $carpetaDestino = __DIR__ . '/../uploads/';

    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $nombreOriginal);
    $rutaDestino = $carpetaDestino . $nombreArchivo;

    if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $rutaDestino)) {
        $datos = [
            'nombre_archivo' => $_POST['nombre_archivo'],
            'archivo_pdf' => $nombreArchivo,
            'id_alumno' => $_POST['id_alumno']
        ];

        $archivo->crear($datos);
    }

    header('Location: ../view/archivos/formulario.php');
    exit;
}

if (isset($_GET['eliminar'])) {
    $archivo->eliminar($_GET['eliminar']);
    header('Location: ../view/archivos/formulario.php');
    exit;
}

header('Location: ../view/archivos/formulario.php');
exit;
?>