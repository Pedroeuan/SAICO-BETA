<?php

namespace App\Http\Controllers\PDFReportes;

use App\Http\Controllers\Controller;
use App\Models\PDFReportes\PDFReportes;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Barryvdh\Snappy\Facades\SnappyPdf;

class PDFReportesController extends Controller
{

    public function FOR_PINS_03_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
    
        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];
    
        // Cargar la vista con los datos y configuraciones de papel
        $pdf = SnappyPdf::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)
            ->setOption('page-width', '760mm')
            ->setOption('page-height', '780mm');
            /*->setPaper([0, 0, 760, 780]) // Ancho x Alto en milímetros
            ->setOption('margin-top', '10mm')
            ->setOption('margin-right', '10mm')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-left', '10mm')
            ->setOption('orientation', 'Landscape')
            ->setOption('footer-font-size', '9')
            ->setOption('footer-right', '[page] de [toPage]'); // Para el pie de página con la numeración*/
    
        return $pdf->stream('Reporte_FOR_PINS_03_01.PDF');
    }

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
