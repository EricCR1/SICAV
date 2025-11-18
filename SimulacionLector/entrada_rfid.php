<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rfid = $_POST["rfid"] ?? "";

    try {
        $sql = $conn->prepare("CALL registrarEntradaRFID(?)");
        $sql->bind_param("s", $rfid);
        $sql->execute();

        echo "✅ Entrada registrada correctamente.";

    } catch (mysqli_sql_exception $e) {

        // Mensaje de error enviado desde SIGNAL
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
