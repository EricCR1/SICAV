<?php
include "conexion.php";

$sql = "
SELECT a.*, v.*
FROM alumno a
LEFT JOIN vehiculo v ON a.no_control = v.no_control
ORDER BY a.no_control
";

$res = $conn->query($sql);

$alumnos = [];

while ($row = $res->fetch_assoc()) {
    $id = $row["no_control"];

    if (!isset($alumnos[$id])) {
        $alumnos[$id] = [
            "no_control" => $row["no_control"],
            "nombre" => $row["nombre"],
            "apellido" => $row["apellido"],
            "curp" => $row["curp"],
            "f_nacimiento" => $row["f_nacimiento"],
            "correo" => $row["correo"],
            "telefono" => $row["telefono"],
            "grado" => $row["grado"],
            "grupo" => $row["grupo"],
            "carrera" => $row["carrera"],
            "estado" => $row["estado"],
            "vehiculos" => []
        ];
    }

    if ($row["rfid"]) {
        $alumnos[$id]["vehiculos"][] = [
            "rfid" => $row["rfid"],
            "tipo_vehiculo" => $row["tipo_vehiculo"],
            "marca" => $row["marca"],
            "modelo" => $row["modelo"],
            "año" => $row["año"],
            "color" => $row["color"],
            "placa" => $row["placa"]
        ];
    }
}

echo json_encode(array_values($alumnos));
?>
