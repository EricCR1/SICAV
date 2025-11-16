<?php
include("../conexion.php");
date_default_timezone_set('America/Tijuana');

$sql = "
    SELECT COUNT(*) AS total
    FROM estacionamiento
    WHERE MONTH(fecha) = MONTH(CURDATE())
      AND YEAR(fecha) = YEAR(CURDATE())
      AND accion = '0'
      AND id_visitante IS NOT NULL
";

$result = $conn->query($sql)->fetch_assoc();
echo $result["total"];
