<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;

use App\Models\OC\OC;
use App\Models\Prueba\prueba;
use App\Models\Formato\formato;
use App\Models\Reporte\reporte;
use App\Models\Clientes\clientes;
use App\Models\detallesOC\detallesOC;
use App\Models\Manifiesto\manifiesto;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\Normas_IM\Normas_IM;
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\Procedimientos\Procedimiento;
use App\Models\OrdenServicio\Orden_Servicio;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Models\Admin\Usuario;
use App\Services\ServicioRegistrosFotos;
use App\Services\ServicioPatronGranoReporte;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;


class ReporteController extends Controller
{
    /** Carga el catálogo visual solo para los formatos IM que realmente lo consumen. */
    private function catalogoPatronesGranoIM(?string $nombreFormato): \Illuminate\Support\Collection
    {
        if (!in_array($nombreFormato, ['FOR-PIMP-03_B/01', 'FOR-PIMP-04/02', 'FOR-PIMP-04/03', 'FOR-PIMP-06_B/01'], true)) {
            return collect();
        }

        return app(ServicioPatronGranoReporte::class)->catalogoParaVista();
    }

    /**
     * Reutiliza materiales y escalas guardados en reportes 02_B_04.
     * Cada reporte conserva su texto historico y los valores nuevos alimentan ambos catalogos.
     */
    private function catalogosDureza0204(?string $nombreFormato, ?int $idFormato): array
    {
        if ($nombreFormato !== 'FOR-PIMP-02_B/04' || !$idFormato) {
            return ['materiales' => collect(), 'escalas' => collect()];
        }

        $idsPruebaAplica = Prueba_Aplica::where('idFormato', $idFormato)->pluck('idPrueba_Aplica');
        $materiales = collect(['Base Metal']);
        $escalas = collect(['HB', 'HV', 'HL', 'HRC', 'HRB']);

        reporte::whereIn('idPrueba_Aplica', $idsPruebaAplica)
            ->whereNotNull('Datos_Equipo')
            ->pluck('Datos_Equipo')
            ->each(function ($datosEquipo) use ($materiales, $escalas) {
                $datos = json_decode($datosEquipo, true) ?: [];

                foreach (['ETIQUETA_MATERIAL_A', 'ETIQUETA_MATERIAL_A1'] as $campo) {
                    $nombre = trim((string) ($datos[$campo] ?? ''));
                    if ($nombre !== '') {
                        $materiales->push($nombre);
                    }
                }

                $escala = trim((string) ($datos['ESCALA_DUREZA'] ?? ''));
                if ($escala !== '') {
                    $escalas->push($escala);
                }
            });

        $normalizarCatalogo = static fn ($valores) => $valores
            ->unique(fn ($valor) => mb_strtolower($valor, 'UTF-8'))
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return [
            'materiales' => $normalizarCatalogo($materiales),
            'escalas' => $normalizarCatalogo($escalas),
        ];
    }

    /**
     * Reutiliza la preparación metalográfica capturada en 03_B/01, 04_02 y 04_03.
     * No impone opciones iniciales: el catálogo se forma con los valores realmente guardados.
     */
    private function catalogosMetalografiaIM(?string $nombreFormato): array
    {
        $formatosCompatibles = ['FOR-PIMP-03_B/01', 'FOR-PIMP-04/02', 'FOR-PIMP-04/03'];
        $catalogos = [
            'lijas' => collect(),
            'abrasivos' => collect(),
            'reactivos' => collect(),
            'fases' => collect(),
        ];

        if (!in_array($nombreFormato, $formatosCompatibles, true)) {
            return $catalogos;
        }

        $idsFormato = formato::whereIn('Nombre', $formatosCompatibles)->pluck('idFormato');
        $idsPruebaAplica = Prueba_Aplica::whereIn('idFormato', $idsFormato)->pluck('idPrueba_Aplica');

        reporte::whereIn('idPrueba_Aplica', $idsPruebaAplica)
            ->whereNotNull('Datos_Equipo')
            ->pluck('Datos_Equipo')
            ->each(function ($datosEquipo) use (&$catalogos) {
                $datos = json_decode($datosEquipo, true) ?: [];

                foreach (is_array($datos['LIJAS_DESBASTE'] ?? null) ? $datos['LIJAS_DESBASTE'] : [] as $lija) {
                    $catalogos['lijas']->push(trim((string) $lija));
                }

                foreach ([
                    'MATERIAL_ABRASIVO' => 'abrasivos',
                    'REACTIVO' => 'reactivos',
                    'FASES_PRESENTES' => 'fases',
                ] as $campo => $catalogo) {
                    $catalogos[$catalogo]->push(trim((string) ($datos[$campo] ?? '')));
                }
            });

        foreach ($catalogos as $nombre => $valores) {
            $catalogos[$nombre] = $valores
                ->filter()
                ->unique(fn ($valor) => mb_strtolower($valor, 'UTF-8'))
                ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                ->values();
        }

        return $catalogos;
    }


    public function FOR_01_PRO_INS_02()
    {
        return view('Reportes.INS.Create.FOR-01-PRO-INS-02');
    }
    public function FOR_PINS_04_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-04_01');
    }
    public function FOR_PINS_05_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-05_01');
    }
    public function FOR_PINS_05_02()
    {
        return view('Reportes.PINS.Create.FOR-PINS-05_02');
    }
    public function FOR_PINS_06_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-06_01');
    }
    public function FOR_PINS_07_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-07_01');
    }
    public function FOR_PINS_08_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-08_01');
    }
    public function FOR_PINS_09_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-09_01');
    }
    public function FOR_PINS_10_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-10_01');
    }
    public function FOR_PINS_11_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-11_01');
    }
    public function FOR_PINS_11_02()
    {
        return view('Reportes.PINS.Create.FOR-PINS-11_02');
    }
    public function FOR_PINS_12_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-12_01');
    }
    public function FOR_PINS_13_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-13_01');
    }
    public function FOR_PINS_14_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-14_01');
    }
    
    public function FOR_PINS_15_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-15_01');
    }
    public function FOR_PINS_16_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-16_01');
    }
    public function FOR_PINS_17_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-17_01');
    }
    public function FOR_PINS_17_01_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-17-01_01');
    }
    public function FOR_PINS_18_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-18_01');
    }
    public function FOR_PINS_19_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-19_01');
    }
    public function FOR_PINS_20_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-20_01');
    }
    public function FOR_PINS_21_01 ()
    {
        return view('Reportes.PINS.Create.FOR-PINS-21_01');
    }
    public function FOR_PINS_22_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-22_01');
    }
    public function FOR_PINS_23_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-23_01');
    }
    public function FOR_PINS_24_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-24_01');
    }
    public function FOR_PINS_25_01()
    {
        return view('Reportes.PINS.Create.FOR-PINS-25_01');
    }

    /*public function FOR_PIMP_07_B_01()
    {
        return view('Reportes.IM.Create.FOR-PIMP-07_B_01');
    }*/
    
    public function obtenerSiguienteContratoInterno()
    {
    // Obtener TODOS los registros asegurando el orden correcto
        $registros = reporte::orderBy('idReportes', 'DESC')->get();

        $ultimoNumero = 0;

        foreach ($registros as $r) {

            // Decodificar JSON de la columna
            $json = json_decode($r->Detalles_Generales, true);

            if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {

                // Extraer el número final
                $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));

                if ($n > $ultimoNumero) {
                    $ultimoNumero = $n;
                }

                break; // Ya encontramos el más reciente
            }
        }

        // Nuevo número consecutivo
        $nuevoNumero = $ultimoNumero + 1;

        // Crear contrato con padding de 4 dígitos
        $siguiente = "AICO-INT-" . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);

        return response()->json([
            'siguiente' => $siguiente
        ]);
    }
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
            $detalles = json_decode($reporte->Detalles_Generales, true) ?? [];
            $reportesDetalles_Generales[] = [
                'Contrato' => $detalles['Contrato'] ?? '',
                'Proyecto' => $detalles['Proyecto'] ?? $detalles['Identificacion'] ?? '',
                'Cliente' => $detalles['Cliente'] ?? '',
                'Fecha' => $detalles['Fecha'] ?? '',
                'No_Reporte' => $detalles['No_Reporte'] ?? '',
                'idReportes' => $reporte->idReportes
            ];
        }
        // Filtrar elementos únicos por 'Contrato' y 'Proyecto'
        $reportesDetalles_Generales = collect($reportesDetalles_Generales)->unique(function ($item) {
            //return $item['Contrato'] . $item['Proyecto'];
            return $item['Contrato']; //Solo contrato si se agrega poryecto, genera repeticoones, por no pones el proyecto de la misma manera (Usuarios Â¬Â¬).
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
            $Proyecto = $reportesEncontrados[0]->detalles['Proyecto'] ?? $reportesEncontrados[0]->detalles['Identificacion'] ?? '';

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto, 'reportesEncontrados' => $reportesEncontrados]);
    }


    public function ObtenerNormas($id)
    {
        $normas = norma_codigo::where('idPrueba', $id)->get(); // Ajusta según tu estructura de base de datos
        return response()->json($normas); // Devuelve las normas como JSON
    }

    public function ObtenerFormatos($id)
    {
        //$formatos = formato::where('idPrueba', $id)->get();
        $formatos = formato::where('idNorma_codigo', $id)->get();
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
        $Firmas = $Firmas_Reportes ? json_decode($Firmas_Reportes->Firmas, true) : [];
        $Firmas = is_array($Firmas) ? $Firmas : [];
        // Decodificar el JSON de Datos_Equipo
        $Fotos_Comentarios = $Fotos_Reporte
            ? json_decode($Fotos_Reporte->Fotos_Reportes, true)
            : [];
        $Fotos_Comentarios = ServicioRegistrosFotos::deduplicar(
            is_array($Fotos_Comentarios) ? $Fotos_Comentarios : []
        );
        // Decodificar el JSON de Grupo_Juntas_Detalles_Re
        $Grupo_Juntas_Re = $Grupo_Juntas_Detalles_Re
            ? json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true)
            : [];
        
        $imagenes = [];
        if ($Fotos_Reporte && $Fotos_Reporte->Fotos_Reportes) {
            $imagenes = json_decode($Fotos_Reporte->Fotos_Reportes, true);
        }


        // Obtener el numero de firmas
        $numFirmas = $Firmas['numFirmas'] ?? 1;
        // Obtener el idSolicitud
        $idSolicitud = $Detalles_Generales['idSolicitud'];
        $Solicitud = Solicitudes::findOrFail($idSolicitud);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $idSolicitud)->get();

        // Buscar el idGeneral_EyC de cada detalle
        $idGeneral_EyCs = [];
        foreach ($DetallesSolicitud as $detalle) {
            $idGeneral_EyCs[] = $detalle->idGeneral_EyC;
        }

        //Equipos
        $idsGeneral_EyCs_Equipos = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','EQUIPOS')->get();
        //Herramientas
        $idsGeneral_EyCs_Herramientas = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','HERRAMIENTAS')->get();
        //Accesorios
        $idsGeneral_EyCs_Accesorios = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','ACCESORIOS')->get();
        //Block y Probeta
        $idsGeneral_EyCs_BlockyProbeta = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','BLOCK Y PROBETA')->get();
        //Consumibles
        $idsGeneral_EyCs_Consumibles = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','CONSUMIBLES')->get();

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
        $idProcedimiento = $Buscar_idFormato->idProcedimiento;
        //$Procedimiento = Procedimiento::where('idProcedimiento', $idProcedimiento)->first();
        /* Llamar a la función formatoNombrePersonalizado */
        $formatoNombrePersonalizado = $this->formatoNombrePersonalizado($Nombre_Formato);
        $Clientes = clientes::where('Cliente', '!=', 'POR DEFINIR')->get();
        // Obtén todos los usuario que tengan el rol Técnico
        $Tecnicos = Usuario::where('rol', 'Técnicos')->where('Estatus', 'Alta')->get();

        $NormasIM = Normas_IM::orderBy('Nombre_Espe')->orderBy('Variable')->get()->map(function ($norma) {
            return [
                'idnormas_im' => $norma->idnormas_im,
                'Nombre_Espe' => $norma->Nombre_Espe,
                'Variable' => $norma->Variable,
                'Tabla' => json_decode($norma->Tabla, true) ?: [],
                'Observaciones' => $norma->Observaciones,
            ];
        })->values();

        // Catálogo independiente: el reporte recibe solo datos seguros para construir el select y su vista previa.
        $PatronesGranoIM = $this->catalogoPatronesGranoIM($Nombre_Formato);
        $CatalogosDureza0204 = $this->catalogosDureza0204($Nombre_Formato, (int) $Obtener_idFormato);
        $MaterialesDureza0204 = $CatalogosDureza0204['materiales'];
        $EscalasDureza0204 = $CatalogosDureza0204['escalas'];
        $CatalogosMetalografiaIM = $this->catalogosMetalografiaIM($Nombre_Formato);

        return view("Reportes.Principal.editMaster", compact('id','idSolicitud','Nombre_Formato','Prueba','formatoNombrePersonalizado','idPrueba_Aplica','idsGeneral_EyCs_Equipos','idsGeneral_EyCs_Herramientas','idsGeneral_EyCs_Accesorios','idsGeneral_EyCs_BlockyProbeta','idsGeneral_EyCs_Consumibles', 'idPrueba_Aplica', 'Detalles_Generales', 'Datos_Equipo','Firmas','Fotos_Comentarios','imagenes','numFirmas','Grupo_Juntas_Re','Clientes','Tecnicos','idProcedimiento','NormasIM','PatronesGranoIM','MaterialesDureza0204','EscalasDureza0204','CatalogosMetalografiaIM'));

    }

    public function formatoNombrePersonalizado ($Nombre_Formato)
    {
        $nombresPersonalizados = [
            "FOR-PINS-03-02" => "INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS",
            "FOR-PINS-04-01" => "INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES",
            "FOR-PINS-05-01" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES NO TUBULARES",
            "FOR-PINS-05-02" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES",
            "FOR-PINS-06-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO DE ACUERDO CON API RP 2X",
            "FOR-PINS-07-01" => "INFORME DE MEDICIÓN DE ESPESORES DE PARED EN LA TUBERÍA Y ELEMENTOS ESTRUCTURALES",
            "FOR-PINS-08-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD",
            "FOR-PINS-09-01" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR",
            "FOR-PINS-10-01" => "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON API 1104",
            "FOR-PINS-11-01" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO PARA METAL BASE",
            "FOR-PINS-11-02" => "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA",
            "FOR-PINS-12-01" => "REGISTRO DE EXAMINACIÓN AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR",
            "FOR-PINS-13-01" => "INFORME DE INSPECCIÓN CON CORRIENTES EDDY",
            "FOR-PINS-14-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO AWS D1.1",
            "FOR-PINS-15-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD",
            "FOR-PINS-16-01" => "INFORME DE INSPECCIÓN VISUAL A ELEMENTOS DE TUBERÍAS DE PROCESO",
            "FOR-PINS-17-01" => "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA",
            "FOR-PINS-17-01_01" => "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA A TABLEROS",
            "FOR-PINS-18-01" => "INFORME DE DETECCIÓN DE DISCONTINUIDADES CON CORRIENTES DE EDDY",
            "FOR-PINS-19-01" => "INFORME DE INSPECCIÓN CON ACFM",
            "FOR-PINS-20-01" => "INFORME DE ANÁLISIS MEDIANTE CORRIENTE EDDY PULSADA (PECT).",
            "FOR-PINS-21-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO API 1104",
            "FOR-PINS-22-01" => "INFORME DE  INSPECCIÓN DE TUBERIA POR CORREINTES EDDY.", //NUEVO FORMATO
            "FOR-PINS-23-01" => "INFORME DE INSPECCIÓN CON EL MÉTODO DE ONDAS GUIADAS",
            "FOR-PINS-24-01" => "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES y TOFD", //MISMO FORMATO QUE EL 15-01 
            "FOR-PINS-25-01" => "INSPECCIÓN VISUAL EN RSP",
            "FOR-03-PRO-INS-15" => "LISTADO DE COMPONENTES", //Mantiene su mismo formato pero con un nombre personalizado
            "FOR-PIMP-07_B/01" => "TRATAMIENTO TÉRMICO DE PWHT (INFORME DE RELEVADO DE ESFUERZOS)",
            "FOR-PIMP-02_B/03"=> "INFORME DE ENSAYO DE DUREZAS EN METALES BASE HARDNESS TEST REPORT ON BASE METALS",
            "FOR-PIMP-02_B/04"=> "INFORME DE ENSAYO DE DUREZAS EN SOLDADURAS TEST REPORT ON WELDING HARDNESS",
            "FOR-PIMP-03_B/01"=> "INFORME DE ANÁLISIS METALÓGRFICO METALLOGRAPHIC ANALYSIS REPORT",
            "FOR-PIMP-04/02"=> "INFORME DE CARACTERIZACIÓN DE MATERIALES MEDIANTE LA TÉCNICA DE ESPECTROMETRÍA DE EMISIÓN ÓPTICA (OES)",
            "FOR-PIMP-04/03"=> "INFORME DE CARACTERIZACIÓN DE MATERIALES MEDIANTE LA TÉCNICA DE FLUORESCENCIA DE RX (XRF)",
            "FOR-PIMP-05/01"=> "INFORME DE ANÁLISIS QUÍMICO MEDIANTE LA TÉCNICA DE ESPECTROMETRÍA DE EMISIÓN ÓPTICA (OES)",
            "FOR-PIMP-05_B/01"=> "INFORME DE ANÁLISIS QUÍMICO MEDIANTE LA TÉCNICA DE ESPECTROMETRÍA DE EMISIÓN ÓPTICA (OES)/CHEMICAL ANALYSIS REPORT USING THE OPTICAL EMISSION SPECTROMETRY TECHNIQUE (OES)",
            "FOR-PIMP-06_B/01"=> "INFORME DE ANÁLISIS QUÍMICO MEDIANTE LA TÉCNICA DE FLUORESCENCIA DE RAYOS X (XRF)/CHEMICALS ANALYSIS REPORT USING THE X-RAY FLUORESCENSE TECHNIQUE (XRF",
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

        //Equipos
        $idsGeneral_EyCs_Equipos = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','EQUIPOS')->get();
        //Herramientas
        $idsGeneral_EyCs_Herramientas = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','HERRAMIENTAS')->get();
        //Accesorios
        $idsGeneral_EyCs_Accesorios = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','ACCESORIOS')->get();
        //Block y Probeta
        $idsGeneral_EyCs_BlockyProbeta = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','BLOCK Y PROBETA')->get();
        //Consumibles
        $idsGeneral_EyCs_Consumibles = general_eyc::with('almacen')->whereIn('idGeneral_EyC', $idGeneral_EyCs)->where('Tipo','CONSUMIBLES')->get();

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
        $idProcedimiento = $formato->idProcedimiento;
        $Procedimiento = Procedimiento::where('idProcedimiento', $idProcedimiento)->first();

        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Clientes = clientes::where('Cliente', '!=', 'POR DEFINIR')->get();

        // Obtén todos los usuario que tengan el rol Técnico
        $Tecnicos = Usuario::where('rol', 'Técnicos')->where('Estatus', 'Alta')->get();
        
        $NormasIM = Normas_IM::orderBy('Nombre_Espe')->orderBy('Variable')->get()->map(function ($norma) {
            return [
                'idnormas_im' => $norma->idnormas_im,
                'Nombre_Espe' => $norma->Nombre_Espe,
                'Variable' => $norma->Variable,
                'Tabla' => json_decode($norma->Tabla, true) ?: [],
                'Observaciones' => $norma->Observaciones,
            ];
        })->values();

        // Las rutas públicas se usan solo en la vista previa; el servidor vuelve a resolver el ID al guardar.
        $PatronesGranoIM = $this->catalogoPatronesGranoIM($Nombre_Formato);
        $CatalogosDureza0204 = $this->catalogosDureza0204($Nombre_Formato, (int) $idFormato);
        $MaterialesDureza0204 = $CatalogosDureza0204['materiales'];
        $EscalasDureza0204 = $CatalogosDureza0204['escalas'];
        $CatalogosMetalografiaIM = $this->catalogosMetalografiaIM($Nombre_Formato);

        return view("Reportes.Principal.Master", compact('Nombre_Formato','idPrueba_Aplica','Prueba','formatoNombrePersonalizado','idSolicitud','Solicitud','DetallesSolicitud','idsGeneral_EyCs_Equipos','idsGeneral_EyCs_Herramientas','idsGeneral_EyCs_Accesorios','idsGeneral_EyCs_BlockyProbeta','idsGeneral_EyCs_Consumibles','Clientes','Tecnicos','Procedimiento','NormasIM','PatronesGranoIM','MaterialesDureza0204','EscalasDureza0204','CatalogosMetalografiaIM'));
    }

    public function indexINS2(Request $request)
    {
        $contratoSeleccionado = $request->input('contratoSeleccionado');
        $Proyecto = $request->input('Proyecto');

        $reportesEncontrados = reporte::whereJsonContains('Detalles_Generales->Contrato', $contratoSeleccionado)->get();
        // Obtener solo las rutas de los archivos firmados
        /*$Rerpote_Firmado = $reportesEncontrados->map(function ($reporte) {
            $detalles = json_decode($reporte->Detalles_Generales, true);
            return $detalles['Reporte_Firmado'] ?? null;
        })->filter(); // filter() elimina los valores nulos*/

        if ($reportesEncontrados->isNotEmpty()) {
            return view('Reportes.INS.Index.indexINS2', compact('reportesEncontrados', 'contratoSeleccionado', 'Proyecto'));
        } else {
            return redirect()->route('indexINS1');
        }
    }

    public function VerPdfQR($token)
    {
        // Buscar todos los reportes
        $reportes = reporte::all();

        $datosEquipoEncontrado = null;
        $reporteEncontrado = null;

        foreach ($reportes as $reporte) {

            $datosEquipo = json_decode(
                $reporte->Datos_Equipo,
                true
            );

            // Comparar token
            if (
                !empty($datosEquipo['QR_TOKEN']) &&
                $datosEquipo['QR_TOKEN'] === $token
            ) {

                $datosEquipoEncontrado = $datosEquipo;
                $reporteEncontrado = $reporte;
                break;
            }
        }

        // Si no existe
        if (!$datosEquipoEncontrado) {
            abort(404, 'Reporte no encontrado');
        }

        if (
            !empty($reporteEncontrado) &&
            (
                empty($datosEquipoEncontrado['PDF_UNIFICADO']) ||
                !file_exists(
                    storage_path(
                        'app/public/' .
                        str_replace('storage/', '', $datosEquipoEncontrado['PDF_UNIFICADO'])
                    )
                )
            )
        ) {
            $pruebaAplica = Prueba_Aplica::where('idPrueba_Aplica', $reporteEncontrado->idPrueba_Aplica)->first();
            $formatoActual = $pruebaAplica
                ? formato::where('idFormato', $pruebaAplica->idFormato)->value('Nombre')
                : null;

            if ($formatoActual === 'FOR-PINS-16-01') {
                app(\App\Http\Controllers\Reporte\PINS\FOR_PINS_16_01Controller::class)->FOR_PINS_16_01($reporteEncontrado->idReportes);
            }

            if ($formatoActual === 'FOR-PINS-25-01') {
                app(\App\Http\Controllers\Reporte\PINS\FOR_PINS_25_01Controller::class)->FOR_PINS_25_01($reporteEncontrado->idReportes);
            }

            $datosEquipoEncontrado = json_decode($reporteEncontrado->fresh()->Datos_Equipo, true) ?: [];
        }

        // Validar PDF
        if (empty($datosEquipoEncontrado['PDF_UNIFICADO'])) {
            abort(404, 'PDF no encontrado');
        }

        /*
        |--------------------------------------------------------------------------
        | RUTA REAL DEL PDF
        |--------------------------------------------------------------------------
        */

        $rutaPdf = storage_path(
            'app/public/' .
            str_replace(
                'storage/',
                '',
                $datosEquipoEncontrado['PDF_UNIFICADO']
            )
        );

        // Verificar existencia
        if (!file_exists($rutaPdf)) {
            abort(404, 'Archivo no existe');
        }

        /*
        |--------------------------------------------------------------------------
        | MOSTRAR PDF
        |--------------------------------------------------------------------------
        */

        return response()->file(
            $rutaPdf,
            [
                'Content-Type' => 'application/pdf'
            ]
        );
    }
    
    public function ObtenerRutaPDF($id)
    {
        $Reporte = reporte::where('idReportes',$id)->first();
        $idPrueba_Aplica = $Reporte->idPrueba_Aplica;

        $Prueba_Aplica = Prueba_Aplica::where('idPrueba_Aplica',$idPrueba_Aplica)->first();
        $idFormato = $Prueba_Aplica->idFormato;

        $Formato = formato::where('idFormato',$idFormato)->first();
        $Nombre_Formato = $Formato->Nombre;

        if($Nombre_Formato == "FOR-PINS-04-01")
        {
            return redirect()->route('Reporte_FOR_PINS_04_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-05-01")
        {
            return redirect()->route('Reporte_FOR_PINS_05_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-06-01")
        {
            return redirect()->route('Reporte_FOR_PINS_06_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-07-01")
        {
            return redirect()->route('Reporte_FOR_PINS_07_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-08-01")
        {
            return redirect()->route('Reporte_FOR_PINS_08_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-09-01")
        {
            return redirect()->route('Reporte_FOR_PINS_09_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-10-01")
        {
            return redirect()->route('Reporte_FOR_PINS_10_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-11-01")
        {
            return redirect()->route('Reporte_FOR_PINS_11_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-12-01")
        {
            return redirect()->route('Reporte_FOR_PINS_12_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-13-01")
        {
            return redirect()->route('Reporte_FOR_PINS_13_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-14-01")
        {
            return redirect()->route('Reporte_FOR_PINS_14_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-15-01")
        {
            return redirect()->route('Reporte_FOR_PINS_15_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-16-01")
        {
            return redirect()->route('Reporte_FOR_PINS_16_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-17-01")
        {
            return redirect()->route('Reporte_FOR_PINS_17_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-18-01")
        {
            return redirect()->route('Reporte_FOR_PINS_18_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-19-01")
        {
            return redirect()->route('Reporte_FOR_PINS_19_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-20-01")
        {
            return redirect()->route('Reporte_FOR_PINS_20_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-21-01")
        {
            return redirect()->route('Reporte_FOR_PINS_21_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-22-01")
        {
            return redirect()->route('Reporte_FOR_PINS_22_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-23-01")
        {
            return redirect()->route('Reporte_FOR_PINS_23_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-24-01")
        {
            return redirect()->route('Reporte_FOR_PINS_24_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-25-01")
        {
            return redirect()->route('Reporte_FOR_PINS_25_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-03-02")
        {
            return redirect()->route('Reporte_FOR_PINS_03_02.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-05-02")
        {
            return redirect()->route('Reporte_FOR_PINS_05_02.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-11-02")
        {
            return redirect()->route('Reporte_FOR_PINS_11_02.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PINS-17-01_01")
        {
            return redirect()->route('Reporte_FOR_PINS_17_01_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-03-PRO-INS-15")
        {
            return redirect()->route('Reporte_FOR_03_INS_15.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PIMP-02_B/03")
        {
            return redirect()->route('Reporte_FOR_PIMP_02_B_03.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PIMP-02_B/04")
        {
            return redirect()->route('Reporte_FOR_PIMP_02_B_04.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PIMP-03_B/01")
        {
            return redirect()->route('procesamientos.pdf.pagina', ['reporte' => $id, 'formato' => '03_B_01']);
        }
        elseif($Nombre_Formato == "FOR-PIMP-04/02")
        {
            return redirect()->route('procesamientos.pdf.pagina', ['reporte' => $id, 'formato' => '04_02']);
        }
        elseif($Nombre_Formato == "FOR-PIMP-04/03")
        {
            return redirect()->route('procesamientos.pdf.pagina', ['reporte' => $id, 'formato' => '04_03']);
        }
        elseif($Nombre_Formato == "FOR-PIMP-05/01")
        {
            return redirect()->route('Reporte_FOR_PIMP_05_01.PDF', ['id' => $id]);
        }
        elseif($Nombre_Formato == "FOR-PIMP-05_B/01")
        {
            return redirect()->route('procesamientos.pdf.pagina', ['reporte' => $id, 'formato' => '05_B_01']);
        }
        elseif($Nombre_Formato == "FOR-PIMP-06_B/01")
        {
            return redirect()->route('procesamientos.pdf.pagina', ['reporte' => $id, 'formato' => '06_B_01']);
        }
        elseif($Nombre_Formato == "FOR-PIMP-07_B/01")
        {
            return redirect()->route('Reporte_FOR_PIMP_07_B_01.PDF', ['id' => $id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyReportes($id)
    {
        //Primero Eliminar el registro de la tabla 'lineal_ideal'
        $Lineal_Ideal  = Lineal_Ideal::where('idReportes', $id)->first();
        if ($Lineal_Ideal) {
            $Lineal_Ideal->delete();
        }

        //Segundo Eliminar el registro de la tabla 'Firma_Reporte'
        $Firma_Reporte  = Firma_Reporte::where('idReportes', $id)->first();
        if ($Firma_Reporte) {
            $Firma_Reporte->delete();
        }

        //Tercero Eliminar el registro de la tabla 'Fotos_Reporte'
        $Fotos_Reporte = Fotos_Reporte::where('idReportes', $id)->first();

        if ($Fotos_Reporte) {

            // Convertir JSON a array
            $fotos = json_decode($Fotos_Reporte->Fotos_Reportes, true);

            if (is_array($fotos)) {

                foreach ($fotos as $foto) {

                    if (!empty($foto['ruta'])) {

                        // Quitar "storage/" porque Storage trabaja desde "public"
                        $ruta = str_replace('storage/', '', $foto['ruta']);

                        if (Storage::disk('public')->exists($ruta)) {
                            Storage::disk('public')->delete($ruta);
                        }
                    }
                }
            }

            // Eliminar registro de la BD
            $Fotos_Reporte->delete();
        }

        //Cuarto Eliminar el registro de la tabla 'Grupo_Juntas_Detalles_Re'
        $Grupo_Juntas_Detalles_Re  = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        if ($Grupo_Juntas_Detalles_Re) {
            $Grupo_Juntas_Detalles_Re->delete();
        }

        //Cuarto y ultimo Eliminar el registro de la tabla 'reporte'
        $reporte  = reporte::where('idReportes', $id)->first();
        if ($reporte) {
            $reporte->delete();
        }

        
        // âœ… Retornar respuesta JSON para el AJAX
        return response()->json([
            'success' => true,
            'message' => 'Reporte eliminado correctamente.'
        ]);

    }

    protected function generarNuevoNoReporte($numeroActual)
    {
        if (empty($numeroActual)) {
            return '001';
        }

        preg_match('/^(\d{3})-(.*)$/', $numeroActual, $matches);

        if ($matches) {
            $nuevoNumero = str_pad(((int)$matches[1]) + 1, 3, '0', STR_PAD_LEFT);
            return $nuevoNumero . '-' . $matches[2];
        }

        return '001-' . trim($numeroActual);
    }

    public function CrearNuevoReporteDesdeModal(Request $request, $id)
    {
        $request->validate([
            'Prueba' => 'required|integer',
            'NormaCodigo' => 'required|integer',
            'Formato' => 'required|integer',
        ]);

        $nuevoId = null;

        DB::transaction(function () use ($request, $id, &$nuevoId) {
            $ReporteOriginal = reporte::where('idReportes', $id)->firstOrFail();
            $NuevoReporte = $ReporteOriginal->replicate();

            $Detalles_Generales = json_decode($ReporteOriginal->Detalles_Generales, true) ?? [];
            $Detalles_Generales['No_Reporte'] = $this->generarNuevoNoReporte($Detalles_Generales['No_Reporte'] ?? '');
            $Detalles_Generales['Fecha'] = now()->format('Y-m-d');

            $idPrueba = (int)$request->input('Prueba');
            $idNorma = (int)$request->input('NormaCodigo');
            $idFormato = (int)$request->input('Formato');

            $Prueba_Aplica = Prueba_Aplica::firstOrCreate([
                'idPrueba' => $idPrueba,
                'idNorma_Codigo' => $idNorma,
                'idFormato' => $idFormato,
            ], [
                'idPrueba' => $idPrueba,
                'idNorma_Codigo' => $idNorma,
                'idFormato' => $idFormato,
            ]);

            $NuevoReporte->idPrueba_Aplica = $Prueba_Aplica->idPrueba_Aplica;
            $NuevoReporte->Detalles_Generales = json_encode($Detalles_Generales);
            $NuevoReporte->Estatus = 'CREADO';
            $NuevoReporte->save();

            $nuevoId = $NuevoReporte->idReportes;

            $linealIdealOriginal = Lineal_Ideal::where('idReportes', $id)->first();
            if ($linealIdealOriginal) {
                Lineal_Ideal::create([
                    'idOC' => $linealIdealOriginal->idOC,
                    'idOrden_Servicio' => $linealIdealOriginal->idOrden_Servicio,
                    'idSolicitud' => $linealIdealOriginal->idSolicitud,
                    'idReportes' => $nuevoId,
                    'Estatus' => 'CREADO',
                ]);
            }

            $FirmaOriginal = Firma_Reporte::where('idReportes', $id)->first();
            if ($FirmaOriginal) {
                $NuevaFirma = $FirmaOriginal->replicate();
                $NuevaFirma->idReportes = $nuevoId;
                $NuevaFirma->save();
            }

            Fotos_Reporte::create([
                'idReportes' => $nuevoId,
                'Fotos_Reportes' => json_encode([]),
            ]);

            Grupo_Juntas_Detalles_Re::create([
                'idReportes' => $nuevoId,
                'Juntas_Grupo_Re' => json_encode([]),
            ]);
        });

        return redirect()->route('Editar.Reporte', ['id' => $nuevoId]);
    }

    public function Next_Reporte($id)
    {
        $nuevoId = null;

        DB::transaction(function () use ($id, &$nuevoId) {

            // Obtener reporte original
            $ReporteOriginal = reporte::where('idReportes', $id)->firstOrFail();

            // Clonar reporte
            $NuevoReporte = $ReporteOriginal->replicate();

            // Decodificar JSON
            $Detalles_Generales = json_decode($ReporteOriginal->Detalles_Generales, true) ?? [];

            $nuevoNoReporte = $this->generarNuevoNoReporte($Detalles_Generales['No_Reporte'] ?? '');

            // Reemplazar valores
            $Detalles_Generales['No_Reporte'] = $nuevoNoReporte;
            $Detalles_Generales['Fecha'] = now()->format('Y-m-d');

            $NuevoReporte->Detalles_Generales = json_encode($Detalles_Generales);

            // En el consecutivo de durezas se conservan los promedios previos,
            // pero las mediciones posteriores deben iniciar en blanco.
            $pruebaAplicaOriginal = Prueba_Aplica::find($ReporteOriginal->idPrueba_Aplica);
            $nombreFormato = $pruebaAplicaOriginal
                ? formato::where('idFormato', $pruebaAplicaOriginal->idFormato)->value('Nombre')
                : null;

            if ($nombreFormato === 'FOR-PIMP-02_B/04') {
                $datosEquipo = json_decode($ReporteOriginal->Datos_Equipo, true) ?? [];
                $promedios = is_array($datosEquipo['DUREZA_PROMEDIO'] ?? null)
                    ? $datosEquipo['DUREZA_PROMEDIO']
                    : [];

                foreach (['DESPUES_A', 'DESPUES_B', 'DESPUES_C', 'DESPUES_B1', 'DESPUES_BM'] as $campo) {
                    // La segunda etapa inicia pendiente, pero nunca deja celdas vacias en el PDF.
                    $promedios[$campo] = '---';
                }

                $datosEquipo['DUREZA_PROMEDIO'] = $promedios;
                $datosEquipo['DUREZA_ROWS'] = [];
                $datosEquipo['DUREZA_MERGE_CONFIG'] = [];
                $datosEquipo['DUREZA_ETAPA'] = 'DESPUES';
                $NuevoReporte->Datos_Equipo = json_encode($datosEquipo);
            }

            $NuevoReporte->Estatus = 'CREADO';

            // Guardar nuevo reporte
            $NuevoReporte->save();

            $nuevoId = $NuevoReporte->idReportes;

            // =====================================
            // BUSCAR RELACIÓN EN LINEAL_IDEAL
            // =====================================

            $linealIdealOriginal = Lineal_Ideal::where('idReportes', $id)->first();

            if ($linealIdealOriginal) {
                Lineal_Ideal::create([
                    'idOC' => $linealIdealOriginal->idOC,
                    'idOrden_Servicio' => $linealIdealOriginal->idOrden_Servicio,
                    'idSolicitud' => $linealIdealOriginal->idSolicitud,
                    'idReportes' => $nuevoId,
                    'Estatus' => 'CREADO',
                ]);
            }

            // =====================================
            // CLONAR FIRMAS
            // =====================================

            $FirmaOriginal = Firma_Reporte::where('idReportes', $id)->first();

            if ($FirmaOriginal) {
                $NuevaFirma = $FirmaOriginal->replicate();
                $NuevaFirma->idReportes = $nuevoId;
                $NuevaFirma->save();
            }

            // =====================================
            //  CREAR FOTOS VACÍAS
            // =====================================

            Fotos_Reporte::create([
                'idReportes' => $nuevoId,
                'Fotos_Reportes' => json_encode([]),
            ]);

            // =====================================
            //  CREAR JUNTAS VACÍAS
            // =====================================

            Grupo_Juntas_Detalles_Re::create([
                'idReportes' => $nuevoId,
                'Juntas_Grupo_Re' => json_encode([]),
            ]);
        });

        return redirect()->route('Editar.Reporte', ['id' => $nuevoId]);
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


}
