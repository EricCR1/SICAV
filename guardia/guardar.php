<?php
include("conexion.php");

// Recibir datos del formulario
$nombre   = $_POST['nombre'];
$apellido = $_POST['apellido'];
$motivo   = $_POST['motivo'];
$placa    = $_POST['placa'];
$marca    = $_POST['marca'];
$color    = $_POST['color'];
$tipo     = $_POST['tipo_vehiculo'];

// Asegurar fecha local
date_default_timezone_set('America/Tijuana');

// Iniciar transacción
$conn->begin_transaction();

try {
    // 1 Insertar en visitante
    $stmt1 = $conn->prepare("
        INSERT INTO visitante (nombre, apellido, tipo_vehiculo, marca_carro, color, placas, motivo)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    // 7 columnas → 7 parámetros → 7 's'
    $stmt1->bind_param("sssssss", $nombre, $apellido, $tipo, $marca, $color, $placa, $motivo);
    $stmt1->execute();

    // Obtener ID autoincrement del visitante
    $idVisitante = $conn->insert_id;

    // 2 Insertar en la tabla estacionamiento
    $fecha = date("Y-m-d H:i:s"); 
    $accion = "Entrada";     // Texto
    $id_carro = NULL;        // Puede ser nulo

    $stmt2 = $conn->prepare("
        INSERT INTO estacionamiento (fecha, accion, id_carro, id_visitante)
        VALUES (?, ?, ?, ?)
    ");

    // 4 parámetros: string, string, int, int
    $stmt2->bind_param("ssii", $fecha, $accion, $id_carro, $idVisitante);
    $stmt2->execute();

    // Confirmar transacción
    $conn->commit();
    echo "Registro exitoso en ambas tablas.";

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>

