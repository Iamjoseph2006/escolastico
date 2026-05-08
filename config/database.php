<?php

class Database{
    //Metodos con parametros y sin parametros
    public static function connect(){
        return new PDO("mysql:host=localhost;dbname=ecolastico", "root","");
    }
}
?>