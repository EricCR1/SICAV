<?php
include("conexion.php");

$sql = "
    SELECT 
        v.id_visitante,
        v.nombre,
        v.motivo,
        v.numero,
        e.fecha
    FROM visitante v
    INNER JOIN estacionamiento e ON v.id_visitante = e.id_visitante
    WHERE e.accion = '0'
    AND e.fecha = (
        SELECT MAX(e2.fecha)
        FROM estacionamiento e2
        WHERE e2.id_visitante = v.id_visitante
    )
    ORDER BY e.fecha DESC
";

$result = $conn->query($sql);
$visitantes = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $visitantes[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($visitantes);
?>
