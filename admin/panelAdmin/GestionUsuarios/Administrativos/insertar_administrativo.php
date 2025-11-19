<?php
include 'conexion.php';
date_default_timezone_set('America/Tijuana');

// Recolectar y validar
$id = $_POST['id_administrativo'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$f_nacimiento = $_POST['f_nacimiento'] ?? null;
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$area = $_POST['area'] ?? '';
$estado = $_POST['estado'] ?? 'activo';
$vehiclesJson = $_POST['vehicles'] ?? '[]';
$vehicles = json_decode($vehiclesJson, true);

if (empty($id) || empty($nombre)) {
    echo "Faltan datos obligatorios.";
    exit;
}

$conn->begin_transaction();

try {
    // insertar administrativo
    $stmt = $conn->prepare("INSERT INTO administrativo (id_administrativo, nombre, apellido, rfc, f_nacimiento, correo, telefono, area, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $id, $nombre, $apellido, $rfc, $f_nacimiento, $correo, $telefono, $area, $estado);
    $stmt->execute();

    // insertar vehiculos (si los hay)
    if (is_array($vehicles) && count($vehicles) > 0) {
        $stmtV = $conn->prepare("INSERT INTO vehiculo (rfid, tipo_vehiculo, marca, modelo, `año`, color, placa, id_administrativo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($vehicles as $v) {
            $rfid = $v['rfid'] ?? null;
            $tipo = $v['tipo_vehiculo'] ?? null;
            $marca = $v['marca'] ?? null;
            $modelo = $v['modelo'] ?? null;
            $anio = $v['año'] ?? null;
            $color = $v['color'] ?? null;
            $placa = $v['placa'] ?? null;
            $stmtV->bind_param("ssssssss", $rfid, $tipo, $marca, $modelo, $anio, $color, $placa, $id);
            $stmtV->execute();
        }
    }

    $conn->commit();
    echo "✅ Administrativo insertado correctamente.";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
