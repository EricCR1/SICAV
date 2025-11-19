<?php
include 'conexion.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
    echo "ID faltante.";
    exit;
}

$conn->begin_transaction();
try {
    // eliminar vehiculos asociados
    $stmtV = $conn->prepare("DELETE FROM vehiculo WHERE id_administrativo = ?");
    $stmtV->bind_param("s", $id);
    $stmtV->execute();

    // eliminar administrativo
    $stmtA = $conn->prepare("DELETE FROM administrativo WHERE id_administrativo = ?");
    $stmtA->bind_param("s", $id);
    $stmtA->execute();

    $conn->commit();
    echo "✅ Administrativo eliminado.";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
