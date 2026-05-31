<?php

// Se importa el modelo Asistencia.
require_once '../../model/Asistencia.php';

// Se importa el modelo Alumno.
require_once '../../model/Alumno.php';


// Se crean objetos.
$asistencia = new Asistencia();
$alumno = new Alumno();


// Arreglo inicial del formulario.
$data = [

    'id_asistencia' => '',
    'materia' => '',
    'creditos' => '',
    'horas_credito' => '',
    'numero_faltas' => '',
    'horas_faltas' => '',
    'porcentaje_asistencia' => '',
    'porcentaje_inasistencia' => '',
    'id_alumno' => ''

];


// Si viene un ID significa edición.
if (isset($_GET['id'])) {

    $data = $asistencia->obtenerPorId(
        $_GET['id']
    ) ?? $data;
}


// Obtener registros.
$asistencias = $asistencia->obtenerTodos();


// Obtener alumnos.
$alumnos = $alumno->obtenerTodo();

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Asistencias</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>


<div class="container mt-4">

    <h2 class="mb-4">

        Registro de asistencia

    </h2>


    <form
        action="../../controller/AsistenciaController.php"
        method="POST"
        onsubmit="calcularAsistencia()"
    >

        <input
            type="hidden"
            name="id_asistencia"
            value="<?= $data['id_asistencia'] ?>"
        >


        <?php if($data['id_asistencia']!=''): ?>

            <input
                type="hidden"
                name="accion"
                value="editar"
            >

        <?php endif; ?>


        <div class="row">


            <!-- Materia -->

            <div class="col-md-4 mb-3">

                <label class="form-label">

                    Materia

                </label>

                <input
                    type="text"
                    name="materia"
                    class="form-control"
                    value="<?= $data['materia'] ?>"
                    required
                >

            </div>



            <!-- Alumno -->

            <div class="col-md-4 mb-3">

                <label class="form-label">

                    Alumno

                </label>

                <select
                    name="id_alumno"
                    class="form-select"
                    required
                >

                    <option value="">

                        Seleccione alumno

                    </option>


                    <?php foreach($alumnos as $a): ?>

                        <option

                            value="<?= $a['id_alumno'] ?>"

                            <?= $data['id_alumno']==$a['id_alumno']
                            ?'selected':''
                            ?>

                        >

                            <?= $a['nombres'] ?>

                            <?= $a['apellidos'] ?>

                        </option>

                    <?php endforeach; ?>


                </select>

            </div>



            <!-- Créditos -->

            <div class="col-md-2 mb-3">

                <label class="form-label">

                    Créditos

                </label>

                <input
                    type="number"
                    id="creditos"
                    name="creditos"
                    class="form-control"
                    value="<?= $data['creditos'] ?>"
                    oninput="calcularAsistencia()"
                    required
                >

            </div>



            <!-- Número faltas -->

            <div class="col-md-2 mb-3">

                <label class="form-label">

                    Faltas

                </label>

                <input
                    type="number"
                    id="numero_faltas"
                    name="numero_faltas"
                    class="form-control"
                    value="<?= $data['numero_faltas'] ?>"
                    oninput="calcularAsistencia()"
                    required
                >

            </div>



            <!-- Horas crédito -->

            <div class="col-md-3 mb-3">

                <label class="form-label">

                    Horas crédito

                </label>

                <input
                    type="text"
                    id="horas_credito"
                    name="horas_credito"
                    class="form-control"
                    value="<?= $data['horas_credito'] ?>"
                    readonly
                >

            </div>



            <!-- Horas faltas -->

            <div class="col-md-3 mb-3">

                <label class="form-label">

                    Horas faltas

                </label>

                <input
                    type="text"
                    id="horas_faltas"
                    name="horas_faltas"
                    class="form-control"
                    value="<?= $data['horas_faltas'] ?>"
                    readonly
                >

            </div>



            <!-- Asistencia -->

            <div class="col-md-3 mb-3">

                <label class="form-label">

                    % Asistencia

                </label>

                <input
                    type="text"
                    id="porcentaje_asistencia"
                    name="porcentaje_asistencia"
                    class="form-control"
                    value="<?= $data['porcentaje_asistencia'] ?>"
                    readonly
                >

            </div>



            <!-- Inasistencia -->

            <div class="col-md-3 mb-3">

                <label class="form-label">

                    % Inasistencia

                </label>

                <input
                    type="text"
                    id="porcentaje_inasistencia"
                    name="porcentaje_inasistencia"
                    class="form-control"
                    value="<?= $data['porcentaje_inasistencia'] ?>"
                    readonly
                >

            </div>


        </div>


        <button
            class="btn btn-primary"
        >

            Guardar

        </button>


    </form>


    <hr>


    <table class="table table-bordered table-hover">

        <thead>

        <tr>

            <th>ID</th>
            <th>Alumno</th>
            <th>Materia</th>
            <th>% Asistencia</th>
            <th>% Inasistencia</th>
            <th>Acciones</th>

        </tr>

        </thead>


        <tbody>

        <?php foreach($asistencias as $a): ?>

            <tr>

                <td>

                    <?= $a['id_alumno'] ?>

                </td>

                <td>

                    <?= $a['nombres'] ?>

                    <?= $a['apellidos'] ?>

                </td>

                <td>

                    <?= $a['materia'] ?>

                </td>

                <td>

                    <?= $a['porcentaje_asistencia'] ?> %

                </td>

                <td>

                    <?= $a['porcentaje_inasistencia'] ?> %

                </td>

                <td>

                    <a
                        href="formulario.php?id=<?= $a['id_asistencia'] ?>"
                        class="btn btn-warning btn-sm"
                    >

                        Editar

                    </a>


                    <a
                        href="../../controller/AsistenciaController.php?eliminar=<?= $a['id_asistencia'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Eliminar registro?')"
                    >

                        Eliminar

                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>


    </table>

</div>



<script>

function calcularAsistencia()
{

    let creditos =
    parseFloat(
        document.getElementById(
            'creditos'
        ).value
    ) || 0;


    let faltas =
    parseFloat(
        document.getElementById(
            'numero_faltas'
        ).value
    ) || 0;


    let horas_credito =
    creditos * 20;


    let horas_faltas =
    faltas * 2;


    let porcentaje_inasistencia =
    (
        horas_faltas * 100
    ) / horas_credito;


    if(
        isNaN(
            porcentaje_inasistencia
        )
    )
    {
        porcentaje_inasistencia = 0;
    }


    let porcentaje_asistencia =
    100 - porcentaje_inasistencia;



    document.getElementById(
        'horas_credito'
    ).value =
    horas_credito;


    document.getElementById(
        'horas_faltas'
    ).value =
    horas_faltas;


    document.getElementById(
        'porcentaje_asistencia'
    ).value =
    porcentaje_asistencia.toFixed(2);


    document.getElementById(
        'porcentaje_inasistencia'
    ).value =
    porcentaje_inasistencia.toFixed(2);

}


document.addEventListener(
    'DOMContentLoaded',
    calcularAsistencia
);

</script>


</body>

</html>