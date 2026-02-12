<?php
include "conexion.php";

$id = $_GET["id"];

// eliminar vehiculo asociado
$conn->query("DELETE FROM vehiculo WHERE id_proveedor='$id'");

// eliminar proveedor
$conn->query("DELETE FROM proveedor WHERE id_proveedor='$id'");

echo "Proveedor eliminado.";
?>
