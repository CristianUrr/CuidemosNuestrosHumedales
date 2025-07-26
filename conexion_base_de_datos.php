<?php
// conexion_base_de_datos.php

error_reporting(E_ALL);

// Parametros necesarios para la conexion
$servername = "127.0.0.1";
$username   = "root";
$password   = "99899989";
$database   = "ORGANIZACION";
$port       = 3306;

// Creacion y verificacion de conexion
$conn = mysqli_connect($servername, $username, $password, $database, $port);
if (!$conn) {
    die("Fallo de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');