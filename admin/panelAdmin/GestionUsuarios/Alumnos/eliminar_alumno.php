<?php
include "conexion.php";

$id = isset($_GET["id"]) ? mysqli_real_escape_string($conn, $_GET["id"]) : '';

if (empty($id)) {
    echo "Falta id";
    exit;
}

$conn->query("DELETE FROM alumno WHERE no_control='$id'");

if ($conn->affected_rows > 0) {
    echo "Alumno eliminado.";
} else {
    echo "No se encontró alumno con ese id.";
}
?>
