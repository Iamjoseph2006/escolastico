<?php
//ejecutar todas las inyecciones SQL

require_once __DIR__ . '/../config/database.php';

class Archivo
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect(); //cargo las características de la conexión a la base de datos
    }

    //Metodo crear
    public function crear($data)
    {
        $sql = "INSERT INTO archivos
        (nombre_archivo, archivo_pdf, id_alumno) 
        values (?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['nombre_archivo'],
            $data['archivo_pdf'],
            $data['id_alumno']
        ]);
    }

    //Metodo obtener todos los archivos
    public function obtenerTodo()
    {
        $sql = "SELECT archivos.*, 
        alumnos.nombres, 
        alumnos.apellidos
        FROM archivos
        INNER JOIN alumnos 
        ON archivos.id_alumno = alumnos.id_alumno
        ORDER BY id_archivo DESC";
        $stmt = $this->db->prepare($sql);
        $stmt = $this->db->query($sql);
        $stmt->execute();

        //Cuando llame a esta funcion debes devolver todos en un arreglo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Metodo obtener por Id
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM archivos WHERE id_archivo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Metodo Eliminar
    public function eliminar($id)
    {
        $sql = "DELETE FROM archivos WHERE id_archivo = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
