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
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $nombre = $user->name;
        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $generalEyC = general_eyc::all();

        $Logo = public_path('images/Logo_AICO.png'); // Ajusta la ruta según sea necesario

        // Consultar datos desde la base de datos
        // Datos para el PDF (pueden ser datos estáticos o dinámicos)
        $data = [
            'title' => 'Sample PDF',
            'content' => 'This is a sample PDF generated without database interaction.',
            'Manifiesto' => $Manifiesto,
            'DetallesSolicitud' => $DetallesSolicitud,
            'Solicitud' => $Solicitud,
            'generalEyC' => $generalEyC,
            'nombre' => $nombre,
            'Logo' => $Logo,
        ];
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoPDF', $data);

        // Descargar el PDF generado
        //return $pdf->download('sample.pdf');
        // Mostrar el PDF en el navegador
        return $pdf->stream('sample.pdf');
    }
}
