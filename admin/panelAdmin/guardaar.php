<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Variables comunes del vehículo
        $rfid = $_POST['rfid']; // 👈 el RFID ahora pertenece al vehículo
        $tipo_vehiculo = $_POST['tipo_vehiculo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $año = $_POST['año'];
        $color = $_POST['color'];
        $placa = $_POST['placa'];

        $fk_field = "";
        $fk_value = "";

        switch ($tipo_usuario) {
            case 'alumno':
                $no_control = $_POST['no_control'];
                $curp = $_POST['curp'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $f_nacimiento = $_POST['f_nacimiento'];
                $correo = $_POST['correo'];
                $telefono = $_POST['telefono'];
                $grado = $_POST['grado'];
                $grupo = $_POST['grupo'];
                $carrera = $_POST['carrera'];
                $estado = $_POST['estado'];

                $sql = "INSERT INTO alumno 
                        (no_control, curp, nombre, apellido, f_nacimiento, correo, telefono, grado, grupo, carrera, estado)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssss", $no_control, $curp, $nombre, $apellido, $f_nacimiento, $correo, $telefono, $grado, $grupo, $carrera, $estado);
                $stmt->execute();

                $fk_field = "no_control";
                $fk_value = $no_control;
                break;

            case 'docente':
                $id_docente = $_POST['id_docente'];
                $rfc = $_POST['rfc'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $f_nacimiento = $_POST['f_nacimiento'];
                $correo = $_POST['correo'];
                $telefono = $_POST['telefono'];
                $especialidad = $_POST['especialidad'];
                $estado = $_POST['estado'];

                $sql = "INSERT INTO docente 
                        (id_docente, rfc, nombre, apellido, f_nacimiento, correo, telefono, especialidad, estado)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $id_docente, $rfc, $nombre, $apellido, $f_nacimiento, $correo, $telefono, $especialidad, $estado);
                $stmt->execute();

                $fk_field = "id_docente";
                $fk_value = $id_docente;
                break;

            case 'administrativo':
                $id_administrativo = $_POST['id_administrativo'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $rfc = $_POST['rfc'];
                $f_nacimiento = $_POST['f_nacimiento'];
                $correo = $_POST['correo'];
                $telefono = $_POST['telefono'];
                $departamento = $_POST['departamento'];
                $estado = $_POST['estado'];

                $sql = "INSERT INTO administrativo 
                        (id_administrativo, nombre, apellido, rfc, f_nacimiento, correo, telefono, area, estado)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $id_administrativo, $nombre, $apellido, $rfc, $f_nacimiento, $correo, $telefono, $departamento, $estado);
                $stmt->execute();

                $fk_field = "id_administrativo";
                $fk_value = $id_administrativo;
                break;

            case 'guardia':
                $id_guardia = $_POST['id_guardia'];
                $curp = $_POST['curp'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $turno = $_POST['turno'];
                $estado = $_POST['estado'];

                $sql = "INSERT INTO guardia 
                        (id_guardia, curp, nombre, apellido, telefono, turno, estado)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $id_guardia, $curp, $nombre, $apellido, $telefono, $turno, $estado);
                $stmt->execute();

                $fk_field = "id_guardia";
                $fk_value = $id_guardia;
                break;

            case 'personal':
                $id_personal = $_POST['id_personal'];
                $curp = $_POST['curp'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $area = $_POST['area'];
                $turno = $_POST['turno'];

                $sql = "INSERT INTO personal 
                        (id_personal, curp, nombre, apellido, telefono, area, turno)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $id_personal, $curp, $nombre, $apellido, $telefono, $area, $turno);
                $stmt->execute();

                $fk_field = "id_personal";
                $fk_value = $id_personal;
                break;

            case 'proveedor':
                $id_proveedor = $_POST['id_proveedor'];
                $rfc = $_POST['rfc'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $razon_social = $_POST['razon_social'];
                $empresa = $_POST['empresa'];
                $telefono = $_POST['telefono'];
                $correo = $_POST['correo'];
                $tipo_servicio = $_POST['tipo_servicio'];

                $sql = "INSERT INTO proveedor 
                        (id_proveedor, rfc, nombre, apellido, razon_social, empresa, telefono, correo, tipo_servicio)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssss", $id_proveedor, $rfc, $nombre, $apellido, $razon_social, $empresa, $telefono, $correo, $tipo_servicio);
                $stmt->execute();

                $fk_field = "id_proveedor";
                $fk_value = $id_proveedor;
                break;

            default:
                throw new Exception("Tipo de usuario no reconocido.");
        }

        // Insertar vehículo con RFID
        $sql_carro = "INSERT INTO vehiculo 
            (rfid, tipo_vehiculo, marca, modelo, año, color, placa, id_docente, no_control, id_personal, id_guardia, id_proveedor, id_administrativo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_carro = $conn->prepare($sql_carro);

        // Inicializar todas las FK como NULL
        $id_docente = $no_control = $id_personal = $id_guardia = $id_proveedor = $id_administrativo = null;

        if ($fk_field === "id_docente") $id_docente = $fk_value;
        if ($fk_field === "no_control") $no_control = $fk_value;
        if ($fk_field === "id_personal") $id_personal = $fk_value;
        if ($fk_field === "id_guardia") $id_guardia = $fk_value;
        if ($fk_field === "id_proveedor") $id_proveedor = $fk_value;
        if ($fk_field === "id_administrativo") $id_administrativo = $fk_value;

        $stmt_carro->bind_param(
            "sssssssssssss",
            $rfid, $tipo_vehiculo, $marca, $modelo, $año, $color, $placa,
            $id_docente, $no_control, $id_personal, $id_guardia, $id_proveedor, $id_administrativo
        );
        $stmt_carro->execute();

        $conn->commit();
        echo "<script>
        alert('Registro Correcto');
            window.history.back();
          </script>";

    } catch (Exception $e) {
         $conn->rollback();

    $error = addslashes($e->getMessage()); // evitar romper el script
    echo "<script>
            alert('❌ Error: $error');
            window.history.back();
          </script>";
    }
}
?>

