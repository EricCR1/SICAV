<?php
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

include "conexion.php";

// 🔹 Fecha actual para el título
$fechaHoy = date("d/m/Y");

// 🔹 Consultar movimientos de visitantes del día actual
$sql = "
SELECT 
    vte.numero AS numero_asignado,
    vte.nombre AS nombre,
    vte.motivo AS motivo,
    e.fecha AS fecha_hora,
    e.accion AS accion
FROM estacionamiento e
LEFT JOIN visitante vte ON e.id_visitante = vte.id_visitante
WHERE e.id_visitante IS NOT NULL
  AND DATE(e.fecha) = CURDATE()
ORDER BY e.fecha DESC
";

$res = $conn->query($sql);

// 🔹 Construir HTML del PDF
$html = "
<h2 style='text-align:center;'>Reporte de Visitantes – ($fechaHoy)</h2>
<table border='1' width='100%' style='border-collapse: collapse; font-size:12px;'>
<tr>
<th>Número</th>
<th>Nombre</th>
<th>Motivo</th>
<th>Fecha</th>
<th>Acción</th>
</tr>
";

while ($row = $res->fetch_assoc()) {
    $accion = ($row['accion'] == "0") ? "Entrada" : "Salida";

    $html .= "
    <tr>
        <td>{$row['numero_asignado']}</td>
        <td>{$row['nombre']}</td>
        <td>{$row['motivo']}</td>
        <td>{$row['fecha_hora']}</td>
        <td>{$accion}</td>
    </tr>";
}

$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

header("Content-Type: application/pdf");
echo $dompdf->output();
?>
