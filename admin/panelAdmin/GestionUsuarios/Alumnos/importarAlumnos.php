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

    $headersValidos = [
        "no_control","nombre","apellido","curp","f_nacimiento","correo","telefono",
        "grado","grupo","carrera","estado",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados del CSV no coinciden con el formato requerido.");
    }

    // SQL para alumno
    $sqlAlumno = "INSERT INTO alumno 
    (no_control,nombre,apellido,curp,f_nacimiento,correo,telefono,grado,grupo,carrera,estado)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)";

    // SQL para vehiculo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid,tipo_vehiculo,marca,modelo,año,color,placa,id_docente,no_control,id_personal,id_guardia,id_proveedor,id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtAlumno = $conexion->prepare($sqlAlumno);
    $stmtVehiculo = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // ---------------------------
        // INSERTAR ALUMNO
        // ---------------------------
        list($no_control,$nombre,$apellido,$curp,$f_n,$correo,$tel,$grado,$grupo,$carrera,$estado) =
            array_slice($data, 0, 11);

        $grado = intval($grado);

        $stmtAlumno->bind_param(
            "sssssssisss",
            $no_control,$nombre,$apellido,$curp,$f_n,$correo,$tel,
            $grado,$grupo,$carrera,$estado
        );
        $stmtAlumno->execute();


        // ---------------------------
        // INSERTAR VEHÍCULO
        // ---------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 11);

        // convertir INT
        $rfid = intval($rfid);
        $anio = intval($anio);

        // variables NULL
        $id_docente = null;
        $id_personal = null;
        $id_guardia = null;
        $id_proveedor = null;
        $id_administrativo = null;

        $stmtVehiculo->bind_param(
            "isssissisiiii",
            $rfid,          // i
            $tipo,          // s
            $marca,         // s
            $modelo,        // s
            $anio,          // i
            $color,         // s
            $placa,         // s
            $id_docente,    // i
            $no_control,    // s
            $id_personal,   // i
            $id_guardia,    // i
            $id_proveedor,  // i
            $id_administrativo // i
        );

        $stmtVehiculo->execute();
    }

    fclose($handle);
    echo "✔ Importación doble completada correctamente.";

} else {
    echo "❌ No se pudo abrir el archivo CSV.";
}
?>
