<?php

namespace App\Http\Controllers\PDFReportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PDFReportes\PDFReportes;


use App\Models\Reporte\reporte;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;
//use TCPDF;
//use FPDF;

//use iio\libmergepdf\Merger;


class PDFReportesController extends Controller
{
    public function Reportecreate()
    {
        return view('Reportes.ReporteIndex');
    }
    
}