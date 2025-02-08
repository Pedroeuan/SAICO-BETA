<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;

use App\Models\Reporte\reporte;
use App\Models\Prueba\prueba;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\Formato\formato;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\devolucion;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexManifiesto(Request $request)
    {
        // Obtener los valores de los selects
        $idPrueba = $request->input('Prueba');
        $idNorma_Codigo = $request->input('NormaCodigo');
        $idFormato = $request->input('Formato');
        $formatoNombrePersonalizado = $request->input('formatoNombrePersonalizado');

        //$Solicitudes = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])->get();
        $Solicitudes = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])
        ->where('Estatus', 'MANIFIESTO')
        ->get();


        // Crear un array para almacenar el último folio encontrado para cada grupo
        $ultimoFolioPorGrupo = [];

        // Procesar cada solicitud
        foreach ($Solicitudes as $solicitud) 
        {
            $manifiesto = manifiesto::where('idSolicitud', $solicitud->idSolicitud)->first();
            $devolucion = devolucion::where('idSolicitud', $solicitud->idSolicitud)->first();  

            if ($manifiesto) 
            {
                
                $solicitud->folio = $manifiesto->Folio;
                $solicitud->pdf = $manifiesto->ScanPDF; // Guardar la ruta del PDF
                
                if($devolucion)
                {
                    //$devolucion->pdf = $devolucion->ScanPDF;
                    $solicitud->devolucion_pdf = $devolucion->ScanPDF;
                }else {
                $solicitud->devolucion_pdf = null;
                }
                
                // Verificar si la expresión regular coincide
                if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                    $folioBase = $matches[1];
                } else {
                    // Si no coincide, asignar un valor predeterminado o manejar el caso
                    $folioBase = '';
                }
        
                // Extraer la letra al final del folio si existe (después del número antes de la "/")
                if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                    $folioLetra = $matches[1] ?? ''; // Si no hay letra, asigna una cadena vacía
                } else {
                    $folioLetra = '';
                }
        
                // Verificar si este folio es el último en su grupo (mayor en orden lexicográfico)
                if (!isset($ultimoFolioPorGrupo[$folioBase]) || strcmp($folioLetra, $ultimoFolioPorGrupo[$folioBase]) > 0) {
                    $ultimoFolioPorGrupo[$folioBase] = $folioLetra;
                }
            } 
            else 
            {
                $solicitud->folio = "No Asignado";
                $solicitud->pdf = null; // No hay PDF disponible
                $solicitud->devolucion_pdf = null;
            }
        }
        
        return view("Reportes.indexManifiesto", compact('Solicitudes','idPrueba','idNorma_Codigo','idFormato','formatoNombrePersonalizado'));
    }

    public function indexMenuServicios()
    {
        return view('Pruebas.Pruebas');
    }

    public function Servicios_Pruebas(Request $request)
    {
        // Obtener el valor de 'service' del cuerpo de la solicitud
        $service = $request->input('service');

        return redirect()->route('Seleccion.Servicios.Pruebas', ['service' => $service]);
    }    

    public function Seleccion_Servicios_Pruebas(Request $request)
    {
        $service = $request->query('service');
        $Pruebas = prueba::with('norma_codigo.formato')->get();

        return view('Reportes.Servicios', compact('service', 'Pruebas'));
    }

    public function ObtenerNormas($id)
    {
        $normas = norma_codigo::where('idPrueba', $id)->get(); // Ajusta según tu estructura de base de datos
        return response()->json($normas); // Devuelve las normas como JSON
    }

    public function ObtenerFormatos($id)
    {
        $formatos = formato::where('idPrueba', $id)->get();
        return response()->json($formatos);
    }

    public function CreateReporte(Request $request)
    {
        //Obtener los Valores de los campos ocultos de indexManifiesto
        $idPrueba = $request->input('idPrueba');
        $idNorma_Codigo = $request->input('idNorma_Codigo');
        $idFormato = $request->input('idFormato');
        $idSolicitud = $request->input('selectedSolicitud');
        $formatoNombrePersonalizado = $request->input('formatoNombrePersonalizado');

        $Prueba = prueba::where('idPrueba', $idPrueba)->first();
        $Nombre_Formato = formato::where('idFormato', $idFormato)->first();

        return view("Reportes.Principal.Master", compact('idPrueba','idNorma_Codigo','idFormato','idSolicitud','Prueba','Nombre_Formato','formatoNombrePersonalizado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
