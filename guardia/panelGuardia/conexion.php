<?php
$host = "127.0.0.1"; 
$user = "root";       // usuario por defecto
$pass = "";           // contraseña vacía en XAMPP
$db   = "ite";
$port = 3306;         // puerto de MariaDB

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
