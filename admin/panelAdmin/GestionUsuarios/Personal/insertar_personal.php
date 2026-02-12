<?php
include "conexion.php";

$id = $_POST["id_personal"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$curp = $_POST["curp"];
$telefono = $_POST["telefono"];
$area = $_POST["area"];
$turno = $_POST["turno"];

$rfid = $_POST["rfid"];
$tipo = $_POST["tipo_vehiculo"];
$marca = $_POST["marca"];
$modelo = $_POST["modelo"];
$anio = $_POST["año"];
$color = $_POST["color"];
$placa = $_POST["placa"];

$conn->query("
INSERT INTO personal
(id_personal, nombre, apellido, curp, telefono, area, turno)
VALUES ('$id','$nombre','$apellido','$curp','$telefono','$area','$turno')
");

if ($rfid != "") {
    $conn->query("
    INSERT INTO vehiculo
    (rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_personal)
    VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$id')
    ");
}

echo "Personal registrado correctamente.";
?>
