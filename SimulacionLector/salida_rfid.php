<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $rfid = $_POST["rfid"] ?? "";

    try {
        $sql = $conn->prepare("CALL registrarSalidaRFID(?)");
        $sql->bind_param("s", $rfid);
        $sql->execute();

        echo "✅ Salida registrada correctamente.";
        $python = "C:\\Users\\EricC\\AppData\\Local\\Programs\\Python\\Python313\\python.exe";
$script = "C:\\xampp\\htdocs\\SICAV\\SimulacionLector\\arduino_ledSalida.py";

$command = "$python $script 2>&1";
exec($command, $output);

// echo "<pre>";
// print_r($output);
// echo "</pre>";

    } catch (mysqli_sql_exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
