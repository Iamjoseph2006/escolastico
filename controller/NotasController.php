<?php
require_once __DIR__.'/../config/Auth.php';
require_secretaria();
require_once __DIR__.'/../model/Notas.php';

$nota = new Notas();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
            $nota->actualizar($_POST);
        } else {
            $nota->crear($_POST);
        }
    } catch (InvalidArgumentException $exception) {
        $_SESSION['notas_error'] = $exception->getMessage();
    }

    header('Location:../view/notas/formulario.php');
    exit;
}

if (isset($_GET['eliminar'])) {
    $nota->eliminar($_GET['eliminar']);
    header('Location:../view/notas/formulario.php');
    exit;
}


?>
