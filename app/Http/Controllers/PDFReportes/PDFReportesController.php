<?php

namespace App\Http\Controllers\PDFReportes;

use App\Http\Controllers\Controller;
use App\Models\PDFReportes\PDFReportes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//use FPDF;
//use Spipu\Html2Pdf\Html2Pdf;
//use Mpdf\Mpdf;
//use Spatie\Browsershot\Browsershot;
//use Smalot\PdfParser\Parser;
//use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\DomPDF\Facade\Pdf;
//use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;
use Dompdf\Options;

//use Spatie\LaravelPdf\Facades\Pdf;

class PDFReportesController extends Controller
{
    /*DOMPDF*/
    public function FOR_PINS_03_01()
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
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario

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
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_04_01_PDF', $data)->setPaper('letter', 'portrait');
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_04_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $size = 9;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
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
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_05_01_PDF', $data)->setPaper('letter', 'portrait');
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_PINS_05_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $size = 9;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_PINS_05_01.PDF');
    }

    /*public function FOR_PINS_03_01132()
    {
        // Crear instancia de Dompdf
        $dompdf = new Dompdf();
        $dompdf->set_option('isRemoteEnabled', true);

        // Ruta absoluta de la imagen
        $image_path = public_path('images/Logo_AICO_R2.png');
        $image_data = base64_encode(file_get_contents($image_path));
        $image_src = 'data:image/png;base64,' . $image_data;

        // Verificar que la imagen existe
        if (!file_exists($image_path)) {
            die('La imagen no existe en la ruta especificada.');
        }
        
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-03/01</title>
            <style>
                @page {
                    margin: 90px 30px; /* Margen superior para header y margen inferior para footer */
                /*}
                header {
                    position: fixed;
                    top: -80px; /* Ajustar para que quede dentro del margen superior */
                    /*left: 0;
                    right: 0;
                    height: 100px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    /*border-bottom: 1px solid #ffffff;
                }
                footer {
                    position: fixed;
                    bottom: 100px; /* Ajustar para que quede dentro del margen inferior */
                    /*left: 0;
                    right: 0;
                    height: 100px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    /*border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 227px; /* Ajusta según el tamaño de tu encabezado */
                /*}
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    /*margin-bottom: 200px; /* Evita superposición con el footer */
                /*}

                .table-container {
                    margin: 100px 0;
                }

                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 8px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                /*.lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                }
                    
                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    /*border-spacing: 0px;        /* Espacio entre celdas */
                    /*width: 100%;
                    text-align: center;
                    font-size: 8px;
                }

                .simbologia td, .simbologia th {
                    border: .6px solid black; 
                }
                .celdaAmarillo{
                    background-color: #FFF2CC;
                }

                .tablaheader {
                    border-collapse: collapse; 
                    border-spacing: 0px;        /* Espacio entre celdas */
                    /*width: 100%;
                    text-align: center;
                    font-size: 10px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                /*.tablaheader th {
                    /*width: 70%;*/
                    /*border: 1px solid black; 
                }

            .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 8px;
            background-color: #305496;
            color: #ffffff;
            border-collapse: collapse;
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            /*border-spacing: 0px;        /* Espacio entre celdas */
            /*width: 100%;
            text-align: center;
            font-size: 8px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosresultados{
            border-collapse: separate;  /*separate No colapsar bordes */
            /*border-spacing: 0px;        /* Espacio entre celdas */
            /*width: 100%;
            text-align: center;
            font-size: 12px;
        }

        .datosresultados td, .datosresultados th {
            border: .6px solid black; 
        }
        .celdaGris{
            background-color: #DBDBDB;
        }        
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-PINS-03/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="' . $image_src . '" alt="Logo" style="width: 50%; height: auto;"></th>
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
                            <th></th>
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
                            <th style="width: 12%;">FECHA:</th>
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
            </header>

        <footer>
                <table class="simbologia">
                    <thead>
                        <tr>
                            <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                        </tr>

                        <tr>
                            <td style="width: 20px;"><strong>NPIR</strong></td>
                            <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                            <td style="width: 20px;"><strong>DM</strong></td>
                            <td style="width: 150px;">DAÑO MECÁNICO</td>
                            <td style="width: 20px;"><strong>PT</strong></td>
                            <td style="width: 180px;">POROSIDAD TUBULAR</td>
                        </tr>

                        <tr>
                            <td><strong>G</strong></td>
                            <td>GRIETA</td>
                            <td><strong>S</strong></td>
                            <td>SOCAVADO</td>
                            <td><strong>C</strong></td>
                            <td>CRATER</td>
                        </tr>

                        <tr>
                            <td><strong>ZG</strong></td>
                            <td>ZONA DE GRIETAS</td>
                            <td><strong>P</strong></td>
                            <td>POROSIDAD</td>
                            <td><strong>IL</strong></td>
                            <td>INDICACIÓN LINEAL</td>
                        </tr>

                        <tr>
                            <td><strong>FF</strong></td>
                            <td>FALTA DE FUSIÓN</td>
                            <td><strong>ZP</strong></td>
                            <td>ZONA DE POROS</td>
                            <td><strong>IR</strong></td>
                            <td>INDICACIÓN REDONDEADA</td>
                        </tr>
                    </thead>
                </table>
                <br>

                <table>                               
                    <tr>                                     
                        <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                        <td class="lineaInferior" style="width: 580px;"></td>                            
                    </tr>                      
                </table>

                <br>
                                            
                <table class="datosgenerales">
                    <thead>
                        <tr>
                            <td style="width: 30px;"></td>
                            <th>Realizó</th>
                            <td style="width: 30px;"></td>
                            <th>Vo.Bo.</th>
                            <td style="width: 30px;"></td>
                            <th>Vo.Bo.</th>
                        </tr>

                        <tr>
                            <th></th>
                            <td style="width: 200px; height:40px" class="lineaInferior"></td>
                            <td></td>
                            <td style="width: 200px; height:40px" class="lineaInferior"></td>
                            <td></td>
                            <td style="width: 200px; height:40px" class="lineaInferior"></td>
                        </tr>

                        <tr>
                            <th></th>
                            <td><strong>NOMBRE DEL TÉCNICO</strong></td>
                            <td></td>
                            <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                            <td></td>
                            <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                        </tr>
                                                            
                        <tr>
                            <th></th>
                            <td><strong>Técnico N-II SNT-TC-1A</strong></td>
                            <td></td>
                            <td><strong>PUESTO DEL ENCARGADO</strong></td>
                            <td></td>
                            <td><strong>PUESTO DEL ENCARGADO</strong></td>
                        </tr>

                        <tr>
                            <th></th>
                            <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                            <td></td>
                            <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                            <td></td>
                            <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                        </tr>
                    </thead>                            
                </table>
        </footer>

        <div class="content">
            <div style="margin-bottom: 0px;"></div>
                <table class="datosresultados">
                        <thead>
                            <tr class="celdaGris">
                                <th rowspan= "2" style="width: 20%;">No.</th>
                                <th rowspan= "2">No. De Junta / Componente</th>
                                <th rowspan= "2">No. Indicación</th>
                                <th rowspan= "2">Tipo de Indicación</th>
                                <th colspan="3">Dim. De Indicación</th>

                                <th style="width: 50%;">Localización</th>
                                <th rowspan= "2" style="width: 100%;">Evaluación</th>
                                <th rowspan= "2" style="width: 150%;">Longitud Inspeccionada</th>
                            </tr>
                            <tr class="celdaGris">
                                <th style="width: 50%;">Largo</th>
                                <th style="width: 50%;">Ancho</th>
                                <th style="width: 50%;">Ø</th>
                                <th style="width: 50%;">H.T.</th>
                            </tr>
                        </thead>
                        <tbody>';
                            // Ciclo for para agregar las filas dinámicamente
                            for ($i=0; $i<50; $i=$i+1) {
                                $html .= '
                                <tr>
                                    <td>' . $i. '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                    <td>' . $i . '</td>
                                </tr>';
                            }

                            $html .= '
                            <tr class="sinBordetd">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td colspan="4"><strong>Longitud total inspeccionada:</strong></td>
                                <th>0 m</th>
                            </tr>
                        </tbody>
                </table>
            </div>
        </body>
    </html>
        ';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Establecer las opciones para mostrar el número de página correctamente
        $canvas = $dompdf->getCanvas();
        $canvas->page_text(470, 36, "{PAGE_NUM} de {PAGE_COUNT}", null, 8, array(0, 0, 0)); // Ajuste la posición y estilo según necesite

        $dompdf->stream("FORMATO FOR-PINS-03/01", ["Attachment" => false]);

    }*/

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
