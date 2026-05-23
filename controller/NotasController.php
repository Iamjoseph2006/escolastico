<?php
require_once __DIR__.'/../model/Notas.php';

$nota = new Notas();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        
    $nota->actualizar($_POST);
    header('Location:../view/notas/formulario.php');

    exit;

    }else{
        $nota->crear($_POST);
        header('Location:../view/notas/formulario.php');
        exit;

    }

}

if (isset($_GET['eliminar'])) {
    $nota->eliminar($_GET['eliminar']);
    header('Location:../view/notas/formulario.php');
    exit;
}


?>