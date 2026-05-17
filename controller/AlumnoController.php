<?php

//consumir el modelo porque debo mandar información a funciones
require_once __DIR__.'/../model/Alumno.php';

//instancia de la clase que se llama alumno

$alumno = new Alumno();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        
    $alumno->actualizar($_POST);
    //espacio de aqui para codificar JS
    header('Location:../view/alumnos/formulario.php');

    exit;

    }else{
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