<?php
include "conexion.php";

$id = isset($_GET["id"]) ? mysqli_real_escape_string($conn, $_GET["id"]) : '';

if (empty($id)) { echo "Falta id"; exit; }

$conn->query("DELETE FROM personal WHERE id_personal='$id'");

echo ($conn->affected_rows>0) ? "Personal eliminado." : "No se encontró personal con ese id.";
?>
