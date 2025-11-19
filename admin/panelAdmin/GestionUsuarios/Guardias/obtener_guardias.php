<?php
include "conexion.php";

$sql = "
SELECT g.*, v.rfid, v.tipo_vehiculo, v.marca, v.modelo, v.año, v.color, v.placa
FROM guardia g
LEFT JOIN vehiculo v ON g.id_guardia = v.id_guardia
";

$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
