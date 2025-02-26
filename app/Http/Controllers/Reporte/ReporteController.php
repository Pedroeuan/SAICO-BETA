<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;

use App\Models\Prueba\prueba;
use App\Models\Formato\formato;
use App\Models\Reporte\reporte;
use App\Models\Manifiesto\manifiesto;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ReporteController extends Controller
{
    /*Para evitar el reenvio de formulario*/
    public function indexContratoProyecto()
    {
        return redirect()->route('indexINS1');
    }

    public function indexINS1(Request $request)
    {
        $Reportes = reporte::all();
        $reportesDetalles_Generales = [];
        foreach ($Reportes as $reporte) {
            $detalles = json_decode($reporte->Detalles_Generales, true);
            $reportesDetalles_Generales[] = [
                'Contrato' => $detalles['Contrato'],
                'Proyecto' => $detalles['Proyecto'],
                'Fecha' => $detalles['Fecha'],
                'No_Reporte' => $detalles['No_Reporte'],
                'idReportes' => $reporte->idReportes // Asumiendo que tienes un campo 'id' en tu modelo reporte
            ];
        }
        // Filtrar elementos únicos por 'Contrato' y 'Proyecto'
        $reportesDetalles_Generales = collect($reportesDetalles_Generales)->unique(function ($item) {
            return $item['Contrato'] . $item['Proyecto'];
        })->values()->all();

        return view('Reportes.INS.Index.indexINS1', compact('reportesDetalles_Generales'));
    }

    public function indexReporteProyectoContrato(Request $request)
    {
            // Obtener el valor seleccionado
            $contratoSeleccionado = $request->input('selectedContrato_Proyecto');

            // Obtener todos los reportes que coincidan con el contrato seleccionado
            $reportesEncontrados = reporte::whereJsonContains('Detalles_Generales->Contrato', $contratoSeleccionado)->get();

            // Decodificar el campo Detalles_Generales para cada reporte encontrado
            foreach ($reportesEncontrados as $reporte) {
                $detalles = json_decode($reporte->Detalles_Generales, true);
                $reporte->detalles = $detalles; // Añadir los detalles decodificados al objeto reporte
            }
            $Proyecto = $reportesEncontrados[0]->detalles['Proyecto'];

            if ($reportesEncontrados->isNotEmpty()) {
                return view('Reportes.INS.Index.indexINS2', compact('reportesEncontrados','contratoSeleccionado','Proyecto'));
            } else {
                return "No se encontraron reportes con ese contrato.";
            }
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

    public function Edicion_Reportes($id)
    {
        /*Obtener datos del Reporte */
        $Reporte = reporte::where('idReportes',$id)->first();
        /*Obtener datos Firmas del Reporte */
        $Firmas_Reportes = Firma_Reporte::where('idReportes',$id)->first();
        /*Obtener datos Fotos y Comentarios del Reporte */
        $Fotos_Reporte = Fotos_Reporte::where('idReportes',$id)->first();
        /*Obtener datos Juntas del Reporte */
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        // Decodificar el JSON de Detalles_Generales
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el JSON de Datos_Equipo
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el JSON de Datos_Equipo
        $Firmas = json_decode($Firmas_Reportes->Firmas, true);
        // Decodificar el JSON de Datos_Equipo
        $Fotos_Comentarios = json_decode($Fotos_Reporte->Fotos_Reportes, true);
        // Decodificar el JSON de Grupo_Juntas_Detalles_Re
        $Grupo_Juntas_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re , true);
        // Iterar sobre el arreglo y obtener los comentarios
        /*foreach ($Fotos_Comentarios as $foto) {
            $comentarios[] = $foto['comment'];
        }*/
        // Obtener el numero de firmas
        $numFirmas = $Firmas ['numFirmas'];
        // Obtener el idSolicitud
        $idSolicitud = $Detalles_Generales['idSolicitud'];
        $Solicitud = Solicitudes::findOrFail($idSolicitud);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $idSolicitud)->get();

        // Buscar el idGeneral_EyC de cada detalle
        $idGeneral_EyCs = [];
        foreach ($DetallesSolicitud as $detalle) {
            $idGeneral_EyCs[] = $detalle->idGeneral_EyC;
        }

        // Buscar los registros en la tabla General_EyC
        //Equipos
        $idsGeneral_EyCs_Equipos = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','EQUIPOS')->get();
        //Accesorios
        $idsGeneral_EyCs_Accesorios = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','ACCESORIOS')->get();
        //Block y Probeta
        $idsGeneral_EyCs_BlockyProbeta = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','BLOCK Y PROBETA')->get();

        /*Obtener id de Prueba_Aplica */
        $idPrueba_Aplica = $Reporte->idPrueba_Aplica;
        /*Obtener datos de Prueba_Aplica */
        $Prueba_Aplica = Prueba_Aplica::where('idPrueba_Aplica',$idPrueba_Aplica)->first();
        /*Obtener id de Prueba */
        $Obtener_idPrueba = $Prueba_Aplica->idPrueba;
        /*Obtener datos del id del Prueba */
        $Buscar_idFormato = prueba::where('idPrueba',$Obtener_idPrueba)->first();
        /*Obtener Nombre de Prueba */
        $Prueba = $Buscar_idFormato->Nombre;
        /*Obtener id de Formato */
        $Obtener_idFormato = $Prueba_Aplica->idFormato;
        /*Obtener datos del id del Formato */
        $Buscar_idFormato = formato::where('idFormato',$Obtener_idFormato)->first();
        /*Obtener el Nombre del Formato */
        $Nombre_Formato = $Buscar_idFormato->Nombre;
        /* Llamar a la función formatoNombrePersonalizado */
        $formatoNombrePersonalizado = $this->formatoNombrePersonalizado($Nombre_Formato);

        return view("Reportes.Principal.editMaster", compact('id','idSolicitud','Nombre_Formato','Prueba','formatoNombrePersonalizado','idPrueba_Aplica','idsGeneral_EyCs_Equipos','idsGeneral_EyCs_Accesorios','idsGeneral_EyCs_BlockyProbeta', 'idPrueba_Aplica', 'Detalles_Generales', 'Datos_Equipo','Firmas','Fotos_Comentarios','numFirmas','Grupo_Juntas_Re'));

    }

    public function formatoNombrePersonalizado ($Nombre_Formato)
    {
        $nombresPersonalizados = [
            "FOR-02-PRO-INS-02" => "INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS",
            "FOR-01-PRO-INS-03" => "INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES",
            "FOR-01-PRO-INS-04" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES NO TUBULARES",
            "FOR-02-PRO-INS-04" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES",
            "FOR-01-PRO-INS-05" => "INFORME DE INSPECCIÓN CON ULTRASONIDO DE ACUERDO CON API RP 2X",
            "FOR-01-PRO-INS-06" => "INFORME DE MEDICIÓN DE ESPESORES DE PARED EN LA TUBERÍA Y ELEMENTOS ESTRUCTURALES",
            "FOR-01-PRO-INS-07" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES",
            "FOR-01-PRO-INS-08" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR",
            "FOR-01-PRO-INS-09" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON API 1104",
            "FOR-01-PRO-INS-10" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO PARA METAL BASE",
            "FOR-02-PRO-INS-10" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA",
            "FOR-01-PRO-INS-11" => "REGISTRO DE EXAMINACIÓN AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR",
            "FOR-01-PRO-INS-12" => "INFORME DE INSPECCIÓN CON CORRIENTES EDDY",
            "FOR-01-PRO-INS-13" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO AWS D1.1",
            "FOR-01-PRO-INS-14" => "PROCEDIMIENTO DE INSPECCIÓN CON ULTRASONIDO POR EL METODO TOFD (TIME OF FLIGHT DIFFRACTION)",
            "FOR-01-PRO-INS-15" => "INFORME DE INSPECCIÓN VISUAL",
            "FOR-02-PRO-INS-15" => "INFORME DE INSPECCIÓN VISUAL DE TUBERIAS Y RECIPIENTES SUJETOS A PRESION",
            "FOR-03-PRO-INS-15" => "LISTADO DE COMPONENTES",
            "FOR-01-PRO-INS-16" => "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA",
            "FOR-01-PRO-INS-17" => "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA A TABLEROS",
            "FOR-01-PRO-INS-18" => "INFORME DE DETECCIÓN DE DISCONTINUIDADES CON CORRIENTES DE EDDY",
            "FOR-01-PRO-INS-19" => "INFORME DE INSPECCIÓN CON ACFM",
            "FOR-01-PRO-INS-20" => "Informe de Análisis mediante Corriente Eddy Pulsada (PECT)",
            "FOR-01-PRO-INS-21" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO POR ARREGLO DE FASES, DE ACUERDO CON API 1104",
            "FOR-01-PRO-INS-22" => "Ondas Guiadas"
        ];
    
        return $nombresPersonalizados[$Nombre_Formato] ?? $Nombre_Formato;
    }

    /**
     * Display a listing of the resource.
     */
    public function indexManifiesto(Request $request)
    {
        return redirect()->route('ReportesindexManifiesto', [
            'Prueba' => $request->input('Prueba'),
            'NormaCodigo' => $request->input('NormaCodigo'),
            'Formato' => $request->input('Formato'),
            'formatoNombrePersonalizado' => $request->input('formatoNombrePersonalizado'),
        ]);
        
    }

    public function ReportesindexManifiesto(Request $request)
    {
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

    public function CreateReporte(Request $request)
    {
        return redirect()->route('ReportesPrincipalMaster', [
            'idPrueba' => $request->input('idPrueba'),
            'idNorma_Codigo' => $request->input('idNorma_Codigo'),
            'idFormato' => $request->input('idFormato'),
            'selectedSolicitud' => $request->input('selectedSolicitud'),
            'formatoNombrePersonalizado' => $request->input('formatoNombrePersonalizado'),
        ]);
    }

    public function ReportesPrincipalMaster(Request $request)
    {
        // Obtener los valores de los campos ocultos de indexManifiesto
        $idPrueba = $request->input('idPrueba');
        $idNorma_Codigo = $request->input('idNorma_Codigo');
        $idFormato = $request->input('idFormato');
        $idSolicitud = $request->input('selectedSolicitud');
        $formatoNombrePersonalizado = $request->input('formatoNombrePersonalizado');

        $Solicitud = Solicitudes::findOrFail($idSolicitud);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $idSolicitud)->get();

        // Buscar el idGeneral_EyC de cada detalle
        $idGeneral_EyCs = [];
        foreach ($DetallesSolicitud as $detalle) {
            $idGeneral_EyCs[] = $detalle->idGeneral_EyC;
        }

        // Buscar los registros en la tabla General_EyC
        //Equipos
        $idsGeneral_EyCs_Equipos = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','EQUIPOS')->get();
        //Accesorios
        $idsGeneral_EyCs_Accesorios = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','ACCESORIOS')->get();
        //Block y Probeta
        $idsGeneral_EyCs_BlockyProbeta = general_eyc::whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','BLOCK Y PROBETA')->get();

        // Verificar si el registro ya existe
        $existeRegistro = Prueba_Aplica::where('idPrueba', $idPrueba)
        ->where('idNorma_Codigo', $idNorma_Codigo)
        ->where('idFormato', $idFormato)
        ->exists();

        $ObteneridRegistroExistente = Prueba_Aplica::where('idPrueba', $idPrueba)
        ->where('idNorma_Codigo', $idNorma_Codigo)
        ->where('idFormato', $idFormato)->first();

        if (!$existeRegistro) 
        {
        $Prueba_Aplica = new Prueba_Aplica;
        $Prueba_Aplica->idPrueba = $idPrueba;
        $Prueba_Aplica->idNorma_Codigo = $idNorma_Codigo;
        $Prueba_Aplica->idFormato = $idFormato;
        $Prueba_Aplica->save();

        // Obtener el idPrueba_Aplica del registro recién creado
        $idPrueba_Aplica = $Prueba_Aplica->idPrueba_Aplica;
        }
        else
        {
        $idPrueba_Aplica = $ObteneridRegistroExistente->idPrueba_Aplica;
        }
    
        $Prueba = prueba::where('idPrueba', $idPrueba)->first();
        $formato = formato::where('idFormato', $idFormato)->first();
        $Nombre_Formato = $formato ? $formato->Nombre : null; // Obtener el nombre del formato como string
    
        return view("Reportes.Principal.Master", compact('Nombre_Formato','idPrueba_Aplica','Prueba','formatoNombrePersonalizado','idSolicitud','Solicitud','DetallesSolicitud','idsGeneral_EyCs_Equipos','idsGeneral_EyCs_Accesorios','idsGeneral_EyCs_BlockyProbeta'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function indexINS2(Request $request)
    {
        $contratoSeleccionado = $request->input('contratoSeleccionado');
        $Proyecto = $request->input('Proyecto');

        $reportesEncontrados = reporte::whereJsonContains('Detalles_Generales->Contrato', $contratoSeleccionado)->get();

        if ($reportesEncontrados->isNotEmpty()) {
            return view('Reportes.INS.Index.indexINS2', compact('reportesEncontrados', 'contratoSeleccionado', 'Proyecto'));
        } else {
            return "No se encontraron reportes con ese contrato.";
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function FOR_02_PRO_INS_10_store(Request $request)
    {
        //dd($request->all());

        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'required|string|max:255',
            'Detalles_Generales.Cliente' => 'nullable|string|max:255',
            'Detalles_Generales.Contrato' => 'nullable|string|max:255',
            'Detalles_Generales.Proyecto' => 'nullable|string|max:255',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string|max:255',
            'Detalles_Generales.Folio' => 'nullable|string|max:255',
            'Detalles_Generales.Partida' => 'nullable|string|max:255',
            'Detalles_Generales.Lugar' => 'nullable|string|max:255',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string|max:255',
            'Detalles_Generales.Pieza' => 'nullable|string|max:255',
            'Detalles_Generales.Material' => 'nullable|string|max:255',
            'Detalles_Generales.Procedimiento' => 'nullable|string|max:255',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string|max:255',
            'Detalles_Generales.idSolicitud' => 'nullable|string|max:255',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.FRECC_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string|max:255',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string|max:255',
            'Datos_Equipo.GANANCIA' => 'nullable|string|max:255',
            'Datos_Equipo.RANGO' => 'nullable|string|max:255',
            'Datos_Equipo.RECHAZO' => 'nullable|string|max:255',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string|max:255',
            'Datos_Equipo.PINTURA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'elemento_tubo' => 'nullable|array',
            'no_aceptacion' => 'nullable|array',
            'no_serie' => 'nullable|array',
            'no_colada' => 'nullable|array',
            'tnominal' => 'nullable|array',
            'diametro' => 'nullable|array',
            'no_ind' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'nr' => 'nullable|array',
            'ni' => 'nullable|array',
            'ht' => 'nullable|array',
            'prof' => 'nullable|array',
            'la' => 'nullable|array',
            'lc' => 'nullable|array',
            'tmax' => 'nullable|array',
            'tmin' => 'nullable|array',
            'metros_lineales' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'observaciones' => 'nullable|array',

            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:2,3,4',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string|max:255',
        ]);

        //En la validación de Laravel, nullable significa que el campo puede estar vacío (nulo) 
        // y no se aplicarán las demás reglas de validación si el campo está vacío. Esto es útil 
        // cuando tienes campos opcionales en tu formulario.

        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos

        $Reportes->idPrueba_Aplica = $request->input('idPrueba_Aplica'); 

        //$Reportes->Contrato = json_encode($validatedData['Detalles_Generales']['Contrato']); //Fila Contrato en la Tabla Reportes, Borrar por si acaso
        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;

            Log::info('***********************');
            Log::info('Iniciando el guardado de datos.');

            // Verificar si validatedData contiene datos
            if (empty($validatedData['elemento_tubo'])) {
                Log::error('validatedData[elemento_tubo] está vacío.');
            }
            /*Resultados Juntas*/
            // Guardar las filas dinámicas
            $Resultados_Juntas = [];
            Log::info('Cantidad de elementos a procesar: ' . count($validatedData['elemento_tubo']));
            foreach ($validatedData['elemento_tubo'] as $index => $elemento_tubo) {
                $Resultados_Juntas[] = [
                    'elemento_tubo' => $elemento_tubo,
                    'no_aceptacion' => $validatedData['no_aceptacion'][$index],
                    'no_serie' => $validatedData['no_serie'][$index],
                    'no_colada' => $validatedData['no_colada'][$index],
                    'tnominal' => $validatedData['tnominal'][$index],
                    'diametro' => $validatedData['diametro'][$index],
                    'no_ind' => $validatedData['no_ind'][$index],
                    'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                    'nr' => $validatedData['nr'][$index],
                    'ni' => $validatedData['ni'][$index],
                    'ht' => $validatedData['ht'][$index],
                    'prof' => $validatedData['prof'][$index],
                    'la' => $validatedData['la'][$index],
                    'lc' => $validatedData['lc'][$index],
                    'tmax' => $validatedData['tmax'][$index],
                    'tmin' => $validatedData['tmin'][$index],
                    'metros_lineales' => $validatedData['metros_lineales'][$index],
                    'evaluacion' => $validatedData['evaluacion'][$index],
                    'observaciones' => $validatedData['observaciones'][$index],
                ];
            }

            Log::info('Cantidad de registros a guardar: ' . count($Resultados_Juntas));
    
            // Convertir el array de resultados juntas a JSON
            $ResultadosJuntas = json_encode($Resultados_Juntas);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error en json_encode: ' . json_last_error_msg());
            }
            
            Log::info('JSON generado correctamente.');
            // Obtener el número de caracteres
            $numCaracteres = mb_strlen($ResultadosJuntas, '8bit'); // Tamaño en bytes
            $numBytes = strlen($ResultadosJuntas); // Tamaño en bytes
            Log::info('numCaracteres: ', ['numCaracteres' => $numCaracteres]);
            Log::info('numBytes: ', ['numBytes' => $numBytes]);
            
    
            $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;
            //Guardar Datos_Equipo como JSON en la base de datos
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_Re->save();

            Log::info('Datos de Juntas guardados correctamente.');
            Log::info('***********************');
        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes2']);
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes3']);
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes4']);
        }

        $Firmas_Reportes->idReportes = $idReportes;
        $Firmas_Reportes->save();

        /*Fotos y Comentarios */
        // Procesar las imágenes y los comentarios
        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) {
                $image = $request->file("image$i");
                $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
                $Contrato = $validatedData['Detalles_Generales']['Contrato'];
                $path = $image->store("public/Reportes/FOR_02_PRO_INS_10/$Contrato/$No_Reporte/Fotos");
                $comment = $request->input("comment$i");
                $fotos[] = [
                    'path' => $path,
                    'comment' => $comment,
                ];
            }
        }

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($fotos); 

        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);

    }

    public function FOR_02_PRO_INS_10_update(Request $request, $id)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'required|string|max:255',
            'Detalles_Generales.Cliente' => 'nullable|string|max:255',
            'Detalles_Generales.Contrato' => 'nullable|string|max:255',
            'Detalles_Generales.Proyecto' => 'nullable|string|max:255',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string|max:255',
            'Detalles_Generales.Folio' => 'nullable|string|max:255',
            'Detalles_Generales.Partida' => 'nullable|string|max:255',
            'Detalles_Generales.Lugar' => 'nullable|string|max:255',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string|max:255',
            'Detalles_Generales.Pieza' => 'nullable|string|max:255',
            'Detalles_Generales.Material' => 'nullable|string|max:255',
            'Detalles_Generales.Procedimiento' => 'nullable|string|max:255',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string|max:255',
            'Detalles_Generales.idSolicitud' => 'nullable|string|max:255',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.FRECC_TRANSDUCTOR' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string|max:255',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string|max:255',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string|max:255',
            'Datos_Equipo.GANANCIA' => 'nullable|string|max:255',
            'Datos_Equipo.RANGO' => 'nullable|string|max:255',
            'Datos_Equipo.RECHAZO' => 'nullable|string|max:255',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string|max:255',
            'Datos_Equipo.PINTURA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'elemento_tubo' => 'required|array',
            'no_aceptacion' => 'required|array',
            'no_serie' => 'required|array',
            'no_colada' => 'required|array',
            'tnominal' => 'required|array',
            'diametro' => 'required|array',
            'no_ind' => 'required|array',
            'tipo_indicacion' => 'required|array',
            'nr' => 'required|array',
            'ni' => 'required|array',
            'ht' => 'required|array',
            'prof' => 'required|array',
            'la' => 'required|array',
            'lc' => 'required|array',
            'tmax' => 'required|array',
            'tmin' => 'required|array',
            'metros_lineales' => 'required|array',
            'evaluacion' => 'required|array',
            'observaciones' => 'required|array',

            //Validar el campo NumFirmas
            'numFirmas' => 'required|integer|in:2,3,4',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string|max:255',


            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string|max:255',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string|max:255',
        ]);

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::where('idReportes',$id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']) 
        ]);

        /*Resultados Juntas*/
        // Guardar las filas dinámicas
        $Resultados_Juntas = [];
        foreach ($validatedData['elemento_tubo'] as $index => $elemento_tubo) {
            $Resultados_Juntas[] = [
                'elemento_tubo' => $elemento_tubo,
                'no_aceptacion' => $validatedData['no_aceptacion'][$index],
                'no_serie' => $validatedData['no_serie'][$index],
                'no_colada' => $validatedData['no_colada'][$index],
                'tnominal' => $validatedData['tnominal'][$index],
                'diametro' => $validatedData['diametro'][$index],
                'no_ind' => $validatedData['no_ind'][$index],
                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                'nr' => $validatedData['nr'][$index],
                'ni' => $validatedData['ni'][$index],
                'ht' => $validatedData['ht'][$index],
                'prof' => $validatedData['prof'][$index],
                'la' => $validatedData['la'][$index],
                'lc' => $validatedData['lc'][$index],
                'tmax' => $validatedData['tmax'][$index],
                'tmin' => $validatedData['tmin'][$index],
                'metros_lineales' => $validatedData['metros_lineales'][$index],
                'evaluacion' => $validatedData['evaluacion'][$index],
                'observaciones' => $validatedData['observaciones'][$index],
            ];
        }

        // Convertir el array de resultados juntas a JSON
        $ResultadosJuntas = json_encode($Resultados_Juntas);

        // Actualiza los detalles generales como JSON en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => $ResultadosJuntas
        ]);

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas2 = json_encode($validatedData['Firmas_Reportes2']);
            $Firmas->update([
                'Firmas' => $Firmas2
            ]);
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas3 = json_encode($validatedData['Firmas_Reportes3']);
            $Firmas->update([
                'Firmas' => $Firmas3
            ]);
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas4 = json_encode($validatedData['Firmas_Reportes4']);
            $Firmas->update([
                'Firmas' => $Firmas4
            ]);
        }

         /*Fotos y Comentarios */
        // Procesar las imágenes y los comentarios

        // Obtener las rutas de las imágenes guardadas anteriormente
        $previousFotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);

        // Procesar las nuevas imágenes y los comentarios
        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) {
                // Eliminar la imagen anterior si existe
                if (isset($previousFotos[$i - 1]['path']) && Storage::exists($previousFotos[$i - 1]['path'])) {
                    Storage::delete($previousFotos[$i - 1]['path']);
                }

                // Guardar la nueva imagen
                $image = $request->file("image$i");
                $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
                $Contrato = $validatedData['Detalles_Generales']['Contrato'];
                $path = $image->store("public/Reportes/FOR_02_PRO_INS_10/$Contrato/$No_Reporte/Fotos");
                $comment = $request->input("comment$i");
                $fotos[] = [
                    'path' => $path,
                    'comment' => $comment,
                ];
            } else {
                // Mantener la imagen anterior si no se está actualizando
                if (isset($previousFotos[$i - 1])) {
                    $fotos[] = $previousFotos[$i - 1];
                }
            }
        }

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($fotos);

        // Actualiza los detalles generales como JSON en la base de datos
        $Fotos_Reportes->update([
            'Fotos_Reportes' => $Fotos
        ]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
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
