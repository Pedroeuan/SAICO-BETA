<?php

namespace App\Http\Controllers\PDFReportes;

use App\Http\Controllers\Controller;
use App\Models\PDFReportes\PDFReportes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
//use Barryvdh\Snappy\Facades\SnappyPdf;
use \Mpdf\Mpdf;

class PDFReportesController extends Controller
{
    public function FOR_PINS_03_01()
    {
    $mpdf = new Mpdf();

    $Logo = public_path('images/Logo_AICO_R.jpg');
    // Definir el CSS como una cadena
    $css = "
    .content {
        margin-top: 100px;  /* El contenido se desplazará hacia abajo para no solaparse con el encabezado */
        margin-bottom: 50px;  /* Evita que el pie de página se solape */
    }
    ";

    // Definir el HTML de la tabla en una cadena
    $html = '
        <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 60%;">FOR-PINS-03/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2"> INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS </th>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th>{PAGENO} de {nbpg}</th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 5px;"></div>
        
                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="4">DATOS GENERALES</th>
                    </tr>
                </table>            
                <table class="datosgenerales">
                    <tbody>
                        <tr>
                            <th style="width: 11%;">FECHA:</th>
                            <td class="lineaInferior"></td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior"></td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior"></td>
                            <th>ISOMETRICO/PLANO:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <th>PIEZA:</th>
                            <td class="lineaInferior"></td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior"></td>
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                    </tbody>
                </table>

            
                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="7">DATOS DE LA INSPECCIÓN</th>
                    </tr>
                </table>

                <div style="margin-bottom: 2px;"></div>

                <table class="datosinspeccion">
                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 60px;">ITEM</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;">LOTE</th>
                                <th style="width: 100px;">TIPO</th>
                                <th style="width: 100px;">COLOR</th>
                                <th style="width: 100px;">APLICACIÓN</th>
                            </tr>
                            <tr>
                                <th class="celdaGris">PARTICULAS:</th>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <th class="celdaGris">CONTRASTANTE:</th>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                                <td>12</td>
                            </tr>
                        </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th style="width: 20%;">EQUIPO</th>
                            <th style="width: 20%;">MARCA</th>
                            <th style="width: 20%;">MODELO</th>
                            <th style="width: 20%;">N/S</th>
                            <th style="width: 20%;">CORRIENTE</th>
                            <th style="width: 20%;">DISTANCIA ENTRE PATAS</th>
                            
                        </tr>
                        <tr>
                            <td>YUGO ELECTROMÁGNETICO:</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 10%;">TIPO DE LUZ:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 10%;">INTENCIDAD:</th>
                            <td class="lineaInferior">2</td> <th style="text-align: left; width: 5%;"> Lx </th>
                            <th style="width: 10%;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior">3</td>|
                            <th style="width: 10%;">TEMPERATURA DE PRUEBA:</th>
                            <td class="lineaInferior">4</td> <th style="text-align: left; width: 5%;"> °C </th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                        <tr>
                            <th colspan="9">RESULTADOS</th>
                        </tr>
                </table>

    ';

    // Establecer el CSS y luego el HTML en el PDF
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

    // Output el PDF al navegador o guardar en un archivo
    return $mpdf->Output('Reporte_FOR_PINS_03_01.PDF', 'I'); //Esto envía el PDF generado directamente al navegador en modo de vista previa ('I').
    }

    /*public function FOR_PINS_03_01()
    {
        $user = Auth::user();
        $nombre = $user->name;

        $Logo = public_path('images/Logo_AICO_R.jpg');

        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];

        // Cargar la vista y generar el HTML
        $html = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->render();

        // Crear una instancia de mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            //'format' => [190, 250], // Ancho x Alto en mm (ajústalo según tus necesidades)
            'default_font' => 'Arial',  // Configura la fuente predeterminada (puedes usar Arial, Times, etc.)
            //'default_font_size' => 12,   // Establece el tamaño de fuente predeterminado en puntos
            'format' => 'Letter',
            'orientation' => 'P',   // 'orientation' => 'L': Usa 'L' para formato horizontal (landscape) o 'P' para vertical (portrait).
            'margin_top' => 40,
            'margin_bottom' => 30,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);
        
        // Definir el HTML del encabezado
        $mpdf->SetHTMLHeader('
            <div style="text-align: center; border-bottom: 1px solid #000; font-weight: bold; padding-bottom: 10px;">
                <img src="' . $Logo . '" alt="Logo" style="width: 50px; vertical-align: middle;">
                <span>Reporte FOR-PINS-03/01 - ' . $data['title'] . '</span>
            </div>
        ');

        // Definir el HTML del pie de página
        $mpdf->SetHTMLFooter('
            <div style="text-align: center; border-top: 1px solid #000; font-size: 10px; padding-top: 10px;">
                Página {PAGENO} de {nbpg}
            </div>
        ');

        // Escribir el contenido HTML en el PDF
        $mpdf->WriteHTML($html);

        // Enviar el PDF al navegador
        return $mpdf->Output('Reporte_FOR_PINS_03_01.PDF', 'I'); //Esto envía el PDF generado directamente al navegador en modo de vista previa ('I').
    }*/


    public function FOR_PINS_03_01__()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
    
        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper('letter', 'landscape');
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('defaultPaperMargins', [20, 10, 20, 10]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 9;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 588; // Ajusta esta posición X según sea necesario
            $y = 72;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_PINS_03_01.PDF');
    }

    public function FOR_PINS_04_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
    
        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        $data = [
            'title' => 'Reporte_FOR-PINS-04/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper('letter', 'landscape');
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_04_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('defaultPaperMargins', [10, 15, 10, 15]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 598; // Ajusta esta posición X según sea necesario
            $y = 72;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_PINS_04_01.PDF');
    }

    public function FOR_PINS_05_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
    
        $Logo = public_path('images/Logo_AICO_R.jpg');

        $FOR_PINS_05_01 = public_path('images/FOR-PINS-05-01.png');
    
        $data = [
            'title' => 'Reporte_FOR-PINS-05/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            'FOR_PINS_05_01' => $FOR_PINS_05_01,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper('letter', 'landscape');
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_05_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('defaultPaperMargins', [10, 15, 10, 15]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 598; // Ajusta esta posición X según sea necesario
            $y = 72;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_PINS_05_01.PDF');
    }

    public function FOR_PINS_11_02()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
    
        $Logo = public_path('images/Logo_AICO_R.jpg');

        $data = [
            'title' => 'Reporte_FOR-PINS-11/02.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        //$pdf = PDF::loadView('ReportesPDF.UltrasonidoFOR_PINS_12PDF', $data)->setPaper('a4', 'landscape');
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_11_02_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('defaultPaperMargins', [10, 15, 10, 15]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 630; // Ajusta esta posición X según sea necesario
            $y = 72;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_PINS_11_02.PDF');
    }
}
