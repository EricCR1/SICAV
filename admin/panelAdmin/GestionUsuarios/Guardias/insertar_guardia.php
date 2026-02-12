<?php
include "conexion.php";

$id = $_POST["id_guardia"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$curp = $_POST["curp"];
$telefono = $_POST["telefono"];
$turno = $_POST["turno"];
$estado = $_POST["estado"];

$rfid = $_POST["rfid"];
$tipo = $_POST["tipo_vehiculo"];
$marca = $_POST["marca"];
$modelo = $_POST["modelo"];
$anio = $_POST["año"];
$color = $_POST["color"];
$placa = $_POST["placa"];

$conn->query("
INSERT INTO guardia VALUES
('$id','$nombre','$apellido','$curp','$telefono','$turno','$estado')
");

if ($rfid !== "") {
    $conn->query("
        INSERT INTO vehiculo
        (rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_guardia)
        VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$id')
    ");
}

echo "Guardia registrado correctamente.";
?>
