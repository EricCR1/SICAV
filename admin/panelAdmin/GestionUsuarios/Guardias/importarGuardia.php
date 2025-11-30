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
        "id_guardia","nombre","apellido","curp","telefono","turno","estado",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados del CSV no coinciden con el formato requerido.");
    }

    // SQL guardia
    $sqlGuardia = "INSERT INTO guardia
    (id_guardia,nombre,apellido,curp,telefono,turno,estado)
    VALUES (?,?,?,?,?,?,?)";

    // SQL vehiculo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid,tipo_vehiculo,marca,modelo,año,color,placa,
     id_docente,no_control,id_personal,id_guardia,id_proveedor,id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtGuardia = $conexion->prepare($sqlGuardia);
    $stmtVehiculo = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // Evita filas vacías
        if (count($data) < 14) continue;

        // ---------------------------
        // INSERTAR GUARDIA
        // ---------------------------
        list($id_guardia,$nombre,$apellido,$curp,$telefono,$turno,$estado) =
            array_slice($data, 0, 7);

        $stmtGuardia->bind_param(
            "issssss",
            $id_guardia,$nombre,$apellido,$curp,$telefono,$turno,$estado
        );
        $stmtGuardia->execute();


        // ---------------------------
        // INSERTAR VEHÍCULO
        // ---------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 7);

        // INT
        $rfid = intval($rfid);
        $anio = intval($anio);

        // Campos NULL según tu modelo
        $id_docente = null;
        $no_control = null;
        $id_personal = null;
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
            $id_personal,     // personal
            $id_guardia,      // guardia (this!)
            $id_proveedor,    // proveedor
            $id_administrativo // administrativo
        );

        $stmtVehiculo->execute();
    }

    fclose($handle);
    echo "✔ Importación de guardias + vehículos completada correctamente.";

} else {
    echo "❌ No se pudo abrir el archivo CSV.";
}
?>
