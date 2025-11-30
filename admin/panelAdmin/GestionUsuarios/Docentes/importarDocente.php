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
        "id_docente","nombre","apellido","rfc","f_nacimiento","correo","telefono","especialidad","estado",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    // Leer encabezados reales
    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados del CSV no coinciden con el formato requerido.");
    }

    // SQL para insertar docente
    $sqlDocente = "INSERT INTO docente 
    (id_docente, nombre, apellido, rfc, f_nacimiento, correo, telefono, especialidad, estado)
    VALUES (?,?,?,?,?,?,?,?,?)";

    // SQL para insertar vehículo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid, tipo_vehiculo, marca, modelo, año, color, placa,
     id_docente, no_control, id_personal, id_guardia, id_proveedor, id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtDocente = $conexion->prepare($sqlDocente);
    $stmtVehiculo = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // -------------------------------------
        // INSERTAR DOCENTE
        // -------------------------------------
        list($id_docente, $nombre, $apellido, $rfc, $f_nac,
             $correo, $telefono, $especialidad, $estado) = array_slice($data, 0, 9);

        $id_docente = intval($id_docente);

        $stmtDocente->bind_param(
            "issssssss",
            $id_docente, $nombre, $apellido, $rfc, $f_nac,
            $correo, $telefono, $especialidad, $estado
        );
        $stmtDocente->execute();


        // -------------------------------------
        // INSERTAR VEHÍCULO
        // -------------------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 9);

        $rfid = intval($rfid);
        $anio = intval($anio);

        // Campos no utilizados (deben ser NULL)
        $no_control = null;
        $id_personal = null;
        $id_guardia = null;
        $id_proveedor = null;
        $id_administrativo = null;

        $stmtVehiculo->bind_param(
            "isssissisiiii",
            $rfid,        // i
            $tipo,        // s
            $marca,       // s
            $modelo,      // s
            $anio,        // i
            $color,       // s
            $placa,       // s
            $id_docente,  // i (relación docente → vehículo)
            $no_control,  // i? (NULL)
            $id_personal, // i
            $id_guardia,  // i
            $id_proveedor,// i
            $id_administrativo // i
        );

        $stmtVehiculo->execute();
    }

    fclose($handle);
    echo "✔ Importación de docentes + vehículos completada correctamente.";

} else {
    echo "❌ No se pudo abrir el archivo CSV.";
}
?>
