<?php
include 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'] ?? '';
    $tipo_movimiento = $_POST['tipo_movimiento'] ?? ''; // entrada o salida
    
    if (empty($rfid) || empty($tipo_movimiento)) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }

    try {
        // Insertar el movimiento en la tabla de movimientos
        $sql = "INSERT INTO movimientos (rfid, tipo_movimiento, fecha_hora) 
                VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $rfid, $tipo_movimiento);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al insertar movimiento']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>