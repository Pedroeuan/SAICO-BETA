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

class PDFReportesController extends Controller
{
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

                    // Header
        $xHeader = 20; // Ajusta esta posición X del header
        $yHeader = 40; // Ajusta esta posición Y del header
        $headerText = "Este es el Header de cada página";
        $canvas->text($xHeader, $yHeader, $headerText, $font, $size);

        // Footer
        $xFooter = 588; // Ajusta esta posición X del footer
        $yFooter = 750; // Ajusta esta posición Y del footer
        $footerText = "$pageNumber de $pageCount";
        $canvas->text($xFooter, $yFooter, $footerText, $font, $size);
    
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

    /*public function FOR_PINS_03_01()
    {
        /*$user = Auth::user();
        $nombre = $user->name;
        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        // Generar el HTML para el reporte
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];*/
    // Crear la instancia de FPDF
    /*$pdf = new FPDF();
    $pdf->AddPage();

    // Obtener contenido de la vista
    //$content = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', compact('data'))->render();

    // Establecer la fuente y agregar contenido
    $pdf->SetFont('Arial', '', 12);
            // Títulos de las columnas
            $header = ['ID', 'Nombre', 'Apellido', 'Edad'];

            // Datos de ejemplo (esto puede venir de la base de datos)
            $data = [
                [1, 'Juan', 'Pérez', 28],
                [2, 'Ana', 'Gómez', 22],
                [3, 'Carlos', 'Ruiz', 30],
            ];

    //$pdf->MultiCell(0, 10, $content);
    // Crear la tabla (encabezado + datos)
    $this->CreateTable($pdf, $header, $data);

    // Generar y mostrar el PDF
    $pdf->Output();        
    } 

     // Función para crear la tabla
    /*public function CreateTable($pdf, $header, $data)
    {
        // Ancho de las columnas (en mm)
        $widths = [20, 60, 60, 20]; // Ajusta según tus necesidades

        // Títulos de las columnas
        for ($i = 0; $i < count($header); $i++) {
            $pdf->Cell($widths[$i], 10, $header[$i], 1, 0, 'C');
        }
        $pdf->Ln(); // Salto de línea después de los encabezados

        // Datos
        $pdf->SetFont('Arial', '', 12); // Cambiar fuente para los datos

        foreach ($data as $row) {
            for ($i = 0; $i < count($row); $i++) {
                $pdf->Cell($widths[$i], 10, $row[$i], 1, 0, 'C');
            }
            $pdf->Ln(); // Salto de línea después de cada fila
        }
    }
    /*public function FOR_PINS_03_01()
    {
        $datos = [
            ['nombre' => 'Elemento 1', 'descripcion' => 'Descripción del elemento 1'],
            ['nombre' => 'Elemento 2', 'descripcion' => 'Descripción del elemento 2'],
        ];
        try {
            $content = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', compact('datos'))->render();
            // Crear una instancia de Html2Pdf
            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML($content); // Añadir contenido HTML
            $html2pdf->output('ReportesPDF.Reporte_FOR_PINS_03_01_PDF'); // Generar y mostrar el PDF
        } catch (\Exception $e) {
            echo 'Error al generar el PDF: ' . $e->getMessage();
        }        
    }*/

    /* public function FOR_PINS_03_01()
{
    $user = Auth::user();
    $nombre = $user->name;
    $Logo = public_path('images/Logo_AICO_R.jpg');

    // Generar el HTML para el reporte
    $data = [
        'title' => 'Reporte_FOR-PINS-03/01.PDF',
        'nombre' => $nombre,
        'Logo' => $Logo,
    ];

    // Crear instancia de mPDF
    $mpdf = new \Mpdf\Mpdf();

    // Cargar el encabezado y el pie de página desde archivos HTML
    $header = file_get_contents(resource_path('views/ReportesPDF/header.blade.php'));
    $footer = file_get_contents(resource_path('views/ReportesPDF/footer.blade.php'));

    // Asignar el encabezado y pie de página
    $mpdf->SetHTMLHeader($header);
    $mpdf->SetHTMLFooter($footer);

    // Generar el contenido del reporte usando Blade
    $html = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->render();

    // Escribir el contenido del reporte
    $mpdf->WriteHTML($html);

    // Generar el PDF y mostrarlo en el navegador
    $mpdf->Output('Reporte_FOR_PINS_03_01.PDF', 'I');
}*/

    /*public function FOR_PINS_03_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        $Logo = public_path('images/Logo_AICO_R.jpg');
        
        // Generar el HTML para el reporte
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];

        $mpdf = new Mpdf();
        $html = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Reporte_FOR_PINS_03_01.PDF', 'I');
    }*/

    /*public function FOR_PINS_03_01()
    {
        $user = Auth::user();
        $nombre = $user->name;
        $Logo = public_path('images/Logo_AICO_R.jpg');
        
        // Generar el HTML para el reporte
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];
    
        try {
            // Generar el HTML para el reporte
            $html = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->render();
    
            // Crear el PDF usando Browsershot y guardarlo en una ubicación temporal
            $pdfPath = storage_path('app/public/Reporte_FOR_PINS_03_01.pdf');
            Browsershot::html($html)
                ->windowSize(1280, 720) // Ajusta el tamaño de la ventana (opcional)
                //->setOption('no-sandbox') // A veces se necesita por cuestiones de seguridad en servidores
                ->savePdf($pdfPath);
    
            // Obtener el número de páginas
            $numberOfPages = $this->getPdfPageCount($pdfPath);
    
            // Incluir el número de páginas en el arreglo de datos
            $data['totalPages'] = $numberOfPages;
    
            // Volver a generar el HTML con los datos actualizados
            $htmlWithPages = view('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)->render();
    
            // Crear el PDF con el número de páginas
            Browsershot::html($htmlWithPages)
                ->windowSize(1280, 720) // Ajusta el tamaño de la ventana (opcional)
                ->savePdf($pdfPath);
    
            // Devolver el PDF generado
            return response()->download($pdfPath);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function getPdfPageCount($pdfPath)
    {
        // Crear una instancia del parser de PDF
        $parser = new Parser();
        
        // Parsear el archivo PDF
        $pdf = $parser->parseFile($pdfPath);
        
        // Obtener el número de páginas
        $pages = $pdf->getPages();
        
        // Devolver el número de páginas
        return count($pages);
    }*/
    

    public function FOR_PINS_03_01_no()
    {
        $user = Auth::user();
        $nombre = $user->name;
        //$Logo = public_path('images/Logo_AICO_R.jpg');
        $totalPages = ceil(strlen($contenido) / $caracteresPorPagina);
        $data = [
            'title' => 'Reporte_FOR-PINS-03/01.PDF',
            'nombre' => $nombre,
            'totalPages' => $totalPages,
            //'Logo' => $Logo,
        ];
    
        try {
            $pdf = SnappyPdf::loadView('ReportesPDF.Reporte_FOR_PINS_03_01_PDF', $data)
                ->setPaper('letter')
                ->setOption('margin-top', 90)
                ->setOption('margin-bottom', 20)
                ->setOption('header-html', view('ReportesPDF.header')->render())
                ->setOption('footer-html', view('ReportesPDF.footer')->render())
                ->setOption('footer-right', 'Página [page] de [toPage]')
                ->setOption('enable-local-file-access', true);
                //->setOption('enable-local-file-access', true); // Habilitar acceso a archivos locales
    
            return $pdf->stream('Reporte_FOR_PINS_03_01.PDF');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /*DOMPDF*/
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
