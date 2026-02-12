<?php
include "conexion.php";

$no = $_POST["no_control"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$curp = $_POST["curp"];
$fn = $_POST["f_nacimiento"];
$correo = $_POST["correo"];
$telefono = $_POST["telefono"];
$grado = $_POST["grado"];
$grupo = $_POST["grupo"];
$carrera = $_POST["carrera"];
$estado = $_POST["estado"];

$conn->query("
UPDATE alumno SET
nombre='$nombre',
apellido='$apellido',
curp='$curp',
f_nacimiento='$fn',
correo='$correo',
telefono='$telefono',
grado='$grado',
grupo='$grupo',
carrera='$carrera',
estado='$estado'
WHERE no_control='$no'
");

$conn->query("DELETE FROM vehiculo WHERE no_control='$no'");

if (!empty($_POST["rfid"])) {
    foreach ($_POST["rfid"] as $i => $rfid) {
        if ($rfid == "") continue;

        $tipo = $_POST["tipo_vehiculo"][$i];
        $marca = $_POST["marca"][$i];
        $modelo = $_POST["modelo"][$i];
        $anio = $_POST["año"][$i];
        $color = $_POST["color"][$i];
        $placa = $_POST["placa"][$i];

        $conn->query("
            INSERT INTO vehiculo
            (rfid,tipo_vehiculo,marca,modelo,año,color,placa,no_control)
            VALUES ('$rfid','$tipo','$marca','$modelo','$anio','$color','$placa','$no')
        ");
    }
}

echo "Alumno actualizado correctamente.";
?>
