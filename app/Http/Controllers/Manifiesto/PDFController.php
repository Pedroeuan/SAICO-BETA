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

class PDFController extends Controller
{
    public function generaManifiestoPDF($id)
    {
        $user = Auth::user();
        $nombre = $user->name;
        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();

        $Logo = public_path('images/Logo_AICO.png');

        $data = [
            'title' => 'Manifiesto PDF',
            'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];

        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoPDF', $data);

        // Ajustar las opciones de configuración
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            // Usar una fuente válida predefinida en DomPDF
            $font = $fontMetrics->getFont('Helvetica', 'normal'); // Cambiado a "Helvetica"
            $size = 10;

            // Validar y ajustar las posiciones X e Y según sea necesario
            $x = 400; // Ajusta esta posición X según sea necesario
            $y = 60; // Ajusta esta posición Y según sea necesario

            // Evitar problemas con valores no válidos para coordenadas
            if (is_numeric($x) && is_numeric($y)) {
                $text = "Página $pageNumber de $pageCount";
                $canvas->text($x, $y, $text, $font, $size);
            }
        });

        return $pdf->stream('manifiesto.pdf');
    }
}
