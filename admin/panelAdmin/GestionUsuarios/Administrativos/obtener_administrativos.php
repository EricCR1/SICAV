<?php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

// Trae administrativos y sus vehiculos (agrupados)
$sql = "
SELECT a.*, v.rfid, v.tipo_vehiculo, v.marca, v.modelo, v.`año`, v.color, v.placa
FROM administrativo a
LEFT JOIN vehiculo v ON a.id_administrativo = v.id_administrativo
ORDER BY a.id_administrativo DESC, v.rfid ASC
";
$res = $conn->query($sql);

$map = [];

while ($row = $res->fetch_assoc()) {
    $id = $row['id_administrativo'];
    if (!isset($map[$id])) {
        $map[$id] = [
            'id_administrativo' => $row['id_administrativo'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'rfc' => $row['rfc'],
            'f_nacimiento' => $row['f_nacimiento'],
            'correo' => $row['correo'],
            'telefono' => $row['telefono'],
            'area' => $row['area'],
            'estado' => $row['estado'],
            'vehiculos' => []
        ];
    }
    // si hay rfid (vehiculo)
    if (!empty($row['rfid'])) {
        $map[$id]['vehiculos'][] = [
            'rfid' => $row['rfid'],
            'tipo_vehiculo' => $row['tipo_vehiculo'],
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'año' => $row['año'],
            'color' => $row['color'],
            'placa' => $row['placa']
        ];
    }
}

$out = array_values($map);
echo json_encode($out, JSON_UNESCAPED_UNICODE);
$conn->close();
?>
