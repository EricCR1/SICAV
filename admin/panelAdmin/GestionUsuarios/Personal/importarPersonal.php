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
        "id_personal","nombre","apellido","curp","telefono","area","turno",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados del CSV no coinciden con el formato requerido.");
    }

    // SQL personal
    $sqlPersonal = "INSERT INTO personal
    (id_personal,nombre,apellido,curp,telefono,area,turno)
    VALUES (?,?,?,?,?,?,?)";

    // SQL vehiculo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid,tipo_vehiculo,marca,modelo,año,color,placa,
     id_docente,no_control,id_personal,id_guardia,id_proveedor,id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtPersonal = $conexion->prepare($sqlPersonal);
    $stmtVehiculo = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        if (count($data) < 14) continue;

        // ------------------------------------
        // INSERTAR PERSONAL
        // ------------------------------------
        list($id_personal,$nombre,$apellido,$curp,$telefono,$area,$turno) =
            array_slice($data, 0, 7);

        $stmtPersonal->bind_param(
            "issssss",
            $id_personal,$nombre,$apellido,$curp,$telefono,$area,$turno
        );
        $stmtPersonal->execute();


        // ------------------------------------
        // INSERTAR VEHÍCULO
        // ------------------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 7);

        $rfid = intval($rfid);
        $anio = intval($anio);

        // Campos NULL
        $id_docente = null;
        $no_control = null;
        $id_guardia = null;
        $id_proveedor = null;
        $id_administrativo = null;

        $stmtVehiculo->bind_param(
            "isssissisiiii",
            $rfid,
            $tipo,
            $marca,
            $modelo,
            $anio,
            $color,
            $placa,
            $id_docente,      // docente
            $no_control,      // alumno
            $id_personal,     // personal (this!)
            $id_guardia,      // guardia
            $id_proveedor,    // proveedor
            $id_administrativo // administrativo
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
