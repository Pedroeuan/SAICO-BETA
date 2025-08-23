<?php
require 'conexion.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//Buscar registro
$id = isset($GET['id']) ? (int)$_GET['id']:0;
$res = $conexion->query("SELECT*FROM registros WHERE id={$id} LIMIT 1");
if (!$res || $res->num_rows===0) {die("Registro no encontrado");}
$R = $res->fetch_assoc();

//Logo embebido en base64 (coloca tu logo en img/logo.png)
$logoPath =__DIR__ . '/img/logo.pgn';
$logoB64 =  file_exists($logoPath) ? 'data:imagen/png;base64,' .base64_encode(file_get_contents($logoPath)): '';

//plantilla (tabla y notas como en la imagen)
$html ='
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 24px; }
    body { font-family: Arial, sans-serif; font-size: 11px; }
    table { width:100%; borde-collapse: collapse; }
    td, th { border:1px solid #000; padding:5px; vertical-aling:top;}
    .no-b { borde.none !important; }
    .enc td { font: size 0.11px;}
    .logo{ text-align:center; }
    .titulo {font-weight.bold, text-transform:uppercase; text-align.center; margin:6px 0; }
    .sub {background:#f0f0f0; font-weight:bold; text-align:center; }
    .nota {font-style:italic; border-top:1px solid #000; padding:4px; }
    .firm td { border:none; text-align:center; padding-top:30px; }
</style>
</head>
<body>

<!---Encabezado -->
<table class="enc">
    <tr>
        <td colspan="2"><strong>PROCEDIMIENTO</strong><br>EXAMINACIÓN DE AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR A PERSONAL</td>
        <td><strong>Codigo:</strong> PRO-PINS-15</td>
        <td class="logo" rowspan="2">'.($logoB64?'<img src="'.$logoB64.'" style="height:70px">':'').'</td>
    </tr>
    <tr>
        <td><strong>Versión:</strong> 0</td>
        <td><strong>Página:</strong> 1 de 1</td>
    </tr>
</table>

<p><strong>Anexo 1. FOR-PINS-18</strong> Registro de Examinación de Agudez visual y Diferenciación del contraste de color.</p>

<table>
    <tr><td class="no-b" colspan="4" style="padding: 0">
        <div class="titulo">REGISTRO DE EXAMINACIÓN AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR</div>
        </td></tr>
    <tr><td colspan="4"><strong>Nombre:</strong> '.htmlspecialchars($R['nombre']).'</td></tr>
</table>

<table>
    <tr><td colspan="4" class="sub">AGUDEZA VISUAL CERCANA</td></tr>
    <tr><td colspan="4" class="nota">Nota: Se deberá establecer N/A en los campos que queden en blanco.</td></tr>
    <tr>
        <td colspan="2"><strong>Correccion:</strong> '.htmlspecialchars($R['correccion']).'</td>
        <td><strong>Ojo izquierdo:</strong> '.htmlspecialchars($R['ojo_izq']).'</td>
        <td><strong>Ojo derecho</strong> '.htmlspecialchars($R['ojo_der']).'</td>
    </tr>
</table>

<table>
    <tr><td colspan="4" class="sub">CONTRASTE DE COLOR / VISIÓN DE COLORES</td></tr>
    <tr><td colspan="4" class="nota">Cartas de Ishihara y Placas Pseudoisocromáticas. Nota: Se deberá establecer N/A (No Aplica) en la prueba no realizada.</td></tr>
    <tr>
        <td><strong>Ishihara Ojo izq:</strong> '.htmlspecialchars($R['ishihara_izq']).'</td>
        <td><strong>Ishihara Ojo der</strong> '.htmlspecialchars($R['ishihara_der']).'</td>
        <td><strong>Diferenciación Rojo_Verde:</strong> '.htmlspecialchars($R['rv']).'</td>
        <td><strong>Diferenciación Azul_Amarillo:</strong> '.htmlspecialchars($r['aa']).'</td>
    </tr>
</table>

<table>
    <tr><td colspan="2" class="sub">DATOS DEL EXAMINADOR</td></tr>
    <tr>
        <td><strong>Nombre:</strong> '.htmlspecialchars($R['exam_nombre']).'</td>
        <td><strong>Dirección:</strong> '.htmlspecialchars($R['exam_direccion']).'</td>
    </tr>
    <tr>
        <td><strong>Teléfono:</strong> '.htmlspecialchars($R['exam_tel']).'</td>
        <td><strong>Registro_Licencia:</strong> '.htmlspecialchars($R['exam-registro']).'</td>
    </tr>
</table>

<table class="firm" style="margin-top: 6px">
    <tr>
        <td>______________________<br>Firma</td>
        <td>______________________<br>Fecha: '.date("Y-m-d", strtotime($R['fecha'])).'</td>
    </tr>
</table>

</body>
</html>
';

// Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true); // por si usas rutas http
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream('examen_visual_'.$R['id'].'.pdf', ['Attachment'=>true]);
