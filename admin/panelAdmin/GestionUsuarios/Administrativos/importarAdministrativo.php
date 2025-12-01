<?php
$conexion = new mysqli("localhost", "root", "", "ite");

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== 0) {
    die("Error al subir el archivo CSV");
}

$archivo = $_FILES['archivo']['tmp_name'];

if (($handle = fopen($archivo, "r")) !== FALSE) {

    // Encabezados requeridos
    $headersValidos = [
        "id_administrativo","nombre","apellido","rfc","f_nacimiento","correo","telefono","area","estado",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados del CSV no coinciden con el formato requerido.");
    }

    // SQL administrativo
    $sqlAdmin = "INSERT INTO administrativo
    (id_administrativo,nombre,apellido,rfc,f_nacimiento,correo,telefono,area,estado)
    VALUES (?,?,?,?,?,?,?,?,?)";

    // SQL vehiculo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid,tipo_vehiculo,marca,modelo,año,color,placa,id_docente,no_control,id_personal,id_guardia,id_proveedor,id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtAdmin = $conexion->prepare($sqlAdmin);
    $stmtVehiculo = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // Evitar filas vacías
        if (count($data) < 16) continue;

        // ---------------------------
        // INSERTAR ADMINISTRATIVO
        //---------------------------
        list($id_admin,$nombre,$apellido,$rfc,$f_n,$correo,$tel,$area,$estado) =
            array_slice($data, 0, 9);

        $stmtAdmin->bind_param(
            "issssssss",
            $id_admin,$nombre,$apellido,$rfc,$f_n,$correo,$tel,$area,$estado
        );

        $stmtAdmin->execute();


        // ---------------------------
        // INSERTAR VEHÍCULO
        //---------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 9);

        // convertir INT
        $rfid = intval($rfid);
        $anio = intval($anio);

        // NULL para claves no usadas
        $id_docente = null;
        $no_control = null;
        $id_personal = null;
        $id_guardia = null;
        $id_proveedor = null;

        $stmtVehiculo->bind_param(
            "isssissisiiii",
            $rfid,
            $tipo,
            $marca,
            $modelo,
            $anio,
            $color,
            $placa,
            $id_docente,
            $no_control,
            $id_personal,
            $id_guardia,
            $id_proveedor,
            $id_admin
        );

        $stmtVehiculo->execute();
    }

    fclose($handle);
    echo "<script>
        alert('Registro Correcto');
            window.history.back();
          </script>";

} else {
    echo "<script>
        alert('No se puedo importar el archivo');
            window.history.back();
          </script>";
}
?>
