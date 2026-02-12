<?php
include("../conexion.php");
date_default_timezone_set('America/Tijuana');

$sql = "
SELECT COUNT(*) AS activos
FROM (
    SELECT v.id_visitante,
           (
               SELECT accion 
               FROM estacionamiento e2
               WHERE e2.id_visitante = v.id_visitante
               ORDER BY e2.fecha DESC
               LIMIT 1
           ) AS ultima_accion
    FROM visitante v
) AS movimientos
WHERE ultima_accion = '0'
";

$result = $conn->query($sql)->fetch_assoc();
echo $result["activos"];
