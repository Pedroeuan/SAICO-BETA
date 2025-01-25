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
        $prueba = $request->input('Prueba');
        $normaCodigo = $request->input('NormaCodigo');
        $formato = $request->input('Formato');

        // Obtener el usuario autenticado
        //$user = Auth::user();
        // Obtener el nombre del usuario
        /*$Nombre = $user->name;
        $rol = Auth::user()->rol;

        if($rol == 'Técnicos')
        {
            $Solicitudes = Solicitudes::where('tecnico',$Nombre)->get();
        }
        else
        {*/
            // Obtener todas las solicitudes
            //$Solicitudes = Solicitudes::all();
            $Solicitudes = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])->get();
        //}

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
        

        // Marcar los folios que deben ocultar el botón
        foreach ($Solicitudes as $solicitud) 
        {
            // Intentar coincidir con el patrón del folio base
            if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                $folioBase = $matches[1];  // Si coincide, asignar el valor
            } else {
                $folioBase = '';  // Si no coincide, asignar un valor predeterminado
            }
        
            // Intentar coincidir con el patrón de la letra del folio
            if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                $folioLetra = $matches[1] ?? '';  // Si coincide, asignar la letra o cadena vacía
            } else {
                $folioLetra = '';  // Si no coincide, asignar una cadena vacía
            }
        
            // Si este folio no es el último en su grupo, ocultar el botón
            $solicitud->hidePlus = isset($ultimoFolioPorGrupo[$folioBase]) && $folioLetra !== $ultimoFolioPorGrupo[$folioBase];
        }

        return view("Reportes.indexManifiesto", compact('Solicitudes'));
    }

    public function indexMenuServicios()
    {
        return view('Pruebas.pruebas');
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
