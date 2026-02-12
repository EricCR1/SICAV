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
UPDATE guardia SET
nombre='$nombre',
apellido='$apellido',
curp='$curp',
telefono='$telefono',
turno='$turno',
estado='$estado'
WHERE id_guardia='$id'
");

// Elimina vehículo previo (para evitar duplicados)
$conn->query("DELETE FROM vehiculo WHERE id_guardia='$id'");

if ($rfid !== "") {
    $conn->query("
        INSERT INTO vehiculo
        (rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_guardia)
        VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$id')
    ");
}

echo "Guardia actualizado correctamente.";
?>
