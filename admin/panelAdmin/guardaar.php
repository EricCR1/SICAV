<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include("conexion.php");

// Recibir datos del formulario
$rfid = $_POST['rfid'];
$tipo_usuario      = $_POST['tipo_usuario'];
$nombre      = $_POST['nombre'];
$apellido    = $_POST['apellido'];
$correo      = $_POST['correo'];
$telefono      = $_POST['telefono'];
$placas       = $_POST['placa'];
$nocontrol = $_POST['no_control'];
$curp = $_POST['curp'];
$fecha = $_POST['f_nacimiento'];
$correo = $_POST['correo'];
$grado = $_POST['grado'];
$grupo = $_POST['grupo'];
$carrera = $_POST['carrera'];
$estado = $_POST['estado'];
//$marca       = $_POST['marca'];
//$color       = $_POST['color'];
//$tipo        = $_POST['tipo_vehiculo'];

//Dependiendo el tipo de usuario, definimos la tabla
/*switch ($tipo_usuario) {

    //REGISTRO DE ALUMNOS
    case "Alumno":
        $sql = "INSERT INTO alumno (rfid, no_control, nombre, apellido, curp, f_nacimiento, correo, telefono, grado, grupo, carrera, estado)  VALUES 
        ('$rfid','$nocontrol','$nombre', '$apellido', '$curp' , '$fecha' , '$correo', '$telefono', '$grado', '$grupo', '$carrera', '$estado')";

    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    //REGISTRO DE DOCENTES
    case "docente":
        $sql = "INSERT INTO docente (id_docente, nombre, apellido, RFC, f_nacimiento, correo, telefono, especialidad, estado)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    //REGISTRO DE ADMINISTRATIVOS
    case "administrativo":
        $sql = "INSERT INTO administrativo (id_administrativo,nombre,apellido,RFC,f_nacimiento,correo,telefono,area,estado)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    //REGISTRO DE GUARDIAS
    case "guardia":
         $sql = "INSERT INTO administrativo (id_guardia,nombre,apellido,CURP,telefono,turno,estado)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    //REGISTRO DE PERSONAL
    case "personal":
        $sql = "INSERT INTO personal (id_personal,nombre,apellido,CURP,telefono,area,turno)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    //REGISTRO DE PROVEEDORES
    case "proveedores":
        $sql = "INSERT INTO proveedor (id_proveedor,nombre,apellido,razon_social,RFC,telefono,correo,tipo_servicio,empresa)  VALUES 
        (99,'$nombre', '$apellido', NULL, NULL, '$correo', '$telefono', NULL, NULL)";
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }
        break;
    default:
        die("Tipo de usuario no válido");
    }*/

    if ($tipo_usuario == 'alumno') {
        $sql = "INSERT INTO alumno (rfid, no_control, nombre, apellido, curp, f_nacimiento, correo, telefono, grado, grupo, carrera, estado)  VALUES 
        ('$rfid','$nocontrol','$nombre', '$apellido', '$curp' , '$fecha' , '$correo', '$telefono', '$grado', '$grupo', '$carrera', '$estado')";
    
    if ($conn->query($sql) === TRUE) {
    echo " Registro exitoso";
    } else {
    echo " Error: " . $conn->error;
    }

    }
$conn->close();
?>
