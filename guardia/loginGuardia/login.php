<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "ite";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("No hay conexión: " . mysqli_connect_error());
}

$usuario   = $_POST['usuario'] ?? '';
$pass      = $_POST['password'] ?? '';

$sql = $conn->prepare("CALL validar_usuario(?, ?)");
$sql->bind_param("ss", $usuario, $pass);
$sql->execute();

$res = $sql->get_result();

if ($res->num_rows == 1) {
    $row = $res->fetch_assoc();

    // Validación por rol
    if ($row["rol"] == "admin") {
        header("Location: ../panelAdmin/admin.html");
        exit;
    } 
    else if ($row["rol"] == "guardia") {
        header("Location: ../panelGuardia/guardia.html");
        exit;
    }
} 
else {
    echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location.href='login_guardia.html';
          </script>";
}
?>
