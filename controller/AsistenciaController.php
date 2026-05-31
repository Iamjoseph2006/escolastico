<?php
require_once __DIR__ . '/../model/Asistencia.php';

$asistencia = new Asistencia();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        
    $asistencia->actualizar($_POST);
    header('Location:../view/asistencia/formulario.php');

    exit;

    }else{
        $asistencia->crear($_POST);
        header('Location:../view/asistencia/formulario.php');
        exit;

    }

}

if (isset($_GET['eliminar'])) {
    $asistencia->eliminar($_GET['eliminar']);
    header('Location:../view/asistencia/formulario.php');
    exit;
}





?>