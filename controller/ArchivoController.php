<?php

require_once __DIR__ . '/../config/Auth.php';
require_once __DIR__ . '/../model/Archivo.php';

require_secretaria();

$archivo = new Archivo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombreArchivo = "";

    if (isset($_FILES['archivo_pdf'])) {

        $directorio = "../uploads/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = time() . "_" . $_FILES['archivo_pdf']['name'];

        move_uploaded_file(
            $_FILES['archivo_pdf']['tmp_name'],
            $directorio . $nombreArchivo
        );
    }

    $datos = [
        'nombre_archivo' => $_POST['nombre_archivo'],
        'archivo_pdf' => $nombreArchivo,
        'id_alumno' => $_POST['id_alumno']
    ];

    $archivo->crear($datos);
    header('Location:../view/archivos/formulario.php');
    exit;
}
if (isset($_GET['eliminar'])) {
    $archivo->eliminar($_GET['eliminar']);
    header('Location:../view/archivos/formulario.php');
    exit;
}
