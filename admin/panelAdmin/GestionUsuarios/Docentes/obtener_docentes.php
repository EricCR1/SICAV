<?php
// obtener_docentes.php
include 'conexion.php'; // debe definir $conn (mysqli)
header('Content-Type: application/json; charset=utf-8');

if (!isset($conn) || !$conn) {
    echo json_encode(['error' => 'No hay conexión a la base de datos.']);
    exit;
}

// 1) Obtener todos los docentes
$sqlDoc = "SELECT id_docente, nombre, apellido, rfc, f_nacimiento, correo, telefono, especialidad, estado
           FROM docente";
$resDoc = $conn->query($sqlDoc);

$docentes = [];
if ($resDoc) {
    while ($d = $resDoc->fetch_assoc()) {
        $id = $d['id_docente'];
        $docentes[$id] = $d;
        $docentes[$id]['vehiculos'] = []; // inicializamos arreglo de vehículos
    }
} else {
    echo json_encode(['error' => 'Error al consultar docentes: ' . $conn->error]);
    exit;
}

// 2) Obtener todos los vehículos que tengan id_docente no nulo (para mapearlos)
$sqlVeh = "SELECT rfid, tipo_vehiculo, marca, modelo, `año`, color, placa, id_docente
           FROM vehiculo
           WHERE id_docente IS NOT NULL AND id_docente != ''";
$resVeh = $conn->query($sqlVeh);

if ($resVeh) {
    while ($v = $resVeh->fetch_assoc()) {
        $id_doc = $v['id_docente'];
        if (isset($docentes[$id_doc])) {
            // quitar id_docente del objeto vehículo por limpieza opcional
            unset($v['id_docente']);
            $docentes[$id_doc]['vehiculos'][] = $v;
        }
    }
} else {
    // Si no hay vehículos, no es fatal: devolvemos docentes sin vehículos
    // opcional: log o mensaje según quieras
}

// Reindexar como lista (no mapa por id) para facilidad del frontend
$output = array_values($docentes);

// Enviar JSON
echo json_encode($output, JSON_UNESCAPED_UNICODE);
$conn->close();

