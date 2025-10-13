<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php");

// Recibir datos del formulario
//$rfid      = $_POST['rfid'];
$tipo_usuario      = $_POST['tipo_usuario'];
$nombre      = $_POST['nombre'];
$apellido    = $_POST['apellido'];
$correo      = $_POST['correo'];
$telefono      = $_POST['telefono'];
$placas       = $_POST['placas'];
//$marca       = $_POST['marca'];
//$color       = $_POST['color'];
//$tipo        = $_POST['tipo_vehiculo'];

//Dependiendo el tipo de usuario, definimos la tabla
switch ($tipo_usuario) {
    case "alumno":
        $sql = "INSERT INTO alumno (no_control, nombre, apellido, CURP, f_nacimiento, correo, telefono, grado, grupo, carrera, estado)  VALUES 
        (23760307,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL, NULL, NULL)";

    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    case "docente":
        $sql = "INSERT INTO docente (id_docente, nombre, apellido, RFC, f_nacimiento, correo, telefono, especialidad, estado)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    case "administrativo":
        $tabla = "administrativos";
        break;
    case "guardia":
        $tabla = "guardias";
        break;
    case "personal":
        $tabla = "personal";
        break;
    case "proveedores":
        $tabla = "proveedores";
        break;
    default:
        die("Tipo de usuario no válido");
    }



$conn->close();

?>
