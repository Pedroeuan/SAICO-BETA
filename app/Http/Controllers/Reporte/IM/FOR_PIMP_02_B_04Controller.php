<?php

namespace App\Http\Controllers\Reporte\IM;

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
use App\Models\EquiposyConsumibles\certificados;
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

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;

class FOR_PIMP_02_B_04Controller extends Controller
{
    private function sanitizeDurezaRows($rows): array
    {
        if (!is_array($rows)) {
            return [];
        }

        return collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->map(function ($row) {
                return [
                    'descripcion' => trim((string) ($row['descripcion'] ?? '')),
                    'horario' => trim((string) ($row['horario'] ?? '')),
                    'metal_base_a' => trim((string) ($row['metal_base_a'] ?? '')),
                    'zac_b' => trim((string) ($row['zac_b'] ?? '')),
                    'soldadura_c' => trim((string) ($row['soldadura_c'] ?? '')),
                    'zac_b1' => trim((string) ($row['zac_b1'] ?? '')),
                    'metal_base_a1' => trim((string) ($row['metal_base_a1'] ?? '')),
                    'observaciones' => trim((string) ($row['observaciones'] ?? '')),
                ];
            })
            ->values()
            ->all();
    }

    private function sanitizeDurezaPromedio($rows): array
    {
        if (!is_array($rows)) {
            return [];
        }

        $allowedFields = [
            'ANTES_A',
            'ANTES_B',
            'ANTES_C',
            'ANTES_B1',
            'ANTES_BM',
            'DESPUES_A',
            'DESPUES_B',
            'DESPUES_C',
            'DESPUES_B1',
            'DESPUES_BM',
        ];

        $promedios = [];

        foreach ($allowedFields as $field) {
            $promedios[$field] = trim((string) ($rows[$field] ?? ''));
        }

        return $promedios;
    }

    private function normalizarMergeConfig($mergeConfig): array
    {
        $allowedFields = [
            'descripcion',
            'horario',
            'metal_base_a',
            'zac_b',
            'soldadura_c',
            'zac_b1',
            'metal_base_a1',
            'observaciones',
        ];

        if (is_string($mergeConfig)) {
            $decoded = json_decode($mergeConfig, true);
            $mergeConfig = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($mergeConfig)) {
            return [];
        }

        $unique = [];

        foreach ($mergeConfig as $item) {
            if (!is_array($item)) {
                continue;
            }

            $row = isset($item['row']) ? (int) $item['row'] : (isset($item['startRow']) ? (int) $item['startRow'] : -1);
            $rowspan = isset($item['rowspan']) ? (int) $item['rowspan'] : 1;
            $field = (string) ($item['field'] ?? '');

            if ($row < 0 || $rowspan < 2 || !in_array($field, $allowedFields, true)) {
                continue;
            }

            $key = $row . '|' . $field;
            $unique[$key] = [
                'row' => $row,
                'field' => $field,
                'rowspan' => $rowspan,
            ];
        }

        return array_values($unique);
    }

    private function sanitizeDurezaMergeConfig($mergeConfig, int $rowCount): array
    {
        $normalized = $this->normalizarMergeConfig($mergeConfig);

        return array_values(array_filter($normalized, function ($item) use ($rowCount) {
            return isset($item['row'], $item['rowspan'])
                && $item['row'] >= 0
                && $item['row'] < $rowCount
                && ($item['row'] + $item['rowspan']) <= $rowCount;
        }));
    }

    private function resolveMergeCell(array $mergeConfig, string $field, int $rowIndex, $value): array
    {
        $cellValue = trim((string) $value);

        foreach ($mergeConfig as $merge) {
            $startRow = (int) ($merge['row'] ?? $merge['startRow'] ?? -1);
            $rowspan = (int) ($merge['rowspan'] ?? 1);
            $mergeField = (string) ($merge['field'] ?? '');

            if ($mergeField === $field && $startRow === $rowIndex && $rowspan > 1) {
                return [
                    'show' => true,
                    'rowspan' => $rowspan,
                    'value' => $cellValue,
                ];
            }
        }

        foreach ($mergeConfig as $merge) {
            $startRow = (int) ($merge['row'] ?? $merge['startRow'] ?? -1);
            $rowspan = (int) ($merge['rowspan'] ?? 1);
            $endRow = $startRow + $rowspan - 1;
            $mergeField = (string) ($merge['field'] ?? '');

            if ($mergeField === $field && $rowIndex > $startRow && $rowIndex <= $endRow) {
                return [
                    'show' => false,
                    'rowspan' => 1,
                    'value' => $cellValue,
                ];
            }
        }

        return [
            'show' => true,
            'rowspan' => 1,
            'value' => $cellValue,
        ];
    }

    private function normalizeMergeConfigForPage(array $mergeConfig, int $inicio, int $fin): array
    {
        $normalizado = [];

        foreach ($mergeConfig as $merge) {
            $startRow = (int) ($merge['row'] ?? $merge['startRow'] ?? -1);
            $rowspan = (int) ($merge['rowspan'] ?? 1);
            $field = (string) ($merge['field'] ?? '');
            $endRow = $startRow + $rowspan - 1;

            if ($field === '' || $startRow < $inicio || $startRow > $fin) {
                continue;
            }

            if ($endRow > $fin) {
                continue;
            }

            $normalizado[] = [
                'row' => $startRow - $inicio,
                'field' => $field,
                'rowspan' => $rowspan,
            ];
        }

        return $normalizado;
    }

    private function buildDurezaPages(array $rows, array $mergeConfig, int $rowsPerPage = 20): array
    {
        $rows = $this->sanitizeDurezaRows($rows);
        $mergeConfig = $this->sanitizeDurezaMergeConfig($mergeConfig, count($rows));
        $pages = [];

        foreach (array_chunk($rows, $rowsPerPage) as $pageIndex => $pageRows) {
            $inicioPagina = $pageIndex * $rowsPerPage;
            $finPagina = $inicioPagina + count($pageRows) - 1;
            $mergePagina = $this->normalizeMergeConfigForPage($mergeConfig, $inicioPagina, $finPagina);
            $rowsRender = [];

            foreach ($pageRows as $rowIndex => $row) {
                $rowsRender[] = [
                    'descripcion' => $this->resolveMergeCell($mergePagina, 'descripcion', $rowIndex, $row['descripcion'] ?? ''),
                    'horario' => $this->resolveMergeCell($mergePagina, 'horario', $rowIndex, $row['horario'] ?? ''),
                    'metal_base_a' => $this->resolveMergeCell($mergePagina, 'metal_base_a', $rowIndex, $row['metal_base_a'] ?? ''),
                    'zac_b' => $this->resolveMergeCell($mergePagina, 'zac_b', $rowIndex, $row['zac_b'] ?? ''),
                    'soldadura_c' => $this->resolveMergeCell($mergePagina, 'soldadura_c', $rowIndex, $row['soldadura_c'] ?? ''),
                    'zac_b1' => $this->resolveMergeCell($mergePagina, 'zac_b1', $rowIndex, $row['zac_b1'] ?? ''),
                    'metal_base_a1' => $this->resolveMergeCell($mergePagina, 'metal_base_a1', $rowIndex, $row['metal_base_a1'] ?? ''),
                    'observaciones' => $this->resolveMergeCell($mergePagina, 'observaciones', $rowIndex, $row['observaciones'] ?? ''),
                ];
            }

            $pages[] = [
                'rows' => $rowsRender,
            ];
        }

        return $pages;
    }

    public function Datos_QR($datosParaCrearQR)
    {
        $Contrato = $datosParaCrearQR['Contrato'] ?? 'SinContrato';
        $No_Reporte = $datosParaCrearQR['No_Reporte'] ?? 'SinReporte';
        $token = $datosParaCrearQR['qr_token'] ?? null;

        $idsConsumibles = array_filter([
            $datosParaCrearQR['idEquipo'] ?? null,
            $datosParaCrearQR['idEquipo1'] ?? null,
            $datosParaCrearQR['idSonda'] ?? null,
            $datosParaCrearQR['idBlock'] ?? null
        ]);

        /*
        |--------------------------------------------------------------------------
        | OBTENER FACTURAS Y CERTIFICADOS
        |--------------------------------------------------------------------------
        */

        $facturas = general_eyc::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Factura')
            ->pluck('Factura')
            ->toArray();

        $certificados = certificados::whereIn('idGeneral_EyC', $idsConsumibles)
            ->whereNotNull('Certificado_Actual')
            ->pluck('Certificado_Actual')
            ->toArray();

        $todasLasRutas = array_values(array_merge($facturas, $certificados));

        /*
        |--------------------------------------------------------------------------
        | FILTRAR RUTAS INVALIDAS
        |--------------------------------------------------------------------------
        */

        $rutasInvalidas = ['EN ESPERA DE DATOS', 'ESPERA DE DATO', 'N/A'];

        $rutasValidas = array_filter($todasLasRutas, function ($ruta) use ($rutasInvalidas) {
            if (!$ruta) {
                return false;
            }

            return !in_array(trim(strtoupper($ruta)), $rutasInvalidas);
        });

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO TEMPORAL
        |--------------------------------------------------------------------------
        */

        $directorioTemporal = storage_path("app/temp_pdfs/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}");

        if (!File::exists($directorioTemporal)) {
            File::makeDirectory($directorioTemporal, 0777, true);
        }

        $pdfsTemporales = [];

        /*
        |--------------------------------------------------------------------------
        | COPIAR PDFs TEMPORALES
        |--------------------------------------------------------------------------
        */

        foreach ($rutasValidas as $rutaPdf) {
            $rutaOriginal = storage_path('app/public/' . $rutaPdf);

            if (!File::exists($rutaOriginal)) {
                Log::warning('PDF no encontrado', ['ruta' => $rutaOriginal]);
                continue;
            }

            $nombreArchivo = basename($rutaOriginal);
            $rutaTemporal = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

            File::copy($rutaOriginal, $rutaTemporal);
            $pdfsTemporales[] = $rutaTemporal;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN QR PUBLICO
        |--------------------------------------------------------------------------
        */

        $rutaPublicaPdf = route('qr.reporte', ['token' => $token]);
        $nombreQR = "QR_{$Contrato}_{$No_Reporte}.svg";
        $directorioQR = storage_path("app/public/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/QR_REPORTES");

        if (!File::exists($directorioQR)) {
            File::makeDirectory($directorioQR, 0777, true);
        }

        $rutaQrCompleta = $directorioQR . DIRECTORY_SEPARATOR . $nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        $rutaQrPublica = "storage/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/QR_REPORTES/" . $nombreQR;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {
            Log::warning('No hay PDFs válidos para unir.');

            return [
                'pdf' => null,
                'qr' => $rutaQrPublica
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIR PDFs
        |--------------------------------------------------------------------------
        */

        $pdf = new Fpdi();

        foreach ($pdfsTemporales as $archivoPdf) {
            try {
                /*
                |--------------------------------------------------------------------------
                | HACER PDF COMPATIBLE CON FPDI
                |--------------------------------------------------------------------------
                */

                $archivoCompatible = str_replace('.pdf', '_compatible.pdf', $archivoPdf);

                $comando =
                    'gswin64c -sDEVICE=pdfwrite '
                    . '-dCompatibilityLevel=1.4 '
                    . '-dNOPAUSE '
                    . '-dQUIET '
                    . '-dBATCH '
                    . '-sOutputFile="'
                    . $archivoCompatible
                    . '" "'
                    . $archivoPdf
                    . '"';

                exec($comando);

                $cantidadPaginas = $pdf->setSourceFile($archivoCompatible);

                for ($pagina = 1; $pagina <= $cantidadPaginas; $pagina++) {
                    $template = $pdf->importPage($pagina);
                    $size = $pdf->getTemplateSize($template);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($template);
                }
            } catch (\Exception $e) {
                Log::error('Error procesando PDF', [
                    'archivo' => $archivoPdf,
                    'error' => $e->getMessage()
                ]);
                continue;
            }
        }

        $directorioFinal = "Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/";
        $rutaDirectorioFinal = storage_path("app/public/" . $directorioFinal);

        if (!File::exists($rutaDirectorioFinal)) {
            File::makeDirectory($rutaDirectorioFinal, 0777, true);
        }

        $nombreArchivoFinal = "QR_FOR_PIMP_02_B_04_{$Contrato}_{$No_Reporte}.pdf";
        $rutaPdfFinal = $rutaDirectorioFinal . $nombreArchivoFinal;

        $pdf->Output($rutaPdfFinal, 'F');

        File::deleteDirectory($directorioTemporal);

        return [
            'pdf' => "storage/" . $directorioFinal . $nombreArchivoFinal,
            'qr' => $rutaQrPublica
        ];
    }


    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'] ?? $datosParaCrearOS_OC['Instalacion'] ?? 'ESPERA DE DATOS';
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        //$Contrato = trim(strtoupper($datosParaCrearOS_OC['Contrato']));
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'] ?? $datosParaCrearOS_OC['No_Isometrico'] ?? 'ESPERA DE DATOS';
        $ResultadosJuntas = $datosParaCrearOS_OC['ResultadosJuntas'];
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];

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
            $Orden_Servicio->Fecha = '2001/01/01';
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
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
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
            $Cliente = "POR DEFINIR";
            $Busqueda2Cliente = clientes::where('Cliente', $Cliente)->first();
            // Si no existe, crea el cliente "POR DEFINIR"
            if (!$Busqueda2Cliente) {
                $Busqueda2Cliente = new clientes();
                $Busqueda2Cliente->Cliente = $Cliente;
                $Busqueda2Cliente->RFC = 'N/A';
                $Busqueda2Cliente->Telefono = 'N/A';
                $Busqueda2Cliente->Correo = 'N/A';
                $Busqueda2Cliente->save();
            }

            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            // Obtén el ID del cliente "POR DEFINIR"
            $idClientes = $Busqueda2Cliente->idClientes;
            $Orden_Servicio->idClientes = $idClientes;
            $Orden_Servicio->Fecha = '2001/01/01';
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
            $EsperaDato = "ESPERA DE DATOS";
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
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

    public function FOR_PIMP_02_B_04_store(Request $request)
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
            'Detalles_Generales.Instalacion' => 'nullable|string',
            'Detalles_Generales.No_Isometrico' => 'nullable|string',
            'Detalles_Generales.Nom_Pieza' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.No_Junta' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Temperatura_pieza' => 'nullable|string',
            'Detalles_Generales.Espesor_cedula' => 'nullable|string',
            'Detalles_Generales.Metodo' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.TEMPERATURA_INICIAL' => 'nullable|string',
            'Datos_Equipo.HORA_INICIO' => 'nullable|string',
            'Datos_Equipo.VELOCIDAD_CALENTAMIENTO' => 'nullable|string',
            'Datos_Equipo.HORA_FINAL' => 'nullable|string',
            'Datos_Equipo.TEMPERATURA_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_INICIO' => 'nullable|string',
            'Datos_Equipo.TIEMPO_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_FINAL' => 'nullable|string',
            'Datos_Equipo.VEL_ENFRIAMIENTO' => 'nullable|string',
            'Datos_Equipo.NO_GRAFICA' => 'nullable|string',
            'Datos_Equipo.VEL_GRAFICADOR' => 'nullable|string',
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',
            'Dureza' => 'nullable|array',
            'Dureza.*.descripcion' => 'nullable|string',
            'Dureza.*.horario' => 'nullable|string',
            'Dureza.*.metal_base_a' => 'nullable|string',
            'Dureza.*.zac_b' => 'nullable|string',
            'Dureza.*.soldadura_c' => 'nullable|string',
            'Dureza.*.zac_b1' => 'nullable|string',
            'Dureza.*.metal_base_a1' => 'nullable|string',
            'Dureza.*.observaciones' => 'nullable|string',
            'Dureza_MergeConfig' => 'nullable|string',

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

        /*Detalles Generales y Datos del Equipo */
        $Reportes = new reporte();  // Modelo de la tabla donde guardas los datos
        $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();  // Modelo de la tabla donde guardas los datos
        $Firmas_Reportes = new Firma_Reporte();  // Modelo de la tabla donde guardas los datos
        $Fotos_Reportes = new Fotos_Reporte();  // Modelo de la tabla donde guardas los datos
        $idPrueba_Aplica = $request->input('idPrueba_Aplica');

        $Reportes->idPrueba_Aplica = $idPrueba_Aplica;
        
        // ==========================
        // Lógica para manejar Cliente
        // ==========================
        if ($request->TieneCliente === 'si') {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteSelect;
        } else {
            $validatedData['Detalles_Generales']['Cliente'] = $request->ClienteInput;
        }
        // ==========================
        // Lógica para manejar Contrato
        // ==========================
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

        $validatedData['Datos_Equipo']['DUREZA_PROMEDIO'] = $this->sanitizeDurezaPromedio($request->input('Dureza', []));
        $validatedData['Datos_Equipo']['DUREZA_ROWS'] = $this->sanitizeDurezaRows($request->input('Dureza', []));
        $validatedData['Datos_Equipo']['DUREZA_MERGE_CONFIG'] = $this->sanitizeDurezaMergeConfig(
            $request->input('Dureza_MergeConfig', '[]'),
            count($validatedData['Datos_Equipo']['DUREZA_ROWS'])
        );
        Log::info('Dureza_MergeConfig recibido', [
            'raw' => $request->input('Dureza_MergeConfig')
        ]);

        // Guardar Detalles_Generales como JSON en la base de datos
        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        /*
        |--------------------------------------------------------------------------
        | DATOS PARA CREAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? (string) Str::uuid();

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            'idSonda' => $validatedData['Datos_Equipo']['ID_SONDA'] ?? null,
            'idBlock' => $validatedData['Datos_Equipo']['ID_BLOCK'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? null;
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RUTAS EN DATOS_EQUIPO
        |--------------------------------------------------------------------------
        */

        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);
        $Reportes->save();
        
        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 20; // Mantener 20 filas por bloque/hoja

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
        $imagesBase64 = $request->input('images_base64', []);
        $hayImagenes = !empty(array_filter($imagesBase64));
        if($hayImagenes)
        {
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach ($imagesBase64 as $index => $base64Image) {
            if (empty($base64Image)) {
                continue;
            }

            $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
            $Contrato = $validatedData['Detalles_Generales']['Contrato'];

            // Decodificar Base64
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));

            // Nombre único
            $imageName = 'imagen_' . time() . '_' . $index . '.png';

            $rutaCarpeta = "public/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/Fotos";

            Storage::put("{$rutaCarpeta}/{$imageName}", $image);

            // ✔ Imagen en hoja
            $imagenHoja = isset($request->imagen_hoja[$index]) 
                            ? (bool)$request->imagen_hoja[$index] 
                            : false;

            // ✔ Detalles Junta activado
            $detallesJunta = isset($request->detalles_junta_check[$index]) 
                                ? (bool)$request->detalles_junta_check[$index] 
                                : false;

            // Si detalles junta está activo, guardar datos
            $datosJunta = null;

            if ($detallesJunta) {
                $datosJunta = [
                    'junta' => $request->junta[$index] ?? null,
                    'no_indicacion' => $request->no_indicacion[$index] ?? null,
                    'tipo_indicacion' => $request->tipo_indicacion[$index] ?? null,
                    'longitud' => $request->longitud[$index] ?? null,
                    'profundidad' => $request->profundidad[$index] ?? null,
                    'nivel_referencia' => $request->nivel_referencia[$index] ?? null,
                    'distancia_nivel' => $request->distancia_nivel[$index] ?? null,
                    'direccion_sonda' => $request->direccion_sonda[$index] ?? null,
                    'recubrimiento' => $request->recubrimiento[$index] ?? null,
                ];
            }

            $imagenesGuardadas[] = [
                'ruta' => "storage/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}",
                'comentario' => $request->comments[$index] ?? null,
                'una_hoja' => $imagenHoja,
                'detalles_junta' => $detallesJunta,
                'datos_junta' => $datosJunta
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
        $Instalacion = $validatedData['Detalles_Generales']['Instalacion'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $No_Isometrico = $validatedData['Detalles_Generales']['No_Isometrico'];

        $datosParaCrearOS_OC = [
            'idPrueba_Aplica' => $idPrueba_Aplica,
            'Cliente' => $Cliente,
            'Lugar' => $Instalacion,
            'Contrato' => $Contrato,
            'Proyecto' => $Proyecto,
            'Material' => $Material,
            'Isometrico_Plano' => $No_Isometrico,
            'ResultadosJuntas' => $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re,
            'idSolicitud' => $idSolicitud,
            'idReportes' => $idReportes,
            
        ];

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_02_B_04_update(Request $request, $id)
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
            'Detalles_Generales.Instalacion' => 'nullable|string',
            'Detalles_Generales.No_Isometrico' => 'nullable|string',
            'Detalles_Generales.Nom_Pieza' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.No_Junta' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Criterio_Evaluacion' => 'nullable|string',
            'Detalles_Generales.Temperatura_pieza' => 'nullable|string',
            'Detalles_Generales.Espesor_cedula' => 'nullable|string',
            'Detalles_Generales.Metodo' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',

            'Datos_Equipo.MARCA_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.NS_EQUIPO1' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO1' => 'nullable|string',

            'Datos_Equipo.TEMPERATURA_INICIAL' => 'nullable|string',
            'Datos_Equipo.HORA_INICIO' => 'nullable|string',
            'Datos_Equipo.VELOCIDAD_CALENTAMIENTO' => 'nullable|string',
            'Datos_Equipo.HORA_FINAL' => 'nullable|string',
            'Datos_Equipo.TEMPERATURA_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_INICIO' => 'nullable|string',
            'Datos_Equipo.TIEMPO_SOSTENIMIENTO' => 'nullable|string',
            'Datos_Equipo.DIA_FINAL' => 'nullable|string',
            'Datos_Equipo.VEL_ENFRIAMIENTO' => 'nullable|string',
            'Datos_Equipo.NO_GRAFICA' => 'nullable|string',
            'Datos_Equipo.VEL_GRAFICADOR' => 'nullable|string',
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',
            'Dureza' => 'nullable|array',
            'Dureza.*.descripcion' => 'nullable|string',
            'Dureza.*.horario' => 'nullable|string',
            'Dureza.*.metal_base_a' => 'nullable|string',
            'Dureza.*.zac_b' => 'nullable|string',
            'Dureza.*.soldadura_c' => 'nullable|string',
            'Dureza.*.zac_b1' => 'nullable|string',
            'Dureza.*.metal_base_a1' => 'nullable|string',
            'Dureza.*.observaciones' => 'nullable|string',
            'Dureza_MergeConfig' => 'nullable|string',
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
        $Firmas = Firma_Reporte::firstOrNew(['idReportes' => $id]);
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
            $rutaBase = "public/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_' . $No_Reporte . '_' . time() . '.pdf';
            
            $file->storeAs($rutaBase, $nombreArchivo);

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase) . '/' . $nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        // Conservar cualquier informacion previa que no venga en el formulario de edicion.
        $validatedData['Detalles_Generales'] = array_merge($detallesActuales, $validatedData['Detalles_Generales']);
        $validatedData['Datos_Equipo'] = array_merge($datosEquipoActuales, $validatedData['Datos_Equipo']);

        $validatedData['Datos_Equipo']['DUREZA_PROMEDIO'] = $this->sanitizeDurezaPromedio($request->input('Dureza', []));
        $validatedData['Datos_Equipo']['DUREZA_ROWS'] = $this->sanitizeDurezaRows($request->input('Dureza', []));
        $validatedData['Datos_Equipo']['DUREZA_MERGE_CONFIG'] = $this->sanitizeDurezaMergeConfig(
            $request->input('Dureza_MergeConfig', '[]'),
            count($validatedData['Datos_Equipo']['DUREZA_ROWS'])
        );
        Log::info('Dureza_MergeConfig recibido', [
            'raw' => $request->input('Dureza_MergeConfig')
        ]);
        
        $validatedData['Datos_Equipo']['ID_EQUIPO'] = $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? ($datosEquipoActuales['ID_EQUIPO'] ?? null);
        $validatedData['Datos_Equipo']['ID_EQUIPO1'] = $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? ($datosEquipoActuales['ID_EQUIPO1'] ?? null);
        $validatedData['Datos_Equipo']['ID_SONDA'] = $validatedData['Datos_Equipo']['ID_SONDA'] ?? ($datosEquipoActuales['ID_SONDA'] ?? null);
        $validatedData['Datos_Equipo']['ID_BLOCK'] = $validatedData['Datos_Equipo']['ID_BLOCK'] ?? ($datosEquipoActuales['ID_BLOCK'] ?? null);
        $validatedData['Datos_Equipo']['QR_TOKEN'] = $validatedData['Datos_Equipo']['QR_TOKEN'] ?? ($datosEquipoActuales['QR_TOKEN'] ?? (string) Str::uuid());

        $datosParaCrearQR = [
            'Contrato' => $validatedData['Detalles_Generales']['Contrato'] ?? null,
            'No_Reporte' => $validatedData['Detalles_Generales']['No_Reporte'] ?? null,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null,
            'idEquipo1' => $validatedData['Datos_Equipo']['ID_EQUIPO1'] ?? null,
            'idSonda' => $validatedData['Datos_Equipo']['ID_SONDA'] ?? null,
            'idBlock' => $validatedData['Datos_Equipo']['ID_BLOCK'] ?? null,
        ];

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($datosEquipoActuales['QR_PDF'] ?? null);
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? ($datosEquipoActuales['PDF_UNIFICADO'] ?? null);

        // Actualiza los detalles generales como JSON en la base de datos
        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo']),
        ]);

        $titulos_json = $request->input('titulos_data', '[]');
        //dd($titulos_json);
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("no.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 20; // Mantener 20 filas por bloque/hoja

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
                $filasPorLongitud = 20;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                        'no' => $request->input("no.$sinTituloKey.$i"),
                        'junta' => $request->input("junta.$sinTituloKey.$i"),
                        'lado' => $request->input("lado.$sinTituloKey.$i"),
                        'no_ind' => $request->input("no_ind.$sinTituloKey.$i"),
                        'tipo_ind' => $request->input("tipo_ind.$sinTituloKey.$i"),
                        'long' => $request->input("long.$sinTituloKey.$i"),
                        'prof' => $request->input("prof.$sinTituloKey.$i"),
                        'NR' => $request->input("NR.$sinTituloKey.$i"),
                        'dnr' => $request->input("dnr.$sinTituloKey.$i"),
                        'evaluacion' => $request->input("evaluacion.$sinTituloKey.$i"),
                        'archivo' => $request->input("archivo.$sinTituloKey.$i"),
                        'long_ins' => $request->input("long_ins.$sinTituloKey.$i"),
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

            $filas = $request->input("no.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'no' => $request->input("no.$tituloKey.$i"),
                    'junta' => $request->input("junta.$tituloKey.$i"),
                    'lado' => $request->input("lado.$tituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$tituloKey.$i"),
                    'tipo_ind' => $request->input("tipo_ind.$tituloKey.$i"),
                    'long' => $request->input("long.$tituloKey.$i"),
                    'prof' => $request->input("prof.$tituloKey.$i"),
                    'NR' => $request->input("NR.$tituloKey.$i"),
                    'dnr' => $request->input("dnr.$tituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$tituloKey.$i"),
                    'archivo' => $request->input("archivo.$tituloKey.$i"),
                    'long_ins' => $request->input("long_ins.$tituloKey.$i"),
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
        // Actualizar o crear el campo en la base de datos
        if ($Grupo_Juntas_Detalles_Re) {
            $Grupo_Juntas_Detalles_Re->update([
                'Juntas_Grupo_Re' => json_encode($bloques, JSON_UNESCAPED_UNICODE)
            ]);
        } else {
            $Grupo_Juntas_Detalles_Re = new Grupo_Juntas_Detalles_Re();
            $Grupo_Juntas_Detalles_Re->idReportes = $id;
            $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($bloques, JSON_UNESCAPED_UNICODE);
            $Grupo_Juntas_Detalles_Re->save();
        }

        /*Firmas */
        // Guardar las firmas
        $numFirmas = $request->input('numFirmas'); // Obtener el número de firmas seleccionadas
        
        if ($numFirmas == 1) {
            $validatedData['Firmas_Reportes1']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas1 = json_encode($validatedData['Firmas_Reportes1']);
            $Firmas->Firmas = $Firmas1;
            $Firmas->save();
        }
        else if ($numFirmas == 2) {
            $validatedData['Firmas_Reportes2']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas2 = json_encode($validatedData['Firmas_Reportes2']);
            $Firmas->Firmas = $Firmas2;
            $Firmas->save();
        }
        else if ($numFirmas == 3) {
            $validatedData['Firmas_Reportes3']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas3 = json_encode($validatedData['Firmas_Reportes3']);
            $Firmas->Firmas = $Firmas3;
            $Firmas->save();
        }
        else{
            $validatedData['Firmas_Reportes4']['numFirmas'] = $validatedData['numFirmas'];
            $Firmas4 = json_encode($validatedData['Firmas_Reportes4']);
            $Firmas->Firmas = $Firmas4;
            $Firmas->save();
        } 

        /* Fotos y Comentarios */
        // Obtener los valores necesarios para la ruta personalizada
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'] ?? ''; // Asegurar que Contrato está definido

        // Ruta base para guardar las imágenes
        $rutaCarpeta = "public/Reportes/FOR_PIMP_02_B_04/{$Contrato}/{$No_Reporte}/Fotos";

        // Obtener las imágenes existentes
        $existingImages = $request->input('existing_images', []);
        $comments = $request->input('comments', []);
        $imagesBase64 = $request->input('images_base64', []);
        $deletedImages = $request->input('deleted_images', []);
        $imagenHoja = $request->input('imagen_hoja', []);
        //Log::info('Imágenes eliminadas recibidas:', ['deletedImages' => $deletedImages]);
        $detallesJuntaCheck = $request->input('detalles_junta_check', []);

        $junta = $request->input('junta', []);
        $noIndicacion = $request->input('no_indicacion', []);
        $tipoIndicacion = $request->input('tipo_indicacion', []);
        $longitud = $request->input('longitud', []);
        $profundidad = $request->input('profundidad', []);
        $nivelReferencia = $request->input('nivel_referencia', []);
        $distanciaNivel = $request->input('distancia_nivel', []);
        $direccionSonda = $request->input('direccion_sonda', []);
        $recubrimiento = $request->input('recubrimiento', []);
        

        $getDetallesJunta = function ($index) use (
            $detallesJuntaCheck,
            $junta,
            $noIndicacion,
            $tipoIndicacion,
            $longitud,
            $profundidad,
            $nivelReferencia,
            $distanciaNivel,
            $direccionSonda,
            $recubrimiento,
        ) {
            $activo = $detallesJuntaCheck[$index] ?? 0;

            if (!$activo) {
                return [
                    'detalles_junta' => 0,
                    'datos_junta' => null
                ];
            }

            return [
                'detalles_junta' => 1,
                'datos_junta' => [
                    'junta' => $junta[$index] ?? null,
                    'no_indicacion' => $noIndicacion[$index] ?? null,
                    'tipo_indicacion' => $tipoIndicacion[$index] ?? null,
                    'longitud' => $longitud[$index] ?? null,
                    'profundidad' => $profundidad[$index] ?? null,
                    'nivel_referencia' => $nivelReferencia[$index] ?? null,
                    'distancia_nivel' => $distanciaNivel[$index] ?? null,
                    'direccion_sonda' => $direccionSonda[$index] ?? null,
                    'recubrimiento' => $recubrimiento[$index] ?? null,
                ]
            ];
        };
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
            if ($request->hasFile("replace_images.$index") && empty($imagesBase64[$index])) {
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
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $imagenHoja[$index] ?? 0,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
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
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $imagenHoja[$index] ?? 0,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            } else {
                // **Mantener la imagen existente**
                if (!in_array($ruta, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $imagenHoja[$index] ?? 0,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $ruta;
                }
            }
        }

        // **3️⃣ Procesar nuevas imágenes Base64**
        foreach ($imagesBase64 as $index => $base64Image) {
            if (isset($existingImages[$index])) {
                continue; // ⛔ ya fue procesada arriba
            }

            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . time() . '_' . $index . '.png';
                $path = "{$rutaCarpeta}/{$imageName}";

                // Guardar la imagen en el almacenamiento
                Storage::put($path, $image);
                $rutaNueva = str_replace('public/', 'storage/', $path);

                if (!in_array($rutaNueva, $rutasGuardadas)) {
                $detalles = $getDetallesJunta($index);

                $imagenesGuardadas[] = [
                    'ruta' => $rutaNueva ?? $ruta,
                    'comentario' => $comments[$index] ?? '',
                    'una_hoja' => $imagenHoja[$index] ?? 0,
                    'detalles_junta' => $detalles['detalles_junta'],
                    'datos_junta' => $detalles['datos_junta'],
                ];
                    $rutasGuardadas[] = $rutaNueva;
                }
            }
        }

        // **4️⃣ Guardar las imágenes actualizadas en la BD**
        if ($Fotos_Reportes) {
            $Fotos_Reportes->update([
                'Fotos_Reportes' => json_encode(array_values($imagenesGuardadas)), // Se usa array reindexado
            ]);
        } else {
            $Fotos_Reportes = new Fotos_Reporte();
            $Fotos_Reportes->idReportes = $id;
            $Fotos_Reportes->Fotos_Reportes = json_encode(array_values($imagenesGuardadas));
            $Fotos_Reportes->save();
        }

        //Log::info('Imágenes finales guardadas en BD:', ['imagenesGuardadas' => $imagenesGuardadas]);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PIMP_02_B_04($id)
    {

        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes', $id)->first();
        $Grupo_Juntas_Detalles_Re_Model = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $Firmas_Reportes = Firma_Reporte::where('idReportes', $id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes', $id)->first();

        // Decodificar el campo Detalles_Generales para obtener el nombre del proyecto
        $Detalles_Generales = json_decode($Reporte->Detalles_Generales, true);
        // Decodificar el campo Datos_Equipo para obtener el nombre del proyecto
        $Datos_Equipo = json_decode($Reporte->Datos_Equipo, true);
        $mergeConfigRaw = $Reporte->dureza_merge_config ?? ($Datos_Equipo['DUREZA_MERGE_CONFIG'] ?? '[]');
        $mergeConfig = $this->normalizarMergeConfig($mergeConfigRaw);
        $durezaPromedio = $this->sanitizeDurezaPromedio($Datos_Equipo['DUREZA_PROMEDIO'] ?? []);
        $durezaPages = $this->buildDurezaPages($Datos_Equipo['DUREZA_ROWS'] ?? [], $mergeConfig, 20);
                $croquisPath = public_path('img/reportes/for-pimp-02-b-04-croquis.png');
        $croquisExiste = file_exists($croquisPath);

        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = $Grupo_Juntas_Detalles_Re_Model
            ? json_decode($Grupo_Juntas_Detalles_Re_Model->Juntas_Grupo_Re, true)
            : [];

        if (!is_array($Grupo_Juntas_Detalles_Re)) {
            $Grupo_Juntas_Detalles_Re = [];
        }

        $totalTitulos = 0;
        $totalFilas = 0;

        foreach ($Grupo_Juntas_Detalles_Re as $bloque) {
            if (!is_array($bloque)) continue;

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
        $qrPdf = !empty($Datos_Equipo['QR_PDF']) ? public_path(str_replace('storage/', 'storage/', $Datos_Equipo['QR_PDF'])) : null;
        $Fotos = [];
        $totalFotos = 0;
        // Obtener las fotos con su comentario
        if ($Fotos_Reportes) {
            $fotos = json_decode($Fotos_Reportes->Fotos_Reportes, true) ?? [];
            $totalFotos = count($fotos); // Contar el total de imágenes
        
            foreach ($fotos as $foto) {
                $rutaFoto = storage_path('app/public/' . str_replace('storage/', '', $foto['ruta'] ?? ''));

                if (!File::exists($rutaFoto)) {
                    continue;
                }

                $detallesActivo = $foto['detalles_junta'] ?? 0;
                $datosJunta = $foto['datos_junta'] ?? null;

                $Fotos[] = [
                    'path' => $rutaFoto,
                    'comment' => $foto['comentario'] ?? '',
                    'una_hoja'  => $foto['una_hoja'] ?? 0,

                    // 🔥 NUEVO
                    'detalles_junta' => $detallesActivo,
                    'datos_junta' => $datosJunta,
                ];
            }
        }

        $data = [
            'title' => 'Reporte_FOR-PIMP-02-B-04.PDF',
            'Logo' => $Logo,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            'durezaPromedio' => $durezaPromedio,
            'durezaPages' => $durezaPages,
            'croquisPath' => $croquisPath,
            'croquisExiste' => $croquisExiste,
            'QR_PDF' => $qrPdf,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
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

        // Generar el PDF principal en orientación horizontal
        $pdf1 = PDF::loadView('Reportes.ReportesPDFIM.Reporte_FOR_PIMP_02_B_04_PDF', $data)->setPaper('letter', 'portrait');
        $pdf2Content = null;
        $pageCount2 = 0;

        if (!empty($Fotos)) {
            $pdf2 = PDF::loadView('Reportes.ReportesFotosPDFIM.Reporte_FOTOS_FOR_PIMP_02_B_04_PDF', $data)->setPaper('letter', 'portrait');
            $pdf2Content = $pdf2->output();
        }

        // Generar el PDF adicional en orientación vertical

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();

       // Crear objetos FPDI independientes para contar páginas
        $tempPdf1 = new Fpdi();
        $pageCount1 = $tempPdf1->setSourceFile(StreamReader::createByString($pdf1Content));

        if ($pdf2Content) {
            $tempPdf2 = new Fpdi();
            $pageCount2 = $tempPdf2->setSourceFile(StreamReader::createByString($pdf2Content));
        }


        // Ahora sí combinamos
        $combinedPdf = new Fpdi();
        $totalPageCount = $pageCount1 + $pageCount2;

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(151.5, 32);
            $combinedPdf->MultiCell(24, 3.5, "$i DE $totalPageCount" . "\n" . "$i OF $totalPageCount", 0, 'C');
        }

        // Añadir páginas del segundo PDF

        if ($pdf2Content) {
            $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
            for ($i = 1; $i <= $pageCount2; $i++) {
                $tplId = $combinedPdf->importPage($i);
                $combinedPdf->AddPage('P');
                $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
                $combinedPdf->SetFont('Arial', 'B', 8);
                $paginaActual = $i + $pageCount1;
                $combinedPdf->SetXY(151.5, 32);
                $combinedPdf->MultiCell(24, 3.5, "$paginaActual DE $totalPageCount" . "\n" . "$paginaActual OF $totalPageCount", 0, 'C');
            }
        }

        return response($combinedPdf->Output('Reporte_FOR_PIMP_02_B_04.PDF', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
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
