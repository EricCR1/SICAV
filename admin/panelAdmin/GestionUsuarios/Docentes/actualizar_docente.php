<?php
include "conexion.php";
date_default_timezone_set('America/Tijuana');

$id_docente = $_POST['id_docente'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$f_nacimiento = $_POST['f_nacimiento'] ?? null;
$correo = $_POST['correo'] ?? null;
$telefono = $_POST['telefono'] ?? null;
$especialidad = $_POST['especialidad'] ?? null;
$estado = $_POST['estado'] ?? 'activo';
$vehiculos_json = $_POST['vehiculos'] ?? '[]';
$vehiculos = json_decode($vehiculos_json, true) ?: [];

if (!$id_docente) {
    http_response_code(400);
    echo "Falta id_docente";
    exit;
}

$conn->begin_transaction();
try {
    // actualizar docente
    $stmt = $conn->prepare("UPDATE docente SET nombre=?, apellido=?, rfc=?, f_nacimiento=?, correo=?, telefono=?, especialidad=?, estado=? WHERE id_docente=?");
    $stmt->bind_param("sssssssss", $nombre,$apellido,$rfc,$f_nacimiento,$correo,$telefono,$especialidad,$estado,$id_docente);
    $stmt->execute();

    // eliminar vehículos anteriores asociados a este docente
    $del = $conn->prepare("DELETE FROM vehiculo WHERE id_docente = ?");
    $del->bind_param("s", $id_docente);
    $del->execute();

    // insertar vehículos actuales (si los hay)
    if (!empty($vehiculos)) {
        $stmtv = $conn->prepare("INSERT INTO vehiculo (rfid,tipo_vehiculo,marca,modelo,año,color,placa,id_docente) VALUES (?,?,?,?,?,?,?,?)");
        foreach ($vehiculos as $v) {
            $rfid = $v['rfid'] ?? null;
            $tipo = $v['tipo_vehiculo'] ?? null;
            $marca = $v['marca'] ?? null;
            $modelo = $v['modelo'] ?? null;
            $anio = $v['año'] ?? null;
            $color = $v['color'] ?? null;
            $placa = $v['placa'] ?? null;
            $stmtv->bind_param("ssssssss",$rfid,$tipo,$marca,$modelo,$anio,$color,$placa,$id_docente);
            $stmtv->execute();
        }
    }

    $conn->commit();
    echo "Docente actualizado correctamente.";
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
