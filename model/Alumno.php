<?php
// Modelo Alumno: aquí van las consultas SQL relacionadas con la tabla alumnos.

require_once __DIR__ . '/../config/database.php';

class Alumno
{
    private $db;

    public function __construct()
    {
        // Carga la conexión a la base de datos.
        $this->db = Database::connect();
    }

    // Método crear alumno.
    public function crear($data)
    {
        /*
            Aquí sí se incluye id_alumno.
            Esto permite registrar manualmente el ID desde el formulario.
        */
        $sql = "INSERT INTO alumnos 
                (id_alumno, nombres, apellidos, correo, telefono, estado) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['id_alumno'],
            $data['nombres'],
            $data['apellidos'],
            $data['correo'],
            $data['telefono'] ?? null,
            $data['estado']
        ]);
    }

    // Método para obtener todos los alumnos.
    public function obtenerTodo()
    {
        $sql = "SELECT * FROM alumnos ORDER BY id_alumno DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener un alumno por su ID.
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM alumnos WHERE id_alumno = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para actualizar un alumno.
    public function actualizar($data)
    {
        /*
            El id_alumno NO se cambia.
            Solo se usa en el WHERE para saber qué alumno actualizar.
        */
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
            $data['telefono'] ?? null,
            $data['estado'],
            $data['id_alumno']
        ]);
    }

    // Método para eliminar un alumno.
    public function eliminar($id)
    {
        $sql = "DELETE FROM alumnos WHERE id_alumno = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }

    // Método para obtener datos de un alumno para reportes.
    public function obtenerPorIdParaReporte($id_alumno)
    {
        $sql = "SELECT id_alumno, nombres, apellidos, correo, telefono, estado 
                FROM alumnos 
                WHERE id_alumno = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_alumno]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para buscar alumno por correo.
    // Se usa en el registro de usuarios para verificar si el correo pertenece a un alumno activo.
    public function obtenerPorCorreo($correo)
    {
        $sql = "SELECT id_alumno, nombres, apellidos, correo, telefono, estado 
                FROM alumnos 
                WHERE correo = ? 
                AND estado = 'activo'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
