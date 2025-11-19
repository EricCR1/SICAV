<?php
include "conexion.php";

$id = isset($_GET["id"]) ? mysqli_real_escape_string($conn, $_GET["id"]) : '';

if (empty($id)) { echo "Falta id"; exit; }

$conn->query("DELETE FROM administrativo WHERE id_administrativo='$id'");

echo ($conn->affected_rows>0) ? "Administrativo eliminado." : "No se encontró administrativo con ese id.";
?>

