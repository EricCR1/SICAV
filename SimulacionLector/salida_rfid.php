<?php
include("conexion.php");
date_default_timezone_set('America/Tijuana');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rfid = $_POST["rfid"] ?? "";

    // Validar que exista
    $sqlVeh = $conn->prepare("SELECT * FROM vehiculo WHERE rfid = ?");
    $sqlVeh->bind_param("s", $rfid);
    $sqlVeh->execute();
    $resVeh = $sqlVeh->get_result();

    if ($resVeh->num_rows === 0) {
        echo "❌ Este RFID no está registrado.";
        exit;
    }

    // Último movimiento
    $sqlUlt = $conn->prepare("
        SELECT accion 
        FROM estacionamiento 
        WHERE rfid = ?
        ORDER BY fecha DESC
        LIMIT 1
    ");
    $sqlUlt->bind_param("s", $rfid);
    $sqlUlt->execute();
    $resUlt = $sqlUlt->get_result();

    if ($resUlt->num_rows === 0) {
        echo "❌ No se puede registrar salida: nunca ha registrado entrada.";
        exit;
    }

    $ultima = $resUlt->fetch_assoc()["accion"];

    if ($ultima == "1") {
        echo "❌ El vehículo ya registró salida.";
        exit;
    }

    // Registrar salida
    $fecha = date("Y-m-d H:i:s");
    $accion = "1"; // 1 = salida

    $sqlInsert = $conn->prepare("
        INSERT INTO estacionamiento (fecha, accion, rfid) 
        VALUES (?, ?, ?)
    ");
    $sqlInsert->bind_param("sss", $fecha, $accion, $rfid);
    $sqlInsert->execute();

    echo "✅ Salida registrada correctamente.";
}
?>
