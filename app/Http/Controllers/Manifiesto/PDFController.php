<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function generaManifiestoPDF()
    {
        // Consultar datos desde la base de datos
        // Datos para el PDF (pueden ser datos estáticos o dinámicos)
        $data = [
            'title' => 'Sample PDF',
            'content' => 'This is a sample PDF generated without database interaction.',
        ];
        // Cargar la vista con los datos
        $pdf = PDF::loadView('Manifiesto.manifiestoPDF', $data);

        // Descargar el PDF generado
        return $pdf->download('sample.pdf');
    }
}
