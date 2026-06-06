<?php
require_once __DIR__.'/../config/Auth.php';
require_secretaria();

require_once __DIR__.'/../model/Alumno.php';

$alumno = new Alumno();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        
        $alumno->actualizar($_POST);
        header('Location:../view/alumnos/formulario.php');
        exit;

    } else {
        $alumno->crear($_POST);
        header('Location:../view/alumnos/formulario.php');
        exit;
    }
}

if (isset($_GET['eliminar'])) {
    $alumno->eliminar($_GET['eliminar']);
    header('Location:../view/alumnos/formulario.php');
    exit;
}
?>
