<?php
//ejecutar todas las inyecciones SQL

require_once __DIR__.'/../config/database.php';

class Alumno {
   
private $db;
    
public function __construct() 
    {
        $this->db = Database::connect(); //cargo las características de la conexión a la base de datos
    }


    //Metodo crear
    public function crear ($data) 
    {
        $sql = "INSERT INTO alumnos 
        (nombres, apellidos, correo, telefono, estado) 
        values (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $data['telefono'] ?? null,
            $data['estado']
        ]);

    }

    //metodo obtener todos los alumnos
    public function obtenerTodo() 
    {
        $sql = "SELECT * FROM alumnos ORDER BY id_alumno DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        //cuando llame a esta funcion debes devolver todos en un arreglo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function obtenerPorId($id){ //1717
        $sql = "SELECT * FROM alumnos WHERE id_alumno = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($data) 
    {
        $sql = "UPDATE alumnos 
        SET nombres = ?, 
        apellidos = ?, 
        correo = ?, 
        telefono = ?, 
        estado = ? 
        WHERE id_alumno = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $data['telefono'],
            $data['estado'],
            $data['id_alumno']
        ]);
    }

    public function eliminar($id) 
    {
        $sql = "DELETE FROM alumnos WHERE id_alumno = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerPorIdParaReporte($id_alumno)
    {
        $sql = "SELECT id_alumno, nombres, apellidos,
                correo, telefono, estado 
                FROM alumnos WHERE id_alumno = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_alumno]);//2
        return $stmt->fetch(PDO::FETCH_ASSOC);//solo un registro
}
    public function obtenerPorCorreo($correo)
    {
         $sql = "SELECT id_alumno, nombres, apellidos, correo, telefono, estado 
                 FROM alumnos WHERE correo = ? AND estado = 'activo'";
         $stmt = $this->db->prepare($sql);
         $stmt->execute([$correo]);
         return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>

