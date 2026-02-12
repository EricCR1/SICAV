<?php
include "conexion.php";

$id = isset($_GET["id"]) ? mysqli_real_escape_string($conn, $_GET["id"]) : '';

if (empty($id)) { echo "Falta id"; exit; }

$conn->query("DELETE FROM docente WHERE id_docente='$id'");

echo ($conn->affected_rows>0) ? "Docente eliminado." : "No se encontró docente con ese id.";
?>

