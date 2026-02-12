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
UPDATE personal SET
nombre='$nombre',
apellido='$apellido',
curp='$curp',
telefono='$telefono',
area='$area',
turno='$turno'
WHERE id_personal='$id'
");

$conn->query("
REPLACE INTO vehiculo
(rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_personal)
VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$id')
");

echo "Personal actualizado correctamente.";
?>
