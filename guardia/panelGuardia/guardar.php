<?php
include("conexion.php");

// Asegurar zona horaria local
date_default_timezone_set('America/Tijuana');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre_completo'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $numero = $_POST['numero_asignado'] ?? '';

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // 1️⃣ Insertar visitante
        $stmt1 = $conn->prepare("
            INSERT INTO visitante (nombre, motivo, numero)
            VALUES (?, ?, ?)
        ");
        $stmt1->bind_param("ssi", $nombre, $motivo, $numero);
        $stmt1->execute();

        // Obtener el ID autoincrement generado
        $idVisitante = $conn->insert_id;

        // 2️⃣ Registrar entrada en estacionamiento
        $fecha = date("Y-m-d H:i:s");
        $accion = "Entrada";

        $stmt2 = $conn->prepare("
            INSERT INTO estacionamiento (fecha, accion, id_visitante)
            VALUES (?, ?, ?)
        ");
        $stmt2->bind_param("ssi", $fecha, $accion, $idVisitante);
        $stmt2->execute();

        // Confirmar la transacción
        $conn->commit();
        echo "✅ Registro exitoso en visitantes y estacionamiento.";

    } catch (Exception $e) {
        // Revertir cambios si hay error
        $conn->rollback();
        echo "❌ Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
