<?php
include("../conexion.php");

date_default_timezone_set('America/Tijuana');

// Fecha de hoy en formato YYYY-MM-DD
$hoy = date("Y-m-d");

// Contar SOLO entradas de visitantes hoy
$sql = "
    SELECT COUNT(*) AS total
    FROM estacionamiento
    WHERE DATE(fecha) = ?
      AND accion = '0'
      AND id_visitante IS NOT NULL
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hoy);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo $result["total"];
?>
