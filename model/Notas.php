<?php

require_once __DIR__ . '/../config/database.php';

class Notas{

    private $db;
    public function __construct()
    {
        $this->db = Database::connect();

        //Cargo las caracteriasticas de la base de datos a la variable
    }

    public function crear($data)
    {
        //Validando que si no llega un valor desde el controlador que consume desde 
        $npromedio = $data['npromedio'];
        if ($npromedio === '' || $npromedio === null) {
            $npromedio = ($data['nota1'] + $data['nota2'] + $data['nota3']) / 3;
        }
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
        $npromedio = $data['npromedio'];
        if ($npromedio === '' || $npromedio === null) {
            $npromedio = ($data['nota1'] + $data['nota2'] + $data['nota3']) / 3;
        }


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

    public function obtenerPorId($id){ //1717
        $sql = "SELECT * FROM notas WHERE id_nota = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}