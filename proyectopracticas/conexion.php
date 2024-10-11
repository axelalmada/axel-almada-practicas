<?php
// Archivo de conexión a la base de datos

// Parámetros de conexión
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reparaciones";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a utf8
$conn->set_charset("utf8");

// Nota: No cerramos la conexión aquí para que pueda ser utilizada en otros archivos
?>
