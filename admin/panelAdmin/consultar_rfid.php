<?php
include 'conexion.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'] ?? '';
    
    if (empty($rfid)) {
        echo json_encode(['existe' => false, 'error' => 'RFID vacío']);
        exit;
    }

    // Buscar el RFID en todas las tablas
    $tablas = ['alumno', 'docente', 'administrativo', 'guardia', 'personal', 'proveedor'];
    $usuario = null;
    $tabla_encontrada = '';

    foreach ($tablas as $tabla) {
        $sql = "SELECT * FROM $tabla WHERE rfid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $rfid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            $tabla_encontrada = $tabla;
            break;
        }
    }

    if ($usuario) {
        // Obtener información del vehículo
        $sql_vehiculo = "SELECT * FROM vehiculo WHERE 
                        (no_control = ? OR id_docente = ? OR id_administrativo = ? OR 
                         id_guardia = ? OR id_personal = ? OR id_proveedor = ?)";
        $stmt_vehiculo = $conn->prepare($sql_vehiculo);
        
        // Pasar los mismos valores para todos los parámetros (simplificación)
        $id = $usuario['no_control'] ?? $usuario['id_docente'] ?? $usuario['id_administrativo'] ?? 
              $usuario['id_guardia'] ?? $usuario['id_personal'] ?? $usuario['id_proveedor'] ?? '';
        
        $stmt_vehiculo->bind_param("ssssss", $id, $id, $id, $id, $id, $id);
        $stmt_vehiculo->execute();
        $vehiculo = $stmt_vehiculo->get_result()->fetch_assoc();

        // Verificar estado actual (dentro/fuera)
        // Aquí necesitarías una tabla de movimientos para saber el estado actual
        // Por ahora, simulamos con una consulta simple a una tabla de movimientos
        $sql_estado = "SELECT tipo_movimiento FROM movimientos 
                      WHERE rfid = ? 
                      ORDER BY fecha_hora DESC 
                      LIMIT 1";
        $stmt_estado = $conn->prepare($sql_estado);
        $stmt_estado->bind_param("s", $rfid);
        $stmt_estado->execute();
        $result_estado = $stmt_estado->get_result();
        
        $estado = 'fuera'; // Por defecto
        if ($result_estado->num_rows > 0) {
            $ultimo_movimiento = $result_estado->fetch_assoc();
            $estado = $ultimo_movimiento['tipo_movimiento'] === 'entrada' ? 'dentro' : 'fuera';
        }

        echo json_encode([
            'existe' => true,
            'nombre' => ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellido'] ?? ''),
            'tipo' => $tabla_encontrada,
            'vehiculo' => $vehiculo ? ($vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' (' . $vehiculo['placa'] . ')') : 'Sin vehículo',
            'estado' => $estado
        ]);
    } else {
        echo json_encode(['existe' => false]);
    }
}
?>