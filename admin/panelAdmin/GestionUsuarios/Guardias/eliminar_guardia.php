<?php
include "conexion.php";

$id = $_GET["id"];

$conn->query("DELETE FROM vehiculo WHERE id_guardia='$id'");
$conn->query("DELETE FROM guardia WHERE id_guardia='$id'");

echo "Guardia eliminado.";
?>
