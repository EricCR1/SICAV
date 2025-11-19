<?php
include "conexion.php";

$id = isset($_GET["id"]) ? mysqli_real_escape_string($conn, $_GET["id"]) : '';

if (empty($id)) { echo "Falta id"; exit; }

$conn->query("DELETE FROM guardia WHERE id_guardia='$id'");

echo ($conn->affected_rows>0) ? "Guardia eliminado." : "No se encontró guardia con ese id.";
?>
