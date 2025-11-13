<?php
include 'conexion.php';

// Consulta que une visitantes con estacionamiento
$sql = "
SELECT 
    vte.numero AS numero_asignado,
    vte.nombre AS nombre,
    vte.motivo AS motivo,
    e.fecha AS fecha_hora,
    e.accion AS accion
FROM estacionamiento e
LEFT JOIN visitante vte ON e.id_visitante = vte.id_visitante
WHERE e.id_visitante IS NOT NULL
ORDER BY e.fecha DESC;
";

$result = $conn->query($sql);
$registros = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($registros);
?>
