<?php
include "conexion.php";

$sql = "
SELECT p.*, v.placa, v.rfid, v.tipo_vehiculo, v.marca, v.modelo, v.año, v.color
FROM proveedor p
LEFT JOIN vehiculo v ON p.id_proveedor = v.id_proveedor
";

$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
