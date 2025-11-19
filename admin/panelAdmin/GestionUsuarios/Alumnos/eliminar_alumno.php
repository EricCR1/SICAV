<?php
include "conexion.php";

$id = $_GET["id"];

$conn->query("DELETE FROM vehiculo WHERE no_control='$id'");
$conn->query("DELETE FROM alumno WHERE no_control='$id'");

echo "Alumno eliminado.";
?>
