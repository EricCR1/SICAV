<?php
include("../conexion.php");

// Consulta para obtener el último movimiento por RFID
$sql = "
    SELECT 
        v.rfid,
        e.accion,
        a.no_control,
        d.id_docente,
        ad.id_administrativo,
        g.id_guardia,
        p.id_personal,
        pr.id_proveedor
    FROM estacionamiento e
    INNER JOIN vehiculo v ON e.rfid = v.rfid
    LEFT JOIN alumno a ON v.no_control = a.no_control
    LEFT JOIN docente d ON v.id_docente = d.id_docente
    LEFT JOIN administrativo ad ON v.id_administrativo = ad.id_administrativo
    LEFT JOIN guardia g ON v.id_guardia = g.id_guardia
    LEFT JOIN personal p ON v.id_personal = p.id_personal
    LEFT JOIN proveedor pr ON v.id_proveedor = pr.id_proveedor
    WHERE e.fecha = (
        SELECT MAX(e2.fecha)
        FROM estacionamiento e2
        WHERE e2.rfid = e.rfid
    )
";

$result = $conn->query($sql);

// Contadores
$contadores = [
    "alumno" => 0,
    "docente" => 0,
    "administrativo" => 0,
    "personal" => 0,
    "guardia" => 0,
    "proveedor" => 0,
    "total" => 0
];

while ($row = $result->fetch_assoc()) {

    // Solo contamos si la última acción es Entrada (acción = 0 o 'Entrada')
    if ($row['accion'] == "0" || strtolower($row['accion']) == "entrada") {

        if (!empty($row['no_control'])) {
            $contadores["alumno"]++;
        }
        else if (!empty($row['id_docente'])) {
            $contadores["docente"]++;
        }
        else if (!empty($row['id_administrativo'])) {
            $contadores["administrativo"]++;
        }
        else if (!empty($row['id_personal'])) {
            $contadores["personal"]++;
        }
        else if (!empty($row['id_guardia'])) {
            $contadores["guardia"]++;
        }
        else if (!empty($row['id_proveedor'])) {
            $contadores["proveedor"]++;
        }
    }
}

// Total general
$contadores["total"] = 
    $contadores["alumno"] +
    $contadores["docente"] +
    $contadores["administrativo"] +
    $contadores["personal"] +
    $contadores["guardia"] +
    $contadores["proveedor"];

header("Content-Type: application/json");
echo json_encode($contadores);
?>
