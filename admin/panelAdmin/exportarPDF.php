<?php
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configurar DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
date_default_timezone_set("America/Tijuana");
// Conexión BD
include "conexion.php";

// Obtener fecha actual
$fechaHoy = date("d/m/Y");

// CONSULTA — SOLO MOVIMIENTOS DE HOY
$sql = "
SELECT 
    e.no_registro,
    e.fecha,
    e.accion,
    v.rfid,
    COALESCE(a.nombre, d.nombre, ad.nombre, g.nombre, p.nombre, pr.nombre) AS nombre,
    COALESCE(a.apellido, d.apellido, ad.apellido, g.apellido, p.apellido, pr.apellido) AS apellido,
    CASE
        WHEN a.no_control IS NOT NULL THEN 'Alumno'
        WHEN d.id_docente IS NOT NULL THEN 'Docente'
        WHEN ad.id_administrativo IS NOT NULL THEN 'Administrativo'
        WHEN g.id_guardia IS NOT NULL THEN 'Guardia'
        WHEN p.id_personal IS NOT NULL THEN 'Personal'
        WHEN pr.id_proveedor IS NOT NULL THEN 'Proveedor'
    END AS tipo_usuario
FROM estacionamiento e
INNER JOIN vehiculo v ON e.rfid = v.rfid
LEFT JOIN alumno a ON v.no_control = a.no_control
LEFT JOIN docente d ON v.id_docente = d.id_docente
LEFT JOIN administrativo ad ON v.id_administrativo = ad.id_administrativo
LEFT JOIN guardia g ON v.id_guardia = g.id_guardia
LEFT JOIN personal p ON v.id_personal = p.id_personal
LEFT JOIN proveedor pr ON v.id_proveedor = pr.id_proveedor
WHERE DATE(e.fecha) = CURDATE()
ORDER BY e.fecha DESC
";

$res = $conn->query($sql);

// ---------- CREAR PDF ----------
$html = "
<h2 style='text-align:center;'>Movimientos del Día ($fechaHoy)</h2>
<table border='1' width='100%' style='border-collapse: collapse; font-size:12px;'>
<tr>
<th>No. Registro</th>
<th>Fecha</th>
<th>Acción</th>
<th>RFID</th>
<th>Usuario</th>
<th>Tipo</th>
</tr>";

while ($row = $res->fetch_assoc()) {
    $accion = ($row['accion'] == "0") ? "Entrada" : "Salida";

    $html .= "<tr>
        <td>{$row['no_registro']}</td>
        <td>{$row['fecha']}</td>
        <td>{$accion}</td>
        <td>{$row['rfid']}</td>
        <td>{$row['nombre']} {$row['apellido']}</td>
        <td>{$row['tipo_usuario']}</td>
    </tr>";
}

$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

header("Content-Type: application/pdf");
echo $dompdf->output();
?>
