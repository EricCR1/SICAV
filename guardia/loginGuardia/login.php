<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "ite";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("No hay conexión: " . mysqli_connect_error());
}

$nombre = mysqli_real_escape_string($conn, $_POST["usuario"]);
$password = mysqli_real_escape_string($conn, $_POST["password"]);

$query = "SELECT * FROM usuarios WHERE nombre='$nombre' AND contraseña='$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // ✅ Si el usuario existe, redirige al panel
    header("Location: ../panelGuardia/guardia.html");
    exit();
} else {
    // ❌ Usuario o contraseña incorrectos
    echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location.href='login_guardia.html';
          </script>";
}
?>
