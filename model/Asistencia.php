<?php
require_once __DIR__ . '/../config/database.php';


class Asistencia
{

    private $db;
    public function __construct()
    {
        $this->db = Database::connect();

        //Cargo las caracteriasticas de la base de datos a la variable
    }


    public function crear($data)
    {
        $sql = "INSERT INTO asistencias
        (
        materia,
        creditos,
        horas_credito,
        numero_faltas,
        horas_faltas,
        porcentaje_asistencia,
        porcentaje_inasistencia,
        id_alumno
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['materia'],
            $data['creditos'],
            $data['horas_credito'],
            $data['numero_faltas'],
            $data['horas_faltas'],
            $data['porcentaje_asistencia'],
            $data['porcentaje_inasistencia'],
            $data['id_alumno']
        ]);
    }

    public function obtenerTodos()
    {
        $sql = "SELECT
        alumnos.id_alumno,
        alumnos.nombres,
        alumnos.apellidos,
        asistencias.id_asistencia,
        asistencias.materia,
        asistencias.creditos,
        asistencias.horas_credito,
        asistencias.numero_faltas,
        asistencias.horas_faltas,
        asistencias.porcentaje_asistencia,
        asistencias.porcentaje_inasistencia
        FROM alumnos 
        INNER JOIN asistencias ON asistencias.id_alumno = alumnos.id_alumno";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    { //1717
        $sql = "SELECT * FROM asistencias WHERE id_asistencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM asistencias WHERE id_asistencia = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function actualizar($data)
    {
        $sql = "UPDATE asistencias 
                SET materia = ?,
                creditos = ?,
                horas_credito = ?,
                numero_faltas = ?,
                horas_faltas = ?,
                porcentaje_asistencia = ?,
                porcentaje_inasistencia = ?,
                id_alumno = ?
                WHERE id_asistencia = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['materia'],
            $data['creditos'],
            $data['horas_credito'],
            $data['numero_faltas'],
            $data['horas_faltas'],
            $data['porcentaje_asistencia'],
            $data['porcentaje_inasistencia'],
            $data['id_alumno'],
            $data['id_asistencia']
        ]);
    }

    public function obtenerPorIdParaReporte($id_asistencia)
    {
        $sql = "SELECT
                asistencias.id_asistencia,
                alumnos.id_alumno,
                alumnos.nombres,
                alumnos.apellidos,
                asistencias.materia,
                asistencias.creditos,
                asistencias.horas_credito,
                asistencias.numero_faltas,
                asistencias.horas_faltas,
                asistencias.porcentaje_asistencia,
                asistencias.porcentaje_inasistencia
                FROM asistencias
                INNER JOIN alumnos ON asistencias.id_alumno = alumnos.id_alumno
                WHERE id_asistencia = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_asistencia]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
