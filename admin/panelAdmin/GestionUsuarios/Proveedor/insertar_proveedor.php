<?php
include "conexion.php";

$id = $_POST["id_proveedor"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$razon = $_POST["razon_social"];
$rfc = $_POST["rfc"];
$telefono = $_POST["telefono"];
$correo = $_POST["correo"];
$tipo_servicio = $_POST["tipo_servicio"];
$empresa = $_POST["empresa"];

$rfid = $_POST["rfid"];
$tipo = $_POST["tipo_vehiculo"];
$marca = $_POST["marca"];
$modelo = $_POST["modelo"];
$anio = $_POST["año"];
$color = $_POST["color"];
$placa = $_POST["placa"];

$conn->query("
INSERT INTO proveedor 
(id_proveedor, nombre, apellido, razon_social, rfc, telefono, correo, tipo_servicio, empresa)
VALUES
('$id','$nombre','$apellido','$razon','$rfc','$telefono','$correo','$tipo_servicio','$empresa')
");

if ($rfid != "") {
    $conn->query("
    REPLACE INTO vehiculo
    (rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_proveedor)
    VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$id')
    ");
}

echo "Proveedor registrado correctamente.";
?>
