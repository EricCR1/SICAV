<?php
include("conexion.php");
date_default_timezone_set('America/Tijuana');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rfid = $_POST["rfid"] ?? "";

    // Validar que exista en vehiculo
    $sqlVeh = $conn->prepare("SELECT * FROM vehiculo WHERE rfid = ?");
    $sqlVeh->bind_param("s", $rfid);
    $sqlVeh->execute();
    $resVeh = $sqlVeh->get_result();

    if ($resVeh->num_rows === 0) {
        echo "❌ El RFID no existe en la base de datos.";
        exit;
    }

    // Revisar último movimiento
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

    if ($resUlt->num_rows > 0) {
        $ultima = $resUlt->fetch_assoc()["accion"]; // 0 = entrada, 1 = salida

        if ($ultima == "0") {
            echo "❌ El vehículo ya se encuentra dentro. No puedes registrar otra entrada.";
            exit;
        }
    }

    // Registrar entrada
    $fecha = date("Y-m-d H:i:s");
    $accion = "0"; // 0 = entrada

    $sqlInsert = $conn->prepare("
        INSERT INTO estacionamiento (fecha, accion, rfid) 
        VALUES (?, ?, ?)
    ");
    $sqlInsert->bind_param("sss", $fecha, $accion, $rfid);
    $sqlInsert->execute();

    echo "✅ Entrada registrada correctamente.";
}
?>
