<?php

namespace App\Http\Controllers\PDFReportes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\PDFReportes\PDFReportes;

use App\Models\Reporte\reporte;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

class PDFReportesController extends Controller
{
    public function Reportecreate()
    {
        return view('Reportes.ReporteIndex');
    }
    /*DOMPDF*/
    public function FOR_INS_02_02()
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
            'title' => 'Reporte_FOR-INS-02/02.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_02_02_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_02_02_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_02_02.PDF');
    }

    public function FOR_INS_03_01()
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
            'title' => 'Reporte_FOR-INS-03/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_03_01_PDF', $data)->setPaper('letter', 'portrait');//Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_03_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_03_01.PDF');
    }

    public function FOR_INS_04_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
    
        $Logo = public_path('images/Logo_AICO_R.jpg');

        $FOR_INS_04_01 = public_path('images/FOR-INS-04-01.png');
    
        $data = [
            'title' => 'Reporte_FOR-INS-04/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            'FOR_INS_04_01' => $FOR_INS_04_01,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_04_01_PDF', $data)->setPaper('letter', 'portrait');//Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_04_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_04_01.PDF');
    }

    public function FOR_INS_04_02()
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
            'title' => 'Reporte_FOR-INS-04/02.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_04_02_PDF', $data)->setPaper('letter', 'portrait');//Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_04_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_04_02.PDF');
    }

    public function FOR_INS_05_01()
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
            'title' => 'Reporte_FOR-INS-05/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_05_01_PDF', $data)->setPaper('letter', 'portrait');//Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_05_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_05_01.PDF');
    }

    public function FOR_INS_06_01()
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
            'title' => 'Reporte_FOR-INS-06/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_06_01_PDF', $data)->setPaper('letter', 'landscape');//Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_06_01_PDF', $data)->setPaper([0, 0, 760, 800]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 634; // Ajusta esta posición X según sea necesario
            $y = 46;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_06_01.PDF');
    }


    public function FOR_INS_07_01()
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
            'title' => 'Reporte_FOR-INS-07/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_07_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_07_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_07_01.PDF');
    }

    public function FOR_INS_08_01()
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
            'title' => 'Reporte_FOR-INS-08/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_08_01_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_08_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 633; // Ajusta esta posición X según sea necesario
            $y = 47;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_08_01.PDF');
    }

    public function FOR_INS_09_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        /*$Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();*/
        

        $Logo = public_path('images/Logo_AICO_R.jpg');
        $FOR_INS_09_01 = public_path('images/FOR-INS-09-01.png');
        $data = [
            'title' => 'Reporte_FOR-INS-09/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            'FOR_INS_09_01' => $FOR_INS_09_01,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_09_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_09_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 633; // Ajusta esta posición X según sea necesario
            $y = 47;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_09_01.PDF');
    }

    public function FOR_INS_10_01()
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
            'title' => 'Reporte_FOR-INS-10/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_10_01_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_11_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 634; // Ajusta esta posición X según sea necesario
            $y = 46;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_10_01.PDF');
    }

    {
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes',$id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();
        /*$user = Auth::user();
        $nombre = $user->name;*/

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true);
        $TotalJuntas = count($Grupo_Juntas_Detalles_Re);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Firmas_Reportes = json_decode($Firmas_Reportes->Firmas, true);
        $numFirmas = $Firmas_Reportes['numFirmas'];

        $Logo = public_path('images/Logo_AICO_R.jpg');

        $data = [
            'title' => 'Reporte_FOR-INS-10/02.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            'TotalJuntas' => $TotalJuntas,
            //Fotos_Reportes
            'Fotos_Reportes' => $Fotos_Reportes,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_10_02_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_11_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        // Configurar márgenes personalizados (en milímetros)
        $options = $dompdf->getOptions();
        $options->set('isHtml5ParserEnabled', true); // Opcional, mejora compatibilidad
        $options->set('isPhpEnabled', true);
        //$options->set('defaultPaperMargins', [5, 5, 5, 5]);  // [arriba, derecha, abajo, izquierda]
        $dompdf->setOptions($options);
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 610; // Ajusta esta posición X según sea necesario
            $y = 94;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_10_02.PDF');
    }

    /*public function FOR_INS_10_02($id)
    {
        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true);
        $TotalJuntas = count($Grupo_Juntas_Detalles_Re);
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Firmas_Reportes = json_decode($Firmas_Reportes->Firmas, true);
        $numFirmas = $Firmas_Reportes['numFirmas'];

        $Logo = public_path('images/Logo_AICO_R.jpg');
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);
            $Fotos = [];
        
            for ($i = 0; $i < min(4, count($fotos)); $i++) { // Solo obtener hasta 4 imágenes
                $Fotos[] = [
                    'path' => public_path('storage/' . str_replace('public/', '', $fotos[$i]['path'])),
                    'comment' => $fotos[$i]['comment'] ?? ''
                ];
            }
        }

        $data = [
            'title' => 'Reporte_FOR-INS-10/02.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            //Total de Juntas
            'TotalJuntas' => $TotalJuntas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];

        // Generar el PDF principal en orientación horizontal
        $pdf1 = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_10_02_PDF', $data)->setPaper('letter', 'landscape');

        // Generar el PDF adicional en orientación vertical
        $pdf2 = PDF::loadView('Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_INS_10_02_PDF', $data)->setPaper('letter', 'portrait');

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();
        $pdf2Content = $pdf2->output();

        $combinedPdf = new Fpdi();
        $pageCount1 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        $totalPageCount = 0;

        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('L');
            $combinedPdf->useTemplate($tplId, 0, 0, 297, 210); // Ajusta las dimensiones a las del papel Letter
            $totalPageCount++;
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(152, -180);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        $pageCount2 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297); // Ajusta las dimensiones a las del papel Letter
            $totalPageCount++;

            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(136, -267);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        return response($combinedPdf->Output('Reporte_FOR_INS_10_02.PDF', 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }*/

    public function FOR_INS_12_01()
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
            'title' => 'Reporte_FOR-INS-12/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_12_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_12_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 634; // Ajusta esta posición X según sea necesario
            $y = 46;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_12_01.PDF');
    }

    public function FOR_INS_13_01()
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
            'title' => 'Reporte_FOR-INS-13/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_13_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_13_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_13_01.PDF');
    }

    public function FOR_INS_15_01()
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
            'title' => 'Reporte_FOR-INS-15/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_15_01_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_15_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 633; // Ajusta esta posición X según sea necesario
            $y = 47;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_15_01.PDF');
    }

    public function FOR_INS_15_02()
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
            'title' => 'Reporte_FOR-INS-15/02.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_15_02_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_03_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 633; // Ajusta esta posición X según sea necesario
            $y = 47;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_15_02.PDF');
    }

    public function FOR_INS_15_03()
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
            'title' => 'Reporte_FOR-INS-15/02.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_15_03_PDF', $data)->setPaper('letter', 'landscape'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_03_01_PDF', $data)->setPaper([0, 0, 760, 780]); // Ancho x Alto en milímetros

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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 633; // Ajusta esta posición X según sea necesario
            $y = 47;  // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('Reporte_FOR_INS_15_03.PDF');
    }

    public function FOR_INS_16_01()
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
            'title' => 'Reporte_FOR-INS-16/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_16_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_16_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_16_01.PDF');
    }

    public function FOR_INS_17_01()
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
            'title' => 'Reporte_FOR-INS-17/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_17_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_11_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_17_01.PDF');
    }

    public function FOR_INS_18_01()
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
            'title' => 'Reporte_FOR-INS-18/01.PDF',
            /*'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,*/
            'nombre' => $nombre,
            'Logo' => $Logo,
            //'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_INS_18_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
        //$pdf = PDF::loadView('ReportesPDF.Reporte_FOR_INS_11_01_PDF', $data)->setPaper([0, 0, 800, 760]); // Ancho x Alto en milímetros
    
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 483; // Ajusta esta posición X según sea necesario
            $y = 37;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('Reporte_FOR_INS_18_01.PDF');
    }

    public function FORMATO_01()
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
        $pdf = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->setPaper('letter', 'portrait'); //Define la orientación del papel. Puede ser 'portrait' (vertical) o 'landscape' (horizontal).
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
            $font = $fontMetrics->getFont('arial', 'normal');
            $size = 8;

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
    
}