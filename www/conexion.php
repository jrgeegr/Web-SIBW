<?php

function conectar() {
    $servername = "lamp-mysql8"; // Nombre del contenedor en la red de Docker 
    $username   = "sibwuser";      // El usuario que creaste 
    $password   = "1234";          // La contraseña del usuario 
    $dbname     = "sibw";          // El nombre de tu base de datos 
    $port       = 3306;            // Puerto interno de la red Docker

    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $conn->set_charset("utf8");

    return $conn;
}
?>