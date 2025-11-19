<?php
include "conexion.php";

$id = $_GET["id"];

$conn->query("DELETE FROM vehiculo WHERE id_personal='$id'");
$conn->query("DELETE FROM personal WHERE id_personal='$id'");

echo "Personal eliminado.";
?>
