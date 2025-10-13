<?php
include("conexion.php");

// Recibir datos del formulario
$nombre      = $_POST['nombre'];
$apellido    = $_POST['apellido'];
$motivo      = $_POST['motivo'];
$placa       = $_POST['placa'];
$marca       = $_POST['marca'];
$color       = $_POST['color'];
$tipo        = $_POST['tipo_vehiculo'];

// Insertar en la base de datos
$sql = "INSERT INTO visitante (nombre, apellido, motivo, placa, marca, color, tipo_vehiculo) 
        VALUES ('$nombre', '$apellido', '$motivo', '$placa', '$marca', '$color', '$tipo')";

if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
} else {
    echo " Error: " . $conn->error;
}

$conn->close();

?>
