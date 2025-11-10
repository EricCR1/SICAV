<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';

    // Iniciar transacción
    $conn->begin_transaction();

    try {
        // Variables del vehículo (comunes para todos)
        $tipo_vehiculo = $_POST['tipo_vehiculo'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $año = $_POST['año'];
        $color = $_POST['color'];
        $placa = $_POST['placa'];

        // Variable para guardar el nombre del campo FK
        $fk_field = "";
        $fk_value = "";

        switch ($tipo_usuario) {
            //ALUMNO
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
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO alumno 
                        (no_control, curp, nombre, apellido, f_nacimiento, correo, telefono, grado, grupo, carrera, estado, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssss", $no_control, $curp, $nombre, $apellido, $f_nacimiento, $correo, $telefono, $grado, $grupo, $carrera, $estado, $rfid);
                $stmt->execute();

                $fk_field = "no_control";
                $fk_value = $no_control;
                break;

            //DOCENTE 
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
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO docente 
                        (id_docente, rfc, nombre, apellido, f_nacimiento, correo, telefono, especialidad, estado, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssss", $id_docente, $rfc, $nombre, $apellido, $f_nacimiento, $correo, $telefono, $especialidad, $estado, $rfid);
                $stmt->execute();

                $fk_field = "id_docente";
                $fk_value = $id_docente;
                break;

            ///ADMINISTRATIVO

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
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO administrativo 
                        (id_administrativo, nombre, apellido, rfc, f_nacimiento, correo, telefono, area , estado, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssss", $id_administrativo, $nombre, $apellido, $rfc, $f_nacimiento, $correo, $telefono,$departamento, $estado, $rfid);
                $stmt->execute();

                $fk_field = "id_administrativo";
                $fk_value = $id_administrativo;
                break;

           //GUARDIA 
            case 'guardia':
                $id_guardia = $_POST['id_guardia'];
                $curp = $_POST['curp'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $turno = $_POST['turno'];
                $estado = $_POST['estado'];
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO guardia 
                        (id_guardia, curp, nombre, apellido, telefono, turno, estado, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $id_guardia, $curp, $nombre, $apellido, $telefono, $turno, $estado, $rfid);
                $stmt->execute();

                $fk_field = "id_guardia";
                $fk_value = $id_guardia;
                break;

            //PERSONAL
            case 'personal':
                $id_personal = $_POST['id_personal'];
                $curp = $_POST['curp'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $telefono = $_POST['telefono'];
                $area = $_POST['area'];
                $turno = $_POST['turno'];
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO personal 
                        (id_personal, curp, nombre, apellido, telefono, area, turno, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $id_personal, $curp, $nombre, $apellido, $telefono, $area, $turno, $rfid);
                $stmt->execute();

                $fk_field = "id_personal";
                $fk_value = $id_personal;
                break;

            //PROVEEDOR

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
                $rfid = $_POST['rfid'];

                $sql = "INSERT INTO proveedor 
                        (id_proveedor, rfc, nombre, apellido, razon_social, empresa, telefono, correo, tipo_servicio, rfid)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssss", $id_proveedor, $rfc, $nombre, $apellido, $razon_social, $empresa, $telefono, $correo, $tipo_servicio, $rfid);
                $stmt->execute();

                $fk_field = "id_proveedor";
                $fk_value = $id_proveedor;
                break;

            default:
                throw new Exception("Tipo de usuario no reconocido.");
        }

        //VEHICULO
        $sql_carro = "INSERT INTO vehiculo 
            (tipo_vehiculo, marca, modelo, año, color, placa, id_docente, no_control, id_personal, id_guardia, id_proveedor, id_administrativo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt_carro = $conn->prepare($sql_carro);

        // Inicializar todas las FK como NULL
        $id_docente = $no_control = $id_personal = $id_guardia = $id_proveedor = $id_administrativo = null;

        // Asignar solo la FK correspondiente al tipo de usuario
        if ($fk_field === "id_docente") $id_docente = $fk_value;
        if ($fk_field === "no_control") $no_control = $fk_value;
        if ($fk_field === "id_personal") $id_personal = $fk_value;
        if ($fk_field === "id_guardia") $id_guardia = $fk_value;
        if ($fk_field === "id_proveedor") $id_proveedor = $fk_value;
        if ($fk_field === "id_administrativo") $id_administrativo = $fk_value;

        $stmt_carro->bind_param(
            "ssssssssssss",
            $tipo_vehiculo, $marca, $modelo, $año, $color, $placa,
            $id_docente, $no_control, $id_personal, $id_guardia, $id_proveedor, $id_administrativo
        );
        $stmt_carro->execute();

        // Confirmar transacción
        $conn->commit();

        echo "Registro exitoso de $tipo_usuario y su vehículo.";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>
