<?php

namespace App\Http\Controllers\Reporte\PINS;

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
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\OrdenServicio\Orden_Servicio;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\OrdenServicio\Orden_Servicio_Prueba;
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Support\Reportes\PinsEquiposQrSupport;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;

class FOR_PINS_25_01Controller extends Controller
{
    // Sanea la configuracion enviada desde la tabla con combinacion de celdas.
    private function sanitizarConfiguracionCombinacionTabla($configuracionCruda)
    {
        $configuracion = is_string($configuracionCruda)
            ? json_decode($configuracionCruda, true)
            : $configuracionCruda;

        if (!is_array($configuracion)) {
            return [];
        }

        return collect($configuracion)
            ->take(500)
            ->filter(function ($item) {
                return is_array($item)
                    && !empty($item['field'])
                    && array_key_exists('startRow', $item)
                    && array_key_exists('rowspan', $item);
            })
            ->map(function ($item) {
                return [
                    'groupId' => !empty($item['groupId']) ? (string) $item['groupId'] : 'sin_titulo',
                    'field' => (string) $item['field'],
                    'startRow' => max(0, (int) $item['startRow']),
                    // Evita valores manipulados capaces de generar rowspans desmedidos.
                    'rowspan' => min(1000, max(2, (int) $item['rowspan'])),
                ];
            })
            ->unique(function ($item) {
                return $item['groupId'] . '|' . $item['field'] . '|' . $item['startRow'];
            })
            ->values()
            ->all();
    }

    private function prepararDatosEquipoReporte25(array $datosEquipo, $idSolicitud, array $datosEquipoActuales = [], $contrato = '', $noReporte = ''): array
    {
        $catalogo = PinsEquiposQrSupport::obtenerCatalogoEquiposHerramientasPorSolicitud($idSolicitud);
        $datosEquipo = PinsEquiposQrSupport::prepararDatosEquipoSeleccionados(
            $datosEquipo,
            $catalogo,
            $datosEquipo['EQUIPOS_HERRAMIENTAS_IDS'] ?? []
        );

        $datosEquipo['QR_TOKEN'] = $datosEquipo['QR_TOKEN']
            ?? ($datosEquipoActuales['QR_TOKEN'] ?? (string) Str::uuid());

        $qrAnterior = $datosEquipoActuales['QR_PDF'] ?? null;
        $datosEquipo['QR_PDF'] = PinsEquiposQrSupport::generarQrPublico(
            'FOR_PINS_25_01',
            $contrato,
            $noReporte,
            $datosEquipo['QR_TOKEN']
        );

        if ($qrAnterior && $qrAnterior !== $datosEquipo['QR_PDF']) {
            PinsEquiposQrSupport::eliminarArchivoPublico($qrAnterior);
        }

        $datosEquipo['PDF_UNIFICADO'] = $datosEquipoActuales['PDF_UNIFICADO'] ?? null;

        return $datosEquipo;
    }

    private function invalidarPdfCacheReporte25(array &$datosEquipo): void
    {
        PinsEquiposQrSupport::eliminarArchivoPublico($datosEquipo['PDF_UNIFICADO'] ?? null);
        $datosEquipo['PDF_UNIFICADO'] = null;
    }

    private function obtenerRespuestaPdfCacheadoReporte25($id)
    {
        $reporte = reporte::where('idReportes', $id)->first();

        if (!$reporte) {
            return null;
        }

        $datosEquipo = json_decode($reporte->Datos_Equipo, true) ?: [];
        $rutaPublica = $datosEquipo['PDF_UNIFICADO'] ?? null;

        if (!PinsEquiposQrSupport::existeArchivoPublico($rutaPublica)) {
            return null;
        }

        $rutaAbsoluta = PinsEquiposQrSupport::resolverRutaPublicaAbsoluta($rutaPublica);

        if (!$rutaAbsoluta || !File::exists($rutaAbsoluta)) {
            return null;
        }

        return response(File::get($rutaAbsoluta), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01.PDF"');
    }

    private function guardarPdfCacheadoReporte25($id, $pdfOutput, array $detallesGenerales, array $datosEquipo): void
    {
        $rutaPublica = PinsEquiposQrSupport::guardarPdfCacheado(
            $pdfOutput,
            'FOR_PINS_25_01',
            $detallesGenerales['Contrato'] ?? '',
            $detallesGenerales['No_Reporte'] ?? ''
        );

        $datosEquipo['PDF_UNIFICADO'] = $rutaPublica;

        reporte::where('idReportes', $id)->update([
            'Datos_Equipo' => json_encode($datosEquipo),
        ]);
    }


    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'];
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        //$Contrato = trim(strtoupper($datosParaCrearOS_OC['Contrato']));
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'];
        $Pieza = $datosParaCrearOS_OC['Pieza'];
        $Norma_cod_Criterio_Eva = $datosParaCrearOS_OC['Norma_cod_Criterio_Eva'];
        $ResultadosJuntas = $datosParaCrearOS_OC['ResultadosJuntas'];
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];

        $EsperaDato = "ESPERA DE DATOS";
        /*Instancias */
        $Orden_Servicio = new Orden_Servicio;
        $Orden_Servicio_Prueba = new Orden_Servicio_Prueba;
        $Firmantes_OS = new Firmantes_OS;
        $Grupo_Juntas_Detalles_OS = new Grupo_Juntas_Detalles_OS;
        $OC = new OC;
        $Detalles_OC = new detallesOC;
        $Lineal_Ideal = new Lineal_Ideal;

        $BusquedaCliente = clientes::where('Cliente', 'like', '%' . $Cliente . '%')->first();

        if ($BusquedaCliente) {
            $idCliente = $BusquedaCliente->idClientes; // O el campo que sea clave primaria
            //$nombreReal = $BusquedaCliente->Cliente; // Nombre exacto encontrado
            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            $Orden_Servicio->idClientes = $idCliente;
            $Orden_Servicio->Fecha = '2001-01-01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001-01-01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }
            
            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();

        } else {
            // Cliente no encontrado
                $NewCliente = new clientes();
                $NewCliente->Cliente = $Cliente;
                $NewCliente->RFC = $EsperaDato;
                $NewCliente->Telefono = $EsperaDato;
                $NewCliente->Correo = $EsperaDato;
                $NewCliente->save();
            //}

            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            // Obtén el ID del cliente "POR DEFINIR"
            $idClientes = $NewCliente->idClientes;
            $Orden_Servicio->idClientes = $idClientes;
            $Orden_Servicio->Fecha = '2001-01-01';
            $Orden_Servicio->Lugar = $Lugar;
            $Orden_Servicio->Contrato = $Contrato;
            $Orden_Servicio->Proyecto_actividad = $Proyecto;
            $Orden_Servicio->Material = $Material;
            $Orden_Servicio->Plano_isometrico = $Isometrico_Plano;
            $Orden_Servicio->save();

            // Obtén el ID del registro recién creado
            $idOrdenServicio = $Orden_Servicio->idOrden_Servicio;

            $Orden_Servicio_Prueba->idOrden_Servicio = $idOrdenServicio;
            $Orden_Servicio_Prueba->idPrueba_Aplica = $idPrueba_Aplica;
            $Orden_Servicio_Prueba->save();

            $Firmantes_OS->idOrden_Servicio = $idOrdenServicio;
            $Firmantes_OS->Nombre_Cargo = '[]';
            $Firmantes_OS->save();

            $Grupo_Juntas_Detalles_OS->idOrden_Servicio = $idOrdenServicio;
            $Grupo_Juntas_Detalles_OS->Juntas_grupo = $ResultadosJuntas;
            $Grupo_Juntas_Detalles_OS->save();

            }

            $BusquedaContratoOC = OC::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOC)
            {
                $idOC = $BusquedaContratoOC->idOC;
            } else{
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001-01-01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = $EsperaDato;
            $Detalles_OC->save();
            }

            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();
        }

    }
    private function filasPorHojaFormatoPrincipal(Request $request): int
    {
        $requiereEquipos = strtolower((string) $request->input('Datos_Equipo.REQUIERE_EQUIPOS', 'no'));
        $equiposSeleccionados = $request->input('Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS', []);

        return in_array($requiereEquipos, ['si', 'sí'], true)
            && is_array($equiposSeleccionados)
            && !empty($equiposSeleccionados)
                ? 15
                : 20;
    }

    private function procesarBloques(Request $request)
    {
        $titulosJson = $request->input('componentes_titulos_data', '[]');
        $titulos = json_decode($titulosJson, true) ?: [];
        $bloques = [];
        $bloqueActual = [];
        $contador = 0;
        // El formato complementario 25_01_01 siempre muestra 30 filas por hoja,
        // independientemente de que el reporte tenga equipos seleccionados.
        $maxFilasPorBloque = 30;

        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque, $cerrarBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $cerrarBloque();
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };

        $procesarFilas = function ($tituloKey) use ($request, $agregarElemento) {
            $filas = $request->input("Componentes_ID.$tituloKey", []);

            for ($i = 0; $i < count($filas); $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                        'ID' => $request->input("Componentes_ID.$tituloKey.$i"),
                        'Descripcion_del_Elemento' => $request->input("Componentes_Descripcion_del_Elemento.$tituloKey.$i"),
                        '0' => $request->input("Componentes_0.$tituloKey.$i"),
                        'Longitud_(in)' => $request->input("Componentes_Longitud_in.$tituloKey.$i"),
                        'Tipo_conexion' => $request->input("Componentes_Tipo_conexion.$tituloKey.$i"),
                        'servicio' => $request->input("Componentes_Servicio.$tituloKey.$i"),
                        'Clase' => $request->input("Componentes_Clase.$tituloKey.$i"),
                        'Especificación_material' => $request->input("Componentes_Especificacion_material.$tituloKey.$i"),
                        'Observaciones' => $request->input("Componentes_Observaciones.$tituloKey.$i"),
                    ],
                ]);
            }
        };

        $procesarFilas('sin_titulo');

        foreach ($titulos as $tituloObj) {
            $tituloKey = $tituloObj['id'] ?? null;

            if (!$tituloKey) {
                continue;
            }

            $agregarElemento([
                'tipo' => 'titulo',
                'id' => $tituloKey,
                'texto' => $tituloObj['text'] ?? '',
            ]);

            $procesarFilas($tituloKey);
        }

        $cerrarBloque();

        return $bloques;
    }

    private function contarTitulosYFilasReporte(array $bloques)
    {
        $totalTitulos = 0;
        $totalFilas = 0;

        foreach ($bloques as $bloque) {
            foreach ($bloque as $item) {
                if (($item['tipo'] ?? '') === 'titulo') {
                    $totalTitulos++;
                }

                if (($item['tipo'] ?? '') === 'fila') {
                    $totalFilas++;
                }
            }
        }

        return $totalTitulos + $totalFilas;
    }

    private function obtenerFotosParaPdf($fotosReporte)
    {
        if (!$fotosReporte) {
            return [];
        }

        $fotos = json_decode($fotosReporte->Fotos_Reportes, true) ?: [];

        return collect($fotos)->map(function ($foto) {
            return [
                'path' => storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'] ?? '')),
                'comment' => $foto['comentario'] ?? '',
                'una_hoja' => $foto['una_hoja'] ?? 0,
            ];
        })->all();
    }

    private function cargarContextoReportePdf25($id)
    {
        $reporte = reporte::where('idReportes', $id)->first();
        $grupoJuntas = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $firmasReporte = Firma_Reporte::where('idReportes', $id)->first();
        $fotosReporte = Fotos_Reporte::where('idReportes', $id)->first();

        if (!$reporte || !$grupoJuntas || !$firmasReporte) {
            Log::warning('No se encontraron datos completos para generar el PDF', ['id' => $id]);
            abort(404, 'No se encontrÃ³ la informaciÃ³n del reporte.');
        }

        $detallesGenerales = json_decode($reporte->Detalles_Generales, true) ?: [];
        $datosEquipo = json_decode($reporte->Datos_Equipo, true) ?: [];
        $catalogoEquiposHerramientas = PinsEquiposQrSupport::obtenerCatalogoEquiposHerramientasPorSolicitud($detallesGenerales['idSolicitud'] ?? null);
        $datosEquipo = PinsEquiposQrSupport::normalizarDatosEquipoSeleccionadosExistentes($datosEquipo, $catalogoEquiposHerramientas);
        $tablaCombinacionConfig = json_decode($datosEquipo['TABLA_COMBINACION_CONFIG'] ?? '[]', true) ?: [];
        $tablaCombinacionConfigComponentes = json_decode($datosEquipo['TABLA_COMBINACION_CONFIG_COMPONENTES'] ?? '[]', true) ?: [];
        $juntasGrupoRe = json_decode($grupoJuntas->Juntas_Grupo_Re, true) ?: [];
        $componentesDetalles = $juntasGrupoRe['componentes'] ?? [];
        $inspeccionDetalles = $juntasGrupoRe['inspeccion'] ?? $juntasGrupoRe;
        $firmas = json_decode($firmasReporte->Firmas, true) ?: [];
        $fotos = $this->obtenerFotosParaPdf($fotosReporte);
        $qrPdf = !empty($datosEquipo['QR_PDF']) ? PinsEquiposQrSupport::resolverRutaPublicaAbsoluta($datosEquipo['QR_PDF']) : null;

        return [
            'Detalles_Generales' => $detallesGenerales,
            'Datos_Equipo' => $datosEquipo,
            'QR_PDF' => $qrPdf,
            'tablaCombinacionConfig' => $tablaCombinacionConfig,
            'tablaCombinacionConfigComponentes' => $tablaCombinacionConfigComponentes,
            'Componentes_Detalles_Re' => $componentesDetalles,
            'Grupo_Juntas_Detalles_Re' => $inspeccionDetalles,
            'Firmas_Reportes' => $firmas,
            'numFirmas' => $firmas['numFirmas'] ?? 0,
            'Fotos' => $fotos,
            'totalFotos' => count($fotos),
            'totalTitulosYFilas' => $this->contarTitulosYFilasReporte($inspeccionDetalles),
            'Logo' => public_path('images/Logo_AICO_R.jpg'),
        ];
    }

    private function renderizarPdfContenido($view, array $data, $paper, $orientation, $logMessage = null, $t0 = null)
    {
        $pdfContent = PDF::loadView($view, $data)
            ->setPaper($paper, $orientation)
            ->output();

        if ($logMessage !== null && $t0 !== null) {
            Log::info($logMessage, ['segundos' => round(microtime(true) - $t0, 2)]);
        }

        return $pdfContent;
    }

    private function combinarDocumentosPdf25($pdf0Content, $pdf1Content, $pdf2Content)
    {
        $combinedPdf = new Fpdi();

        $pageCount0 = 0;
        $pageCount1 = 0;
        $pageCount2 = 0;

        $pageCount0 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf0Content));
        $pageCount1 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        $pageCount2 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        $totalPageCount = $pageCount0 + $pageCount1 + $pageCount2;

        $pageCount0 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf0Content));
        for ($i = 1; $i <= $pageCount0; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(119, -266.5);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        $pageCount1 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('L');
            $combinedPdf->useTemplate($tplId, 0, 0, 297, 210);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(180, -182.5);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        $pageCount2 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(138.5, -266.5);
            $combinedPdf->Cell(0, 10, ($i + $pageCount1) . " de $totalPageCount", 0, 0, 'C');
        }

        return $combinedPdf;
    }

    /*public function FOR_PINS_25_01_store1(Request $request)
    {
        // Verificar los datos recibidos antes de procesarlos
        dd($request->input('titulos', []), $request->all()); // Mostrar todos los datos que están llegando
    }*/
    public function FOR_PINS_25_01_store(Request $request)
    {
        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'nullable|string',
            'Detalles_Generales.Cliente' => 'nullable|string',
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string',
            'Detalles_Generales.Folio' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            'Detalles_Generales.Lugar' => 'nullable|string',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string',
            'Detalles_Generales.Pieza' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Iluminacion' => 'nullable|string',
            'Detalles_Generales.Inspeccion' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',

            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.REQUIERE_EQUIPOS' => 'nullable|string|in:si,no',
            'Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS' => 'nullable|array',
            'Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS.*' => 'nullable|integer',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            /*Titulos Juntas */
            //'titulos' => 'nullable|array',  // Asegura que sea un array
            //'titulos.*' => 'string',  // Cada título debe ser un string válido
            'componentes_titulos_data' => 'nullable|string',
            'Tabla_CombinacionConfig_Componentes' => 'nullable|string',

            /*Resultados_Juntas*/
            'titulos_data' => 'nullable|string', // JSON con [{id,text},...]
            'ID' => 'nullable|array',
            'Descripcion_del_Elemento' => 'nullable|array',
            '0_nom' => 'nullable|array',
            'Tipo_material' => 'nullable|array',
            'Descripcion_discontinuidad' => 'nullable|array',
            'No_indicacion' => 'nullable|array',
            'LA' => 'nullable|array',
            'LC' => 'nullable|array',
            'd' => 'nullable|array',
            'ta' => 'nullable|array',
            't_h' => 'nullable|array',
            'Referencia' => 'nullable|array',
            'Dictamen' => 'nullable|array',
            'No_foto' => 'nullable|array',

            'Componentes_ID' => 'nullable|array',
            'Componentes_Descripcion_del_Elemento' => 'nullable|array',
            'Componentes_0' => 'nullable|array',
            'Componentes_Longitud_in' => 'nullable|array',
            'Componentes_Tipo_conexion' => 'nullable|array',
            'Componentes_Servicio' => 'nullable|array',           
            'Componentes_Clase' => 'nullable|array',
            'Componentes_Especificacion_material' => 'nullable|array',
            'Componentes_Observaciones' => 'nullable|array',


            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',
            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string',
            'Firmas_Reportes3.Vobo1' => 'nullable|string',
            'Firmas_Reportes3.Vobo2' => 'nullable|string',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string',
            'Firmas_Reportes4.Vobo1' => 'nullable|string',
            'Firmas_Reportes4.Vobo2' => 'nullable|string',
            'Firmas_Reportes4.Vobo3' => 'nullable|string',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string',
        ]);

        //En la validación de Laravel, nullable significa que el campo puede estar vacío (nulo) 
        // y no se aplicarán las demás reglas de validación si el campo está vacío. Esto es útil 
        // cuando tienes campos opcionales en tu formulario.

        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos
        $idPrueba_Aplica = $request->input('idPrueba_Aplica');

        $Reportes->idPrueba_Aplica = $idPrueba_Aplica;

        // Lógica para manejar Cliente
        if ($request->TieneCliente === 'si') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteSelect;
        } else {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteInput;
        }
        // Lógica para manejar el campo Contrato
        if ($request->TieneContrato === "no") {

            // Si el usuario alteró el valor o no llegó, se recalcula en backend
            $actual = $request->Detalles_Generales['Contrato'] ?? null;

            // Verificar que realmente tenga el formato correcto
            if (!$actual || !preg_match('/^AICO-INT-\d{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                $registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;

                foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) $ultimoNumero = $n;
                        break;
                    }
                }

                $nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $validatedData['Detalles_Generales']['Contrato'] = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $validatedData['Detalles_Generales']['Contrato'] = $actual;
            }
        }
        //$Reportes->Contrato = json_encode($validatedData['Detalles_Generales']['Contrato']); //Fila Contrato en la Tabla Reportes, Borrar por si acaso
        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
        );
        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG_COMPONENTES'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig_Componentes', '[]')),
            JSON_UNESCAPED_UNICODE
        );
        $validatedData['Datos_Equipo'] = $this->prepararDatosEquipoReporte25(
            $validatedData['Datos_Equipo'],
            $validatedData['Detalles_Generales']['idSolicitud'] ?? null,
            [],
            $validatedData['Detalles_Generales']['Contrato'] ?? '',
            $validatedData['Detalles_Generales']['No_Reporte'] ?? ''
        );
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = null;

        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("ID.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = $this->filasPorHojaFormatoPrincipal($request);

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };

        /*
        |--------------------------------------------------------------------------
        | 1. BLOQUE SIN TITULO
        |--------------------------------------------------------------------------
        */
                $longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
                // Debe coincidir con verificarYAgregarLongitud() del JS: inserta una longitud cada 15 filas
                $filasPorLongitud = $maxFilasPorBloque;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$sinTituloKey.$i"),
                    'Descripcion_del_Elemento' => $request->input("Descripcion_del_Elemento.$sinTituloKey.$i"),
                    '0_nom' => $request->input("0_nom.$sinTituloKey.$i"),
                    'Tipo_material' => $request->input("Tipo_material.$sinTituloKey.$i"),
                    'Descripcion_discontinuidad' => $request->input("Descripcion_discontinuidad.$sinTituloKey.$i"),
                    'No_indicacion' => $request->input("No_indicacion.$sinTituloKey.$i"),
                    'LA' => $request->input("LA.$sinTituloKey.$i"),
                    'LC' => $request->input("LC.$sinTituloKey.$i"),
                    'd' => $request->input("d.$sinTituloKey.$i"),
                    'ta' => $request->input("ta.$sinTituloKey.$i"),
                    't_h' => $request->input("t_h.$sinTituloKey.$i"),
                    'Referencia' => $request->input("Referencia.$sinTituloKey.$i"),
                    'Dictamen' => $request->input("Dictamen.$sinTituloKey.$i"),
                    'No_foto' => $request->input("No_foto.$sinTituloKey.$i"),
                    ]
                    ]);
                    // Cada 15 filas, intercalar la longitud correspondiente (replica el orden del DOM)
                    if (($i + 1) % $filasPorLongitud === 0) {
                        $idxLong = intdiv($i, $filasPorLongitud);
                        if (isset($longitudesSin[$idxLong])) {
                            $agregarElemento([
                                'tipo' => 'longitud',
                                'grupo' => $sinTituloKey,
                                'valor' => $longitudesSin[$idxLong]
                            ]);
                            $cerrarBloque();
                        }
                    }
                }

                // Longitudes restantes (si el usuario agregó longitudes manuales extra o el último bloque tiene <15 filas)
                $longsUsadas = intdiv($numFilasSin, $filasPorLongitud);
                $totalLongs = count($longitudesSin);
                for ($j = $longsUsadas; $j < $totalLongs; $j++) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $sinTituloKey,
                        'valor' => $longitudesSin[$j]
                    ]);
                    $cerrarBloque();
                }
            
        //
        

        /*
        |--------------------------------------------------------------------------
        | 2. TITULOS + FILAS + LONGITUDES
        |--------------------------------------------------------------------------
        */

        foreach ($titulos as $tituloObj) {
            $tituloKey = $tituloObj['id'];   // ej. "titulo_1"
            $tituloText = $tituloObj['text']; // texto real

            // agregar título
            $agregarElemento([
                'tipo' => 'titulo',
                'grupo' => $tituloKey,
                'texto' => $tituloText
            ]);

            $filas = $request->input("ID.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$tituloKey.$i"),
                    'Descripcion_del_Elemento' => $request->input("Descripcion_del_Elemento.$tituloKey.$i"),
                    '0_nom' => $request->input("0_nom.$tituloKey.$i"),
                    'Tipo_material' => $request->input("Tipo_material.$tituloKey.$i"),
                    'Descripcion_discontinuidad' => $request->input("Descripcion_discontinuidad.$tituloKey.$i"),
                    'No_indicacion' => $request->input("No_indicacion.$tituloKey.$i"),
                    'LA' => $request->input("LA.$tituloKey.$i"),
                    'LC' => $request->input("LC.$tituloKey.$i"),
                    'd' => $request->input("d.$tituloKey.$i"),
                    'ta' => $request->input("ta.$tituloKey.$i"),
                    't_h' => $request->input("t_h.$tituloKey.$i"),
                    'Referencia' => $request->input("Referencia.$tituloKey.$i"),
                    'Dictamen' => $request->input("Dictamen.$tituloKey.$i"),
                    'No_foto' => $request->input("No_foto.$tituloKey.$i"),
                    ]
                ]);
            }

            // Obtener longitud inspeccionada asociada a este título (si existe)
            $longitudes = $request->input("Long_Inspecc.$tituloKey", []); //Agregar

                foreach ($longitudes as $long) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $tituloKey,
                        'valor' => $long
                    ]);

                    // cerrar bloque al encontrar longitud
                    $cerrarBloque();
                }
        }
        /*
        |--------------------------------------------------------------------------
        | 3. CERRAR SI QUEDAN ELEMENTOS
        |--------------------------------------------------------------------------
        */
        $cerrarBloque();
        /*
        |--------------------------------------------------------------------------
        | 4. GUARDAR
        |--------------------------------------------------------------------------
        */
        $componentesBloques = $this->procesarBloques($request);
        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode([
            'componentes' => $componentesBloques,
            'inspeccion' => $bloques,
        ], JSON_UNESCAPED_UNICODE);
        $Grupo_Juntas_Detalles_Re->save();
        
        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas_Reportes->Firmas = json_encode($validatedData['Firmas_Reportes1']);
        }
        else if ($numFirmas == 2) {
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

        /* Fotos y Comentarios */
        $imageCount = $request->input('imageCount'); // Número de imágenes
        if($imageCount>=1)
        {
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach ($request->images_base64 as $index => $base64Image) {
            $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
            $Contrato = $validatedData['Detalles_Generales']['Contrato'];

            // Decodificar Base64
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
            
            // Crear un nombre único para la imagen
            $imageName = 'imagen_' . time() . '_' . $index . '.png';

            // Definir la ruta personalizada
            $rutaCarpeta = "public/Reportes/FOR_PINS_25_01/{$Contrato}/{$No_Reporte}/Fotos"; /* Ruta personalizada CAMBIAR */
            
            // Guardar la imagen en la ruta personalizada
            Storage::put("{$rutaCarpeta}/{$imageName}", $image);

            // Guardar la ruta en el array con su comentario correspondiente
            $imagenesGuardadas[] = [
                'ruta' => "storage/Reportes/FOR_PINS_25_01/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}", /* Ruta personalizada CAMBIAR */
                'comentario' => $request->comments[$index] ?? null, // Guardar comentario si existe
                'una_hoja' => $request->imagen_hoja[$index] ?? 0, //  AQUÍ
            ];
        }

        // Convertir el array de fotos a JSON
        $Fotos = json_encode($imagenesGuardadas); 

        // Guardar en la base de datos
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }else{
        $imagenesGuardadas = [];
        $Fotos = json_encode($imagenesGuardadas);
        $Fotos = json_encode($imagenesGuardadas); 
        $Fotos_Reportes->idReportes = $idReportes;
        $Fotos_Reportes->Fotos_Reportes = $Fotos;
        $Fotos_Reportes->save();
    }

        $Cliente = $validatedData['Detalles_Generales']['Cliente'];
        $Lugar = $validatedData['Detalles_Generales']['Lugar'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $Isometrico_Plano = $validatedData['Detalles_Generales']['Isometrico_Plano'];
        $Pieza = $validatedData['Detalles_Generales']['Pieza'];
        $Norma_cod_Criterio_Eva = $validatedData['Detalles_Generales']['Criterio_Evaluacion'];

        $datosParaCrearOS_OC = [
            'idPrueba_Aplica' => $idPrueba_Aplica,
            'Cliente' => $Cliente,
            'Lugar' => $Lugar,
            'Contrato' => $Contrato,
            'Proyecto' => $Proyecto,
            'Material' => $Material,
            'Isometrico_Plano' => $Isometrico_Plano,
            'Pieza' => $Pieza,
            'ResultadosJuntas' => $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re,
            'Norma_cod_Criterio_Eva' => $Norma_cod_Criterio_Eva,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,
            
        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    /*public function FOR_PINS_25_01_update1(Request $request) 
    {
        // Verificar los datos recibidos antes de procesarlos
        dd($request->input('titulos', []), $request->all()); // Mostrar todos los datos que están llegando
    }*/

    public function FOR_PINS_25_01_update(Request $request, $id)
    {
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $validatedData = $request->validate([
            /*DETALLES GENERALES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.Fecha' => 'nullable|date',
            'Detalles_Generales.No_Reporte' => 'nullable|string',
            'Detalles_Generales.Cliente' => 'nullable|string',
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string',
            'Detalles_Generales.Folio' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            'Detalles_Generales.Lugar' => 'nullable|string',
            'Detalles_Generales.Isometrico_Plano' => 'nullable|string',
            'Detalles_Generales.Pieza' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Iluminacion' => 'nullable|string',
            'Detalles_Generales.Inspeccion' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',

            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.REQUIERE_EQUIPOS' => 'nullable|string|in:si,no',
            'Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS' => 'nullable|array',
            'Datos_Equipo.EQUIPOS_HERRAMIENTAS_IDS.*' => 'nullable|integer',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            /*Titulos Juntas */
            //'titulos' => 'nullable|array',  // Asegura que sea un array
            //'titulos.*' => 'string',  // Cada título debe ser un string válido

            /*Resultados_Juntas*/
            'componentes_titulos_data' => 'nullable|string',
            'Tabla_CombinacionConfig_Componentes' => 'nullable|string',
            'titulos_data' => 'nullable|string', // JSON con [{id,text},...]
            'ID' => 'nullable|array',
            'Descripcion_del_Elemento' => 'nullable|array',
            '0_nom' => 'nullable|array',
            'Tipo_material' => 'nullable|array',
            'Descripcion_discontinuidad' => 'nullable|array',
            'No_indicacion' => 'nullable|array',
            'LA' => 'nullable|array',
            'LC' => 'nullable|array',
            'd' => 'nullable|array',
            'ta' => 'nullable|array',
            't_h' => 'nullable|array',
            'Referencia' => 'nullable|array',
            'Dictamen' => 'nullable|array',
            'No_foto' => 'nullable|array',

            'Componentes_ID' => 'nullable|array',
            'Componentes_Descripcion_del_Elemento' => 'nullable|array',
            'Componentes_0' => 'nullable|array',
            'Componentes_Longitud_in' => 'nullable|array',
            'Componentes_Tipo_conexion' => 'nullable|array',
            'Componentes_Servicio' => 'nullable|array',           
            'Componentes_Clase' => 'nullable|array',
            'Componentes_Especificacion_material' => 'nullable|array',
            'Componentes_Observaciones' => 'nullable|array',

            /* Longitudes inspeccionadas */
            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',
            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.NOMBRE_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.PUESTO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes2.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes2.EMPRESA_ENCARGADO' => 'nullable|string',

            /*3 FIRMAS */
            'Firmas_Reportes3' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes3.Realizo' => 'nullable|string',
            'Firmas_Reportes3.Vobo1' => 'nullable|string',
            'Firmas_Reportes3.Vobo2' => 'nullable|string',

            'Firmas_Reportes3.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.NOMBRE_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.PUESTO_2DO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes3.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes3.EMPRESA_2DO_ENCARGADO' => 'nullable|string',

            /*4 FIRMAS */
            'Firmas_Reportes4' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes4.Realizo' => 'nullable|string',
            'Firmas_Reportes4.Vobo1' => 'nullable|string',
            'Firmas_Reportes4.Vobo2' => 'nullable|string',
            'Firmas_Reportes4.Vobo3' => 'nullable|string',

            'Firmas_Reportes4.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.NOMBRE_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.PUESTO_3RO_ENCARGADO' => 'nullable|string',

            'Firmas_Reportes4.EMPRESA_TECNICO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_2DO_ENCARGADO' => 'nullable|string',
            'Firmas_Reportes4.EMPRESA_3RO_ENCARGADO' => 'nullable|string',
        ]);

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::where('idReportes',$id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        // 1. Obtener los detalles actuales que ya están en la base de datos
        $detallesActuales = json_decode($Reporte->Detalles_Generales, true) ?? [];
        $datosEquipoActuales = json_decode($Reporte->Datos_Equipo, true) ?? [];

        if ($request->hasFile('Detalles_Generales.Reporte_Firmado')) {
            
            // 1. ELIMINAR ARCHIVO ANTERIOR (si existe)
            if (!empty($detallesActuales['Reporte_Firmado'])) {
                // Convertimos la ruta de la base de datos (storage/...) de vuelta a la ruta del disco (public/...)
                $archivoViejo = str_replace('storage/', 'public/', $detallesActuales['Reporte_Firmado']);
                
                if (Storage::exists($archivoViejo)) {
                    Storage::delete($archivoViejo);
                }
            }

            // 2. PROCESAR NUEVO ARCHIVO
            $file = $request->file('Detalles_Generales.Reporte_Firmado');
            $rutaBase = "public/Reportes/FOR_PINS_25_01/{$Contrato}/{$No_Reporte}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_' . $No_Reporte . '_' . time() . '.pdf';
            
            $file->storeAs($rutaBase, $nombreArchivo);

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase) . '/' . $nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
        );
        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG_COMPONENTES'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig_Componentes', '[]')),
            JSON_UNESCAPED_UNICODE
        );
        $this->invalidarPdfCacheReporte25($datosEquipoActuales);
        $validatedData['Datos_Equipo'] = $this->prepararDatosEquipoReporte25(
            $validatedData['Datos_Equipo'],
            $validatedData['Detalles_Generales']['idSolicitud'] ?? null,
            $datosEquipoActuales,
            $validatedData['Detalles_Generales']['Contrato'] ?? '',
            $validatedData['Detalles_Generales']['No_Reporte'] ?? ''
        );

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']) 
        ]);

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("ID.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = $this->filasPorHojaFormatoPrincipal($request);

        $bloques = []; //agregar
        $bloqueActual = [];//agregar
        $contador = 0;//agregar
        /*//agregar
        |--------------------------------------------------------------------------
        | FUNCIONES AUXILIARES
        |--------------------------------------------------------------------------
        */
        $cerrarBloque = function () use (&$bloques, &$bloqueActual, &$contador) {
            if (!empty($bloqueActual)) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }
        };

        $agregarElemento = function ($elemento) use (&$bloques, &$bloqueActual, &$contador, $maxFilasPorBloque) {
            if ($contador >= $maxFilasPorBloque) {
                $bloques[] = $bloqueActual;
                $bloqueActual = [];
                $contador = 0;
            }

            $bloqueActual[] = $elemento;
            $contador++;
        };

        /*
        |--------------------------------------------------------------------------
        | 1. BLOQUE SIN TITULO
        |--------------------------------------------------------------------------
        */
                $longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
                // Debe coincidir con verificarYAgregarLongitud() del JS: inserta una longitud cada 15 filas
                $filasPorLongitud = $maxFilasPorBloque;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$sinTituloKey.$i"),
                    'Descripcion_del_Elemento' => $request->input("Descripcion_del_Elemento.$sinTituloKey.$i"),
                    '0_nom' => $request->input("0_nom.$sinTituloKey.$i"),
                    'Tipo_material' => $request->input("Tipo_material.$sinTituloKey.$i"),
                    'Descripcion_discontinuidad' => $request->input("Descripcion_discontinuidad.$sinTituloKey.$i"),
                    'No_indicacion' => $request->input("No_indicacion.$sinTituloKey.$i"),
                    'LA' => $request->input("LA.$sinTituloKey.$i"),
                    'LC' => $request->input("LC.$sinTituloKey.$i"),
                    'd' => $request->input("d.$sinTituloKey.$i"),
                    'ta' => $request->input("ta.$sinTituloKey.$i"),
                    't_h' => $request->input("t_h.$sinTituloKey.$i"),
                    'Referencia' => $request->input("Referencia.$sinTituloKey.$i"),
                    'Dictamen' => $request->input("Dictamen.$sinTituloKey.$i"),
                    'No_foto' => $request->input("No_foto.$sinTituloKey.$i"),
                    ]
                    ]);
                    // Cada 15 filas, intercalar la longitud correspondiente (replica el orden del DOM)
                    if (($i + 1) % $filasPorLongitud === 0) {
                        $idxLong = intdiv($i, $filasPorLongitud);
                        if (isset($longitudesSin[$idxLong])) {
                            $agregarElemento([
                                'tipo' => 'longitud',
                                'grupo' => $sinTituloKey,
                                'valor' => $longitudesSin[$idxLong]
                            ]);
                            $cerrarBloque();
                        }
                    }
                }

                // Longitudes restantes (si el usuario agregó longitudes manuales extra o el último bloque tiene <15 filas)
                $longsUsadas = intdiv($numFilasSin, $filasPorLongitud);
                $totalLongs = count($longitudesSin);
                for ($j = $longsUsadas; $j < $totalLongs; $j++) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $sinTituloKey,
                        'valor' => $longitudesSin[$j]
                    ]);
                    $cerrarBloque();
                }

        /*
        |--------------------------------------------------------------------------
        | 2. TITULOS + FILAS + LONGITUDES
        |--------------------------------------------------------------------------
        */

        foreach ($titulos as $tituloObj) {
            $tituloKey = $tituloObj['id'];   // ej. "titulo_1"
            $tituloText = $tituloObj['text']; // texto real

            // agregar título
            $agregarElemento([
                'tipo' => 'titulo',
                'grupo' => $tituloKey,
                'texto' => $tituloText
            ]);

            $filas = $request->input("ID.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$tituloKey.$i"),
                    'Descripcion_del_Elemento' => $request->input("Descripcion_del_Elemento.$tituloKey.$i"),
                    '0_nom' => $request->input("0_nom.$tituloKey.$i"),
                    'Tipo_material' => $request->input("Tipo_material.$tituloKey.$i"),
                    'Descripcion_discontinuidad' => $request->input("Descripcion_discontinuidad.$tituloKey.$i"),
                    'No_indicacion' => $request->input("No_indicacion.$tituloKey.$i"),
                    'LA' => $request->input("LA.$tituloKey.$i"),
                    'LC' => $request->input("LC.$tituloKey.$i"),
                    'd' => $request->input("d.$tituloKey.$i"),
                    'ta' => $request->input("ta.$tituloKey.$i"),
                    't_h' => $request->input("t_h.$tituloKey.$i"),
                    'Referencia' => $request->input("Referencia.$tituloKey.$i"),
                    'Dictamen' => $request->input("Dictamen.$tituloKey.$i"),
                    'No_foto' => $request->input("No_foto.$tituloKey.$i"),
                    ]
                ]);
            }

            // Obtener longitud inspeccionada asociada a este título (si existe)
            $longitudes = $request->input("Long_Inspecc.$tituloKey", []); //Agregar

                foreach ($longitudes as $long) {
                    $agregarElemento([
                        'tipo' => 'longitud',
                        'grupo' => $tituloKey,
                        'valor' => $long
                    ]);

                    // cerrar bloque al encontrar longitud
                    $cerrarBloque();
                }
        }
        /*
        |--------------------------------------------------------------------------
        | 3. CERRAR SI QUEDAN ELEMENTOS
        |--------------------------------------------------------------------------
        */
        $cerrarBloque();
        /*
        |--------------------------------------------------------------------------
        | 4. GUARDAR
        |--------------------------------------------------------------------------
        */
        $componentesBloques = $this->procesarBloques($request);
        // Actualizar el campo en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => json_encode([
                'componentes' => $componentesBloques,
                'inspeccion' => $bloques,
            ], JSON_UNESCAPED_UNICODE)
        ]);

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas1 = json_encode($validatedData['Firmas_Reportes1']);
            $Firmas->update([
                'Firmas' => $Firmas1
            ]);
        }
        else if ($numFirmas == 2) {
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

        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_PINS_25_01/{$Contrato}/{$No_Reporte}/Fotos"; /* Ruta personalizada CAMBIAR */

        // Obtener las imágenes existentes
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $deletedImages = $request->input('deleted_images', []);
        $imagenHoja = $request->input('imagen_hoja', []);

        //Log::info('Imágenes eliminadas recibidas:', ['deletedImages' => $deletedImages]);

        // **1️⃣ Eliminar imágenes marcadas para borrar**
        foreach ($deletedImages as $index) {
            if (isset($existingImages[$index])) {
                $rutaImagen = str_replace('storage/', 'public/', $existingImages[$index]);

                // Eliminar del almacenamiento
                if (Storage::exists($rutaImagen)) {
                    Storage::delete($rutaImagen);
                    //Log::info("Imagen eliminada: {$rutaImagen}");
                } else {
                    //Log::warning("No se encontró la imagen para eliminar: {$rutaImagen}");
                }

                // Eliminar de `existingImages` para que no se guarde en la BD
                unset($existingImages[$index]);
            }
        }

        // **Reiniciar el array antes de procesar imágenes**
        $imagenesGuardadas = [];

        // **Evitar duplicados en las rutas ya guardadas**
        $rutasGuardadas = [];

        // **2️⃣ Procesar imágenes existentes**
        foreach ($existingImages as $index => $ruta) {
            if ($request->hasFile("replace_images.$index")) {
                // **Reemplazo de imagen existente**
                $newImage = $request->file("replace_images.$index");

                // Eliminar imagen anterior si existe
                $rutaImagenPublic = str_replace('storage/', 'public/', $ruta);
                if (Storage::exists($rutaImagenPublic)) {
                    Storage::delete($rutaImagenPublic);
                }

                // Guardar la nueva imagen
                $imageName = 'imagen_' . time() . '_' . $index . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs($rutaCarpeta, $imageName);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'una_hoja' => $imagenHoja[$index] ?? 0,
                    ];
                    $rutasGuardadas[] = $rutaNueva; // Guardar ruta para evitar duplicados
                }
            } elseif (!empty($imagesBase64[$index])) {
                // **Procesar imágenes en Base64**
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $imagesBase64[$index]));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'una_hoja' => $imagenHoja[$index] ?? 0,
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (!in_array($ruta, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $ruta,
                        'comentario' => $comments[$index] ?? '',
                        'una_hoja' => $imagenHoja[$index] ?? 0,
                    ];
                    $rutasGuardadas[] = $ruta;
                }
            }
        }

        // **3️⃣ Procesar nuevas imágenes Base64**
        foreach ($imagesBase64 as $index => $base64Image) {
            if (isset($existingImages[$index])) {
                continue; //  ya fue procesada arriba
            }
            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                // Verificar si ya existe en el array
                if (!in_array($rutaNueva, $rutasGuardadas)) {
                    $imagenesGuardadas[] = [
                        'ruta' => $rutaNueva,
                        'comentario' => $comments[$index] ?? '',
                        'una_hoja' => $imagenHoja[$index] ?? 0,
                    ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            }
        }

        // **4️⃣ Guardar las imágenes actualizadas en la BD**
        $Fotos_Reportes->update([
            'Fotos_Reportes' => json_encode(array_values($imagenesGuardadas)), // Se usa array reindexado
        ]);

        //Log::info('Imágenes finales guardadas en BD:', ['imagenesGuardadas' => $imagenesGuardadas]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }


    public function FOR_PINS_25_01($id)
    {
        $t0 = microtime(true);
        $debugPdf = request()->query('debug_pdf');

        if (!$debugPdf) {
            $respuestaCache = $this->obtenerRespuestaPdfCacheadoReporte25($id);

            if ($respuestaCache) {
                Log::info('PDF cacheado reutilizado', ['id' => $id, 'segundos' => round(microtime(true) - $t0, 2)]);
                return $respuestaCache;
            }
        }
        Log::info('INICIO generación PDF', ['id' => $id]);

        $data = $this->cargarContextoReportePdf25($id);
        $data['title'] = 'Reporte_FOR-PINS-25/01.PDF';

        $dataComponentes = $data;
        $dataComponentes['Grupo_Juntas_Detalles_Re'] = $data['Componentes_Detalles_Re'];
        $dataComponentes['tablaCombinacionConfigComponentes'] = $data['tablaCombinacionConfigComponentes'];
        $dataComponentes['Codigo_Formato_Componentes'] = 'FOR-PINS-25/01';
        $dataComponentes['Titulo_Formato_Componentes'] = 'LISTADO DE COMPONENTES';

        Log::info('Datos preparados para render', ['segundos' => round(microtime(true) - $t0, 2)]);

        if ($debugPdf === 'pdf0') {
            $pdf0Content = $this->renderizarPdfContenido(
                'Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_01_PDF',
                $dataComponentes,
                'letter',
                'portrait',
                'pdf0 (componentes) renderizado',
                $t0
            );

            return response($pdf0Content, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01_componentes.PDF"');
        }

        if ($debugPdf === 'pdf1') {
            $pdf1Content = $this->renderizarPdfContenido(
                'Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_PDF',
                $data,
                'letter',
                'landscape',
                'pdf1 (principal) renderizado',
                $t0
            );

            return response($pdf1Content, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01_principal.PDF"');
        }

        if ($debugPdf === 'pdf2') {
            $pdf2Content = $this->renderizarPdfContenido(
                'Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_PINS_25_01_PDF',
                $data,
                'letter',
                'portrait',
                'pdf2 (fotos) renderizado',
                $t0
            );

            return response($pdf2Content, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01_fotos.PDF"');
        }

        $pdf0Content = $this->renderizarPdfContenido(
            'Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_01_PDF',
            $dataComponentes,
            'letter',
            'portrait',
            'pdf0 (componentes) renderizado',
            $t0
        );
        $pdf1Content = $this->renderizarPdfContenido(
            'Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_PDF',
            $data,
            'letter',
            'landscape',
            'pdf1 (principal) renderizado',
            $t0
        );
        $pdf2Content = $this->renderizarPdfContenido(
            'Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_PINS_25_01_PDF',
            $data,
            'letter',
            'portrait',
            'pdf2 (fotos) renderizado',
            $t0
        );

        $combinedPdf = $this->combinarDocumentosPdf25($pdf0Content, $pdf1Content, $pdf2Content);

        $pdfOutput = $combinedPdf->Output('Reporte_FOR_PINS_25_01.PDF', 'S');
        $this->guardarPdfCacheadoReporte25($id, $pdfOutput, $data['Detalles_Generales'], $data['Datos_Equipo']);
        Log::info('PDF combinado y listo para enviar', ['segundos' => round(microtime(true) - $t0, 2)]);

        return response($pdfOutput, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01.PDF"');

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        $tablaCombinacionConfig = json_decode($Datos_Equipo['TABLA_COMBINACION_CONFIG'] ?? '[]', true) ?: [];
        $tablaCombinacionConfigComponentes = json_decode($Datos_Equipo['TABLA_COMBINACION_CONFIG_COMPONENTES'] ?? '[]', true) ?: [];
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $juntasGrupoRe = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true) ?: [];
        $Componentes_Detalles_Re = $juntasGrupoRe['componentes'] ?? [];
        $Grupo_Juntas_Detalles_Re = $juntasGrupoRe['inspeccion'] ?? $juntasGrupoRe;

        $totalTitulos = 0;
        $totalFilas = 0;

        foreach ($Grupo_Juntas_Detalles_Re as $bloque) {
            foreach ($bloque as $item) {
                if (($item['tipo'] ?? '') === 'titulo') {
                    $totalTitulos++;
                }

                if (($item['tipo'] ?? '') === 'fila') {
                    $totalFilas++;
                }
            }
        }

        $totalTitulosYFilas = $totalTitulos + $totalFilas;

        $Firmas_Reportes = json_decode($Firmas_Reportes->Firmas, true);
        $numFirmas = $Firmas_Reportes['numFirmas'];

        $Logo = public_path('images/Logo_AICO_R.jpg');
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true);
            $totalFotos = count($fotos); // Contar el total de imágenes
            $Fotos = [];
        
            foreach ($fotos as $foto) { // Recorrer todas las imágenes sin límite
                $Fotos[] = [
                    'path' => storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'])),
                    'comment' => $foto['comentario'] ?? '',
                    'una_hoja'  => $foto['una_hoja'] ?? 0,
                ];
            }
        }

        $data = [
            'title' => 'Reporte_FOR-PINS-25/01.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            'tablaCombinacionConfig' => $tablaCombinacionConfig,
            'Componentes_Detalles_Re' => $Componentes_Detalles_Re,
            //Total de Juntas
            /*'totalTitulos' => $totalTitulos,
            'totalFilas' => $totalFilas,*/
            'totalTitulosYFilas' => $totalTitulosYFilas,
            //Fotos_Reportes
            'Fotos' => $Fotos,
            //Total de Fotos
            'totalFotos' => $totalFotos,
            //Numero de Firmas
            'numFirmas' => $numFirmas,
            //Firmas
            'Firmas_Reportes' => $Firmas_Reportes,
        ];
        $dataComponentes = $data;
        $dataComponentes['Grupo_Juntas_Detalles_Re'] = $Componentes_Detalles_Re;
        $dataComponentes['tablaCombinacionConfigComponentes'] = $tablaCombinacionConfigComponentes;
        $dataComponentes['Codigo_Formato_Componentes'] = 'FOR-PINS-25/01';
        $dataComponentes['Titulo_Formato_Componentes'] = 'LISTADO DE COMPONENTES';
        Log::info('Datos preparados para render', ['segundos' => round(microtime(true) - $t0, 2)]);
        // Generar el PDF de componentes en orientación vertical
        $pdf0 = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_01_PDF', $dataComponentes)->setPaper('letter', 'portrait');

        // Generar el PDF principal en orientación horizontal
        $pdf1 = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_PINS_25_01_PDF', $data)->setPaper('letter', 'landscape');

        // Generar el PDF adicional en orientación vertical
        $pdf2 = PDF::loadView('Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_PINS_25_01_PDF', $data)->setPaper('letter', 'portrait');

        // Combinar los PDFs
        $pdf0Content = $pdf0->output();
        Log::info('pdf0 (componentes) renderizado', ['segundos' => round(microtime(true) - $t0, 2)]);
        $pdf1Content = $pdf1->output();
        Log::info('pdf1 (principal) renderizado', ['segundos' => round(microtime(true) - $t0, 2)]);
        $pdf2Content = $pdf2->output();
        Log::info('pdf2 (fotos) renderizado', ['segundos' => round(microtime(true) - $t0, 2)]);

       // Crear objetos FPDI independientes para contar páginas



        // Ahora sí combinamos
        $combinedPdf = new Fpdi();
        $pageCount0 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf0Content));
        $pageCount1 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        $pageCount2 = $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        $totalPageCount = $pageCount0 + $pageCount1 + $pageCount2;

        // Añadir páginas del PDF de componentes
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf0Content));
        for ($i = 1; $i <= $pageCount0; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(122.5, -266.5);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('L');
            $combinedPdf->useTemplate($tplId, 0, 0, 297, 210);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(182, -182.5);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        // Añadir páginas del segundo PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(139.5, -265.5);
            // Para que el conteo sea consecutivo
            $combinedPdf->Cell(0, 10, ($i + $pageCount1) . " de $totalPageCount", 0, 0, 'C');
        }

        $pdfOutput = $combinedPdf->Output('Reporte_FOR_PINS_25_01.PDF', 'S');
        Log::info('PDF combinado y listo para enviar', ['segundos' => round(microtime(true) - $t0, 2)]);

        return response($pdfOutput, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Reporte_FOR_PINS_25_01.PDF"');
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
