<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rfid = $_POST["rfid"] ?? "";

    try {
        $sql = $conn->prepare("CALL registrarSalidaRFID(?)");
        $sql->bind_param("s", $rfid);
        $sql->execute();

        echo "✅ Salida registrada correctamente.";

    } catch (mysqli_sql_exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
