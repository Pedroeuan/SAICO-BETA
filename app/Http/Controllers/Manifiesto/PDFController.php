<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\devolucion;


class PDFController extends Controller
{
    public function generaManifiestoPDF($id)
    {
        $user = Auth::user();
        $nombre = $user->name;
        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();
    
        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        $data = [
            'title' => 'Manifiesto PDF',
            'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,
            'nombre' => $nombre,
            'Logo' => $Logo,
            'Devolucion' => $Devolucion,
        ];
    
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoPDF', $data);
    
        // Renderizar el PDF antes de obtener el canvas
        $dompdf = $pdf->getDomPDF();
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 406; // Ajusta esta posición X según sea necesario
            $y = 72;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });
    
        return $pdf->stream('manifiesto.pdf');
    }

    public function generaManifiestoNewFormatPDF($id)
    {
        $user = Auth::user();
        $nombre = $user->name;
        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $Devolucion = devolucion::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();

        $Logo = public_path('images/Logo_AICO_R.jpg');
    
        $data = [
            'title' => 'Manifiesto PDF',
            'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,
            'nombre' => $nombre,
            'Logo' => $Logo,
            'Devolucion' => $Devolucion,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoNewFormatPDF', $data);
    
        // Renderizar el PDF antes de obtener el canvas
        /*$dompdf = $pdf->getDomPDF();
        $dompdf->render(); // Renderiza el contenido del PDF para calcular todas las páginas
    
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;
    
            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 413; // Ajusta esta posición X según sea necesario
            $y = 62;  // Ajusta esta posición Y según sea necesario
    
            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "$pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });*/
    

        return $pdf->stream('Manifiesto.NewFormat.pdf');
    }
    
}
