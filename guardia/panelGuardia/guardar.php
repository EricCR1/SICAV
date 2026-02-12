<?php
include("conexion.php");

date_default_timezone_set('America/Tijuana');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre_completo'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $numero = $_POST['numero_asignado'] ?? '';

    // 1️⃣ Verificar si el número está ocupado por ALGÚN visitante cuya última acción sea ENTRADA (0)
    $sqlCheck = "
        SELECT e.accion
        FROM estacionamiento e
        INNER JOIN visitante v ON e.id_visitante = v.id_visitante
        WHERE v.numero = ?
        ORDER BY e.fecha DESC
        LIMIT 1
    ";

    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("i", $numero);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        $ultimo = (int)$resultCheck->fetch_assoc()['accion'];

        if ($ultimo === 0) { // 0 = Entrada
            echo "❌ El número $numero está OCUPADO actualmente.";
            exit;
        }
    }

    // Si está libre, continuar con el registro
    $conn->begin_transaction();

    try {

        // 2️⃣ Registrar visitante
        $stmt1 = $conn->prepare("
            INSERT INTO visitante (nombre, motivo, numero)
            VALUES (?, ?, ?)
        ");
        $stmt1->bind_param("ssi", $nombre, $motivo, $numero);
        $stmt1->execute();

        $idVisitante = $conn->insert_id;

        // 3️⃣ Registrar ENTRADA (0)
        $fecha = date("Y-m-d H:i:s");
        $accion = 0; // ENTRADA REAL

        $stmt2 = $conn->prepare("
            INSERT INTO estacionamiento (fecha, accion, id_visitante)
            VALUES (?, ?, ?)
        ");
        $stmt2->bind_param("sii", $fecha, $accion, $idVisitante);
        $stmt2->execute();

        $conn->commit();
        echo "✅ Registro exitoso. Número $numero asignado.";

    } catch (Exception $e) {
        $conn->rollback();
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
