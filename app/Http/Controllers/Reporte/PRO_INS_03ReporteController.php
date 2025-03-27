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


class PRO_INS_03ReporteController extends Controller
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

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto, 'reportesEncontrados' => $reportesEncontrados]);
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
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();
        if (!$Fotos_Reportes) {
            // Manejar el caso en que no se encuentran datos de Fotos_Reportes
            return back()->withErrors(['Fotos_Reportes' => 'No se encontraron datos de Fotos_Reportes para el reporte especificado.']);
        }        
        /*Obtener datos Juntas del Reporte */
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        // Decodificar el JSON de Detalles_Generales
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el JSON de Datos_Equipo
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        // Decodificar el JSON de Firmas
        $Firmas = json_decode($Firmas_Reportes->Firmas, true);
        // Decodificar el JSON de Fotos_Comentarios
        $Fotos_Comentarios = json_decode($Fotos_Reportes->Fotos_Reportes, true);
        // Decodificar el JSON de Grupo_Juntas_Detalles_Re
        $Grupo_Juntas_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true);

        // Verificar si el índice 'numFirmas' existe en el array $Firmas
        if (isset($Firmas['numFirmas'])) {
            $numFirmas = $Firmas['numFirmas'];
        } else {
            $numFirmas = 0; // Valor predeterminado si no existe
        }

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

    public function FOR_02_PRO_INS_02_store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            /* Detalles Generales */
            'Detalles_Generales' => 'required|array',
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
    
            /* Datos del Equipo */
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.MARCA_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.CORRIENTE_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.DISTANCIA_PATAS_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_LUZ' => 'nullable|string|max:255',
            'Datos_Equipo.INTENCIDAD' => 'nullable|string|max:255',
            'Datos_Equipo.CONDICION_SUPERFICIAL' => 'nullable|string|max:255',
            'Datos_Equipo.TEMPERATURA_PRUEBA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',
    
            /* Resultados Juntas */
            'componente' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'longitud_inspeccionada' => 'nullable|array',
    
            'numFirmas' => 'nullable|integer|in:2,3,4',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes2.Vobo1' => 'nullable|string|max:255',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo2' => 'nullable|string|max:255',

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
            'Firmas_Reportes4.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo2' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo3' => 'nullable|string|max:255',

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
    
        // Guardar Detalles Generales
        $Reportes = new reporte();
        $Reportes->idPrueba_Aplica = $request->input('idPrueba_Aplica');
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);
        $Reportes->Estatus = "CREADO";
        $Reportes->save();

        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();
                $Grupo_Juntas_Detalles_Re->idReportes = $Reportes->idReportes;

                $Resultados_Juntas = [];
                foreach($validatedData['componente'] as $index => $componente) {
                        $Resultados_Juntas[] = [
                                'componente' => $componente,
                                'no_indicacion' => $validatedData['no_indicacion'][$index],
                                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                                'largo' => $validatedData['largo'][$index],
                                'ancho' => $validatedData['ancho'][$index],
                                'diametro' => $validatedData['diametro'][$index],
                                'ht' => $validatedData['ht'][$index],
                                'evaluacion' => $validatedData['evaluacion'][$index],
                                'longitud_inspeccionada' => $validatedData['longitud_inspeccionada'][$index],
                        ];
                }
                // Convertir el array de resultados juntas a JSON
                $ResultadosJuntas = json_encode($Resultados_Juntas);

                $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = $ResultadosJuntas;
                $Grupo_Juntas_Detalles_Re->save();
            
        // Guardar Firmas
        $Firmas_Reportes = new Firma_Reporte();
        $Firmas_Reportes->idReportes = $Reportes->idReportes;
        $numFirmas = $request->input('numFirmas');
    
        if ($numFirmas == 2) {
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes2']);
        } elseif ($numFirmas == 3) {
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes3']);
        } else {
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes4']);
        }
        $Firmas_Reportes->save();
    
        // Guardar Fotos
        $Fotos_Reportes = new Fotos_Reporte();
        $Fotos_Reportes->idReportes = $Reportes->idReportes;
    
        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($request->hasFile("image$i")) {
                $image = $request->file("image$i");
                $path = $image->store("public/Reportes/FOR_02_PRO_INS_10/{$validatedData['Detalles_Generales']['Contrato']}/{$validatedData['Detalles_Generales']['No_Reporte']}/Fotos");
                $fotos[] = [
                    'path' => $path,
                    'comment' => $request->input("comment$i"),
                ];
            }
        }
    
        $Fotos_Reportes->Fotos_Reportes = json_encode($fotos);
        $Fotos_Reportes->save();
    
        // Redireccionar
        return redirect()->route('indexINS2', [
            'contratoSeleccionado' => $validatedData['Detalles_Generales']['Contrato'],
            'Proyecto' => $validatedData['Detalles_Generales']['Proyecto'],
        ]);
    }


    public function FOR_02_PRO_INS_02_update(Request $request, $id)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los datos del formulario
        $validatedData = $request->validate([
            /* Detalles Generales */
            'Detalles_Generales' => 'required|array',
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
    
            /* Datos del Equipo */
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.MARCA_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_PARTICULAS' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.LOTE_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.COLOR_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.APLICACION_CONTRASTE' => 'nullable|string|max:255',
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.CORRIENTE_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.DISTANCIA_PATAS_EQUIPO' => 'nullable|string|max:255',
            'Datos_Equipo.TIPO_LUZ' => 'nullable|string|max:255',
            'Datos_Equipo.INTENCIDAD' => 'nullable|string|max:255',
            'Datos_Equipo.CONDICION_SUPERFICIAL' => 'nullable|string|max:255',
            'Datos_Equipo.TEMPERATURA_PRUEBA' => 'nullable|string|max:255',
            'Datos_Equipo.Observaciones' => 'nullable|string|max:255',
    
            /* Resultados Juntas */
            'componente' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'evaluacion' => 'nullable|array',
            'longitud_inspeccionada' => 'nullable|array',
    
        'numFirmas' => 'required|integer|in:2,3,4',

             /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes2.Vobo1' => 'nullable|string|max:255',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string|max:255',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string|max:255',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string|max:255',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes3.Vobo2' => 'nullable|string|max:255',

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
            'Firmas_Reportes4.Realizo' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo1' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo2' => 'nullable|string|max:255',
            'Firmas_Reportes4.Vobo3' => 'nullable|string|max:255',

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
    
        // Guardar Detalles Generales
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

        $Resultados_Juntas = [];
        foreach ($validatedData['componente'] as $index => $componente) {
            $Resultados_Juntas[] = [

                'componente' => $componente,
                'no_indicacion' => $validatedData['no_indicacion'][$index],
                'tipo_indicacion' => $validatedData['tipo_indicacion'][$index],
                'largo' => $validatedData['largo'][$index],
                'ancho' => $validatedData['ancho'][$index],
                'diametro' => $validatedData['diametro'][$index],
                'ht' => $validatedData['ht'][$index],
                'evaluacion' => $validatedData['evaluacion'][$index],
                'longitud_inspeccionada' => $validatedData['longitud_inspeccionada'][$index],
            ];
        }

        // Convertir el array de resultados juntas a JSON
        $ResultadosJuntas = json_encode($Resultados_Juntas);

        // Actualiza los detalles generales como JSON en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => $ResultadosJuntas
        ]);
    
        // Guardar Firmas
        $numFirmas = $request->input('numFirmas');
    
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
    
        $previousFotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);

        $fotos = [];
        for ($i = 1; $i <= 4; $i++) {
            $comment = $request->input("comment$i", ""); // Obtener el comentario incluso si la imagen no cambia
            Log::info("Comentario recibido para imagen $i: ", ['comment' => $comment]);
        
            if ($request->hasFile("image$i")) {
                // Eliminar la imagen anterior si existe
                if (isset($previousFotos[$i - 1]['path']) && Storage::exists($previousFotos[$i - 1]['path'])) {
                    Storage::delete($previousFotos[$i - 1]['path']);
                }
        
                // Guardar la nueva imagen
                $image = $request->file("image$i");
                $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
                $Contrato = $validatedData['Detalles_Generales']['Contrato'];
                $path = $image->store("public/Reportes/FOR_01_PRO_INS_02/$Contrato/$No_Reporte/Fotos");
        
                $fotos[] = [
                    'path' => $path,
                    'comment' => $comment, // Guardar el comentario actualizado
                ];
            } else {
                // Mantener la imagen anterior pero actualizar el comentario si cambió
                if (isset($previousFotos[$i - 1])) {
                    $fotos[] = [
                        'path' => $previousFotos[$i - 1]['path'],
                        'comment' => $comment ?: $previousFotos[$i - 1]['comment'], // Si el nuevo comentario está vacío, mantener el anterior
                    ];
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
