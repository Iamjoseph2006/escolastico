<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {

    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function autenticar($usuario, $password) {
        $sql = "SELECT id_usuario, nombre, usuario, correo, password, rol, id_alumno, estado
                FROM usuarios
                WHERE (usuario = ? OR correo = ?)
                AND estado = 'activo'
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario, $usuario]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && password_verify($password, $data['password'])) {
            unset($data['password']);
            return $data;
        }

        return false;
    }
}
?>
