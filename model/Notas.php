<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Service/CalificacionService.php';

use Escolastico\Service\CalificacionService;

class Notas{

    private $db;
    public function __construct()
    {
        $this->db = Database::connect();

        //Cargo las caracteriasticas de la base de datos a la variable
    }

    public function crear($data)
    {
        $npromedio = $this->calcularPromedio($data);
        $sql = "INSERT INTO notas
        (materia, nota1, nota2, nota3, npromedio, id_alumno)
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['materia'],
            $data['nota1'],
            $data['nota2'],
            $data['nota3'],
            $npromedio,
            $data['id_alumno']
        ]);
    }
    public function obtenerTodos()
    {
        $sql = "SELECT
        notas.id_nota,
        alumnos.id_alumno,
        alumnos.nombres,
        alumnos.apellidos,
        notas.materia,
        notas.nota1,
        notas.nota2,
        notas.nota3,
        notas.npromedio
        FROM notas
        INNER JOIN alumnos ON notas.id_alumno = alumnos.id_alumno";
        $stmt = $this->db->query($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function eliminar($id){
        $sql = "DELETE FROM notas WHERE id_nota = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function actualizar($data){
        $npromedio = $this->calcularPromedio($data);


        $sql = "UPDATE notas 
                SET materia = ?,
                nota1 = ?,
                nota2 = ?,
                nota3 = ?,
                npromedio = ?,
                id_alumno = ?
                WHERE id_nota = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['materia'],
            $data['nota1'],
            $data['nota2'],
            $data['nota3'],
            $npromedio,
            $data['id_alumno'],
            $data['id_nota']
        ]);


    }

    public function obtenerPorId($id){ //5
        $sql = "SELECT * FROM notas WHERE id_nota = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorIdParaReporte($id_nota)
    {
        $sql = "SELECT
                notas.id_nota,
                alumnos.id_alumno,
                alumnos.nombres,
                alumnos.apellidos,
                notas.materia,
                notas.nota1,
                notas.nota2,
                notas.nota3,
                notas.npromedio
                FROM notas
                INNER JOIN alumnos ON notas.id_alumno = alumnos.id_alumno
                WHERE id_nota = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_nota]);//2
        return $stmt->fetch(PDO::FETCH_ASSOC);//solo un registro
    }

    private function calcularPromedio($data)
    {
        foreach (['nota1', 'nota2', 'nota3'] as $campo) {
            if (!isset($data[$campo]) || !is_numeric($data[$campo])) {
                throw new InvalidArgumentException('Las tres calificaciones son obligatorias y deben ser numéricas.');
            }
        }

        return CalificacionService::calcularPromedio(
            (float) $data['nota1'],
            (float) $data['nota2'],
            (float) $data['nota3']
        );
    }
}
