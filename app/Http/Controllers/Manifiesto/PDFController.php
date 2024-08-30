<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\Manifiesto\manifiesto;

class PDFController extends Controller
{
    public function generaManifiestoPDF($id)
    {

        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        // Ruta de la imagen en el directorio public
        $Logo = asset('images/Logo_AICO.png'); // Ajusta la ruta según sea necesario

        // Consultar datos desde la base de datos
        // Datos para el PDF (pueden ser datos estáticos o dinámicos)
        $data = [
            'title' => 'Sample PDF',
            'content' => 'This is a sample PDF generated without database interaction.',
            'Manifiesto' => $Manifiesto,
            'DestallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'Logo' => $Logo,
        ];
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoPDF', $data);

        // Descargar el PDF generado
        return $pdf->download('sample.pdf');
    }
}
