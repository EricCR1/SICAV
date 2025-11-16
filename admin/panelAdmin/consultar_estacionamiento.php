<?php
include 'conexion.php';

// Consulta que obtiene solo usuarios con RFID válidos (excluye visitantes por completo)
$sql = "
SELECT 
    COALESCE(a.nombre, d.nombre, ad.nombre, g.nombre, p.nombre, pr.nombre) AS nombre,
    COALESCE(a.apellido, d.apellido, ad.apellido, g.apellido, p.apellido, pr.apellido) AS apellido,
    CASE
        WHEN a.no_control IS NOT NULL THEN 'Alumno'
        WHEN d.id_docente IS NOT NULL THEN 'Docente'
        WHEN ad.id_administrativo IS NOT NULL THEN 'Administrativo'
        WHEN g.id_guardia IS NOT NULL THEN 'Guardia'
        WHEN p.id_personal IS NOT NULL THEN 'Personal'
        WHEN pr.id_proveedor IS NOT NULL THEN 'Proveedor'
    END AS perfil,
    COALESCE(a.telefono, d.telefono, ad.telefono, g.telefono, p.telefono, pr.telefono) AS telefono,
    COALESCE(a.correo, d.correo, ad.correo, pr.correo) AS correo,
    v.placa,
    e.fecha AS fecha_hora,
    e.accion
FROM estacionamiento e
LEFT JOIN vehiculo v ON e.rfid = v.rfid
LEFT JOIN alumno a ON v.no_control = a.no_control
LEFT JOIN docente d ON v.id_docente = d.id_docente
LEFT JOIN administrativo ad ON v.id_administrativo = ad.id_administrativo
LEFT JOIN guardia g ON v.id_guardia = g.id_guardia
LEFT JOIN personal p ON v.id_personal = p.id_personal
LEFT JOIN proveedor pr ON v.id_proveedor = pr.id_proveedor
-- 🔹 Esta condición excluye los registros sin usuario asociado (visitantes)
WHERE 
    a.no_control IS NOT NULL
    OR d.id_docente IS NOT NULL
    OR ad.id_administrativo IS NOT NULL
    OR g.id_guardia IS NOT NULL
    OR p.id_personal IS NOT NULL
    OR pr.id_proveedor IS NOT NULL
ORDER BY e.fecha DESC;
";

$result = $conn->query($sql);
$registros = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($registros);
?>
