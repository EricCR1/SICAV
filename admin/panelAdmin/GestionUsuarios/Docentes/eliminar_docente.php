<?php
include "conexion.php";

$id = $_GET['id'] ?? '';
if (!$id) {
    http_response_code(400);
    echo "Falta id";
    exit;
}

$conn->begin_transaction();
try {
    // eliminar vehiculos del docente
    $delV = $conn->prepare("DELETE FROM vehiculo WHERE id_docente=?");
    $delV->bind_param("s", $id);
    $delV->execute();

    // eliminar docente
    $delD = $conn->prepare("DELETE FROM docente WHERE id_docente=?");
    $delD->bind_param("s", $id);
    $delD->execute();

    $conn->commit();
    echo "Docente eliminado correctamente.";
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
?>
