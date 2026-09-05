<?php

class Database{
    //Metodos con parametros y sin parametros
    public static function connect(){
        $host = getenv('DB_HOST') ?: 'localhost';
        $database = getenv('DB_NAME') ?: 'ecolastico';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASSWORD');

        if ($password === false) {
            $password = '';
        }

        return new PDO(
            "mysql:host={$host};dbname={$database};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
}
?>
