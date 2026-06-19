<?php
require_once __DIR__ . '/../config/database.php';

class Archivo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function crear($data)
    {
        $sql = "INSERT INTO archivos 
                (nombre_archivo, archivo_pdf, id_alumno) 
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['nombre_archivo'],
            $data['archivo_pdf'],
            $data['id_alumno']
        ]);
    }

    public function obtenerTodo()
    {
        $sql = "SELECT archivos.*, alumnos.nombres, alumnos.apellidos
                FROM archivos
                INNER JOIN alumnos ON archivos.id_alumno = alumnos.id_alumno
                ORDER BY archivos.id_archivo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorAlumno($id_alumno)
    {
        $sql = "SELECT archivos.*, alumnos.nombres, alumnos.apellidos
                FROM archivos
                INNER JOIN alumnos ON archivos.id_alumno = alumnos.id_alumno
                WHERE archivos.id_alumno = ?
                ORDER BY archivos.id_archivo DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_alumno]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM archivos WHERE id_archivo = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM archivos WHERE id_archivo = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }
}
?>