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

    // Encabezados esperados
    $headersValidos = [
        "id_proveedor","nombre","apellido","razon_social","rfc","telefono","correo","tipo_servicio","empresa",
        "rfid","tipo_vehiculo","marca","modelo","año","color","placa"
    ];

    $headers = fgetcsv($handle, 1000, ",");

    if ($headers !== $headersValidos) {
        die("❌ Error: Los encabezados no coinciden con el formato requerido.");
    }

    // SQL proveedor
    $sqlProveedor = "INSERT INTO proveedor 
    (id_proveedor,nombre,apellido,razon_social,rfc,telefono,correo,tipo_servicio,empresa)
    VALUES (?,?,?,?,?,?,?,?,?)";

    // SQL vehículo
    $sqlVehiculo = "INSERT INTO vehiculo 
    (rfid,tipo_vehiculo,marca,modelo,año,color,placa,id_docente,no_control,id_personal,id_guardia,id_proveedor,id_administrativo)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmtProveedor = $conexion->prepare($sqlProveedor);
    $stmtVehiculo  = $conexion->prepare($sqlVehiculo);

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

        // ---------------------------
        // 1) INSERTAR PROVEEDOR
        // ---------------------------
        list($id_proveedor,$nombre,$apellido,$razon,$rfc,$tel,$correo,$servicio,$empresa) =
            array_slice($data, 0, 9);

        $stmtProveedor->bind_param(
            "issssssss",
            $id_proveedor,$nombre,$apellido,$razon,$rfc,$tel,$correo,$servicio,$empresa
        );
        $stmtProveedor->execute();


        // ---------------------------
        // 2) INSERTAR VEHÍCULO
        // ---------------------------
        list($rfid,$tipo,$marca,$modelo,$anio,$color,$placa) =
            array_slice($data, 9);

        $rfid = intval($rfid);
        $anio = intval($anio);

        // SET NULL PARA CAMPOS NO APLICABLES
        $id_docente = null;
        $no_control = null;
        $id_personal = null;
        $id_guardia = null;
        $id_administrativo = null;

        $stmtVehiculo->bind_param(
            "isssissisiiii",
            $rfid,$tipo,$marca,$modelo,$anio,$color,$placa,
            $id_docente,$no_control,$id_personal,$id_guardia,$id_proveedor,$id_administrativo
        );
        $stmtVehiculo->execute();
    }

    fclose($handle);
    echo "✔ Importación de proveedores completada.";

} else {
    echo "❌ No se pudo abrir el archivo CSV.";
}
?>
