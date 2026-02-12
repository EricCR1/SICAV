<?php
include("conexion.php");
date_default_timezone_set('America/Tijuana');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id_visitante'] ?? null;

    if (!$id) {
        echo json_encode(["status" => "error", "msg" => "ID no recibido"]);
        exit;
    }

    $fecha = date("Y-m-d H:i:s");
    $accion = "1"; // salida

    $stmt = $conn->prepare("
        INSERT INTO estacionamiento (fecha, accion, id_visitante)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("ssi", $fecha, $accion, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "ok", "msg" => "Salida registrada"]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Error al registrar salida"]);
    }

    $conn->close();
}
?>
