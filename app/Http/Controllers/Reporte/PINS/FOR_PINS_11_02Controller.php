<?php

namespace App\Http\Controllers\Reporte\PINS;

use App\Http\Controllers\Controller;

use App\Models\OC\OC;
use App\Models\Prueba\prueba;
use App\Models\Formato\formato;
use App\Models\Reporte\reporte;
use App\Models\Clientes\clientes;
use App\Models\Admin\Usuario;
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
use App\Models\Procedimientos\Procedimiento;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/*PDF */
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;
/*QR*/
use Illuminate\Support\Str;

class FOR_PINS_11_02Controller extends Controller
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
                    'rowspan' => max(2, (int) $item['rowspan']),
                ];
            })
            ->unique(function ($item) {
                return $item['groupId'] . '|' . $item['field'] . '|' . $item['startRow'];
            })
            ->values()
            ->all();
    }
    private function getPdfCandidatePaths($rutaDb)
    {
        if (empty($rutaDb)) {
            return [];
        }

        $ruta = trim(str_replace('\\', '/', $rutaDb));
        if ($ruta === '') {
            return [];
        }

        $ruta = preg_replace('#^/?storage/#', '', $ruta);
        $ruta = preg_replace('#^/?public/#', '', $ruta);
        $ruta = ltrim($ruta, '/');

        $candidates = [];

        if (preg_match('#^([A-Za-z]:[\\/]|/)#', $rutaDb)) {
            $candidates[] = $rutaDb;
        }

        $candidates[] = storage_path('app/public/' . $ruta);
        $candidates[] = storage_path($ruta);
        $candidates[] = public_path($ruta);
        $candidates[] = public_path('storage/' . $ruta);
        $candidates[] = public_path('public/' . $ruta);

        return array_values(array_unique($candidates));
    }

    private function resolvePdfPath($rutaDb)
    {
        $candidates = $this->getPdfCandidatePaths($rutaDb);

        foreach ($candidates as $candidate) {
            if ($candidate && is_file($candidate)) {
                return $candidate;
            }
        }

        $ruta = trim(str_replace('\\', '/', $rutaDb));
        $ruta = preg_replace('#^/?storage/#', '', $ruta);
        $ruta = preg_replace('#^/?public/#', '', $ruta);
        $ruta = ltrim($ruta, '/');

        $storagePublicDisk = Storage::disk('public');
        if ($storagePublicDisk->exists($ruta)) {
            return $storagePublicDisk->path($ruta);
        }

        return null;
    }

    private function detectGhostscriptBinary()
    {
        $candidates = [];

        foreach ([getenv('GHOSTSCRIPT_BIN'), getenv('GS_BIN'), getenv('GS_PATH')] as $envCandidate) {
            if (!empty($envCandidate)) {
                $candidates[] = $envCandidate;
            }
        }

        $candidates = array_merge($candidates, [
            'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.03.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs10.02.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs10.02.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs9.56.1\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs9.56.1\\bin\\gswin64c',
            'C:\\Program Files\\gs\\gs9.55.0\\bin\\gswin64c.exe',
            'C:\\Program Files\\gs\\gs9.55.0\\bin\\gswin64c',
        ]);

        if (is_dir('C:\\Program Files\\gs')) {
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gswin64c*') ?: [] as $path) {
                $candidates[] = $path;
            }
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gswin32c*') ?: [] as $path) {
                $candidates[] = $path;
            }
            foreach (glob('C:\\Program Files\\gs\\*\\bin\\gs*') ?: [] as $path) {
                $candidates[] = $path;
            }
        }

        foreach (['gswin64c.exe', 'gswin64c', 'gswin32c.exe', 'gswin32c', 'gs.exe', 'gs'] as $name) {
            $candidates[] = $name;
        }

        foreach (array_unique($candidates) as $candidate) {
            if (empty($candidate)) {
                continue;
            }

            if (is_string($candidate) && is_file($candidate)) {
                return $candidate;
            }

            if (PHP_OS_FAMILY === 'Windows') {
                $command = 'where.exe ' . escapeshellarg($candidate) . ' 2>nul';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && !empty($output[0])) {
                    return trim($output[0]);
                }
            } else {
                $command = 'command -v ' . escapeshellarg($candidate) . ' 2>/dev/null';
                exec($command, $output, $exitCode);

                if ($exitCode === 0 && !empty($output[0])) {
                    return trim($output[0]);
                }
            }
        }

        return null;
    }

    public function Datos_QR($datosParaCrearQR)
    {
        $Contrato = $datosParaCrearQR['Contrato'] ?? 'SinContrato';
        $No_Reporte = $datosParaCrearQR['No_Reporte'] ?? 'SinReporte';
        $ID_TECNICO = $datosParaCrearQR['ID_TECNICO'];
        $idsConsumibles = $datosParaCrearQR['idsConsumibles'] ?? [];
        $idProcedimiento = $datosParaCrearQR['idProcedimiento'] ?? [];
        $token = $datosParaCrearQR['qr_token'] ?? null;

        $idsConsumibles = array_filter([
            $datosParaCrearQR['idEquipo'] ?? null,
            $datosParaCrearQR['idTransductor'] ?? null,
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

        $tecnicos = Usuario::where('id', $ID_TECNICO)
            ->whereNotNull('cv_pdf')
            ->pluck('cv_pdf')
            ->toArray();

        $Procedimiento = Procedimiento::where('idProcedimiento', $idProcedimiento)
            ->whereNotNull('PDF')
            ->pluck('PDF')
            ->toArray();

            $todasLasRutas = array_values(array_merge($facturas, $certificados, $tecnicos, $Procedimiento));

        Log::info('todasLasRutas', $todasLasRutas);

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

        $directorioTemporal = storage_path("app/temp_pdfs/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}");

        if (!File::exists($directorioTemporal)) {
            File::makeDirectory($directorioTemporal, 0777, true);
        }

        $pdfsTemporales = [];

        if (!is_dir(public_path('storage')) && !is_link(public_path('storage'))) {
            try {
                symlink(storage_path('app/public'), public_path('storage'));
            } catch (\Exception $e) {
                Log::warning('No se pudo crear el enlace simbólico de storage.', ['error' => $e->getMessage()]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | COPIAR PDFs TEMPORALES
        |--------------------------------------------------------------------------
        */

        foreach ($rutasValidas as $rutaPdf) {
            $rutaOriginal = $this->resolvePdfPath($rutaPdf);

            if (!$rutaOriginal || !File::exists($rutaOriginal)) {
                Log::warning('PDF no encontrado', [
                    'rutaDb' => $rutaPdf,
                    'rutaOriginal' => $rutaOriginal,
                    'rutasProbadas' => $this->getPdfCandidatePaths($rutaPdf),
                ]);
                continue;
            }

            $nombreArchivo = basename($rutaOriginal);
            $rutaTemporal = $directorioTemporal . DIRECTORY_SEPARATOR . $nombreArchivo;

            File::copy($rutaOriginal, $rutaTemporal);

            $pdfsTemporales[] = $rutaTemporal;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {
            Log::warning('No hay PDFs válidos para unir.');

            return [
                'pdf' => null,
                'qr' => null
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIR PDFs
        |--------------------------------------------------------------------------
        */

        $pdf = new Fpdi();
        $paginasImportadas = 0;

        foreach ($pdfsTemporales as $archivoPdf) {
            try {
                /*
                |--------------------------------------------------------------------------
                | HACER PDF COMPATIBLE CON FPDI
                |--------------------------------------------------------------------------
                */

                $archivoCompatible = str_replace('.pdf', '_compatible.pdf', $archivoPdf);

                $comando =
                $ghostscriptBin = $this->detectGhostscriptBinary();

                if ($ghostscriptBin) {
                    $comando = escapeshellarg($ghostscriptBin)
                        . ' -sDEVICE=pdfwrite '
                        . '-dCompatibilityLevel=1.4 '
                        . '-dNOPAUSE '
                        . '-dQUIET '
                        . '-dBATCH '
                        . '-sOutputFile=' . escapeshellarg($archivoCompatible)
                        . ' ' . escapeshellarg($archivoPdf);

                    exec($comando, $output, $exitCode);

                    if ($exitCode !== 0 || !File::exists($archivoCompatible)) {
                        throw new \Exception("Ghostscript falló para {$archivoPdf}. Exit code: {$exitCode}");
                    }
                } else {
                    Log::warning('Ghostscript no encontrado. Se intentará reescribir el PDF con FPDI.', [
                        'archivo' => $archivoPdf,
                        'busqueda' => 'ruta absoluta o PATH del servicio Apache',
                    ]);
                    $archivoCompatible = $archivoPdf;
                }

                if (!File::exists($archivoCompatible)) {
                    throw new \Exception("No se pudo generar el PDF compatible para {$archivoPdf}");
                }

                $pdfTest = new Fpdi();
                $pdfTest->setSourceFile($archivoCompatible);
                $paginasTest = $pdfTest->setSourceFile($archivoCompatible);
                if ($paginasTest < 1) {
                    throw new \Exception("El archivo no contiene páginas válidas para FPDI: {$archivoCompatible}");
                }

                /*
                |--------------------------------------------------------------------------
                | IMPORTAR PAGINAS
                |--------------------------------------------------------------------------
                */

                $cantidadPaginas = $pdf->setSourceFile($archivoCompatible);

                for ($pagina = 1; $pagina <= $cantidadPaginas; $pagina++) {
                    $template = $pdf->importPage($pagina);
                    $size = $pdf->getTemplateSize($template);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($template);
                    $paginasImportadas++;
                }
            } catch (\Exception $e) {
                Log::error('Error procesando PDF', [
                    'archivo' => $archivoPdf,
                    'error' => $e->getMessage()
                ]);

                continue;
            }
        }
        
        if ($paginasImportadas === 0) {
            Log::warning('No se pudieron importar páginas del PDF unificado. Se generará un PDF de fallback.', [
                'contrato' => $Contrato,
                'noReporte' => $No_Reporte,
                'archivos' => $pdfsTemporales,
            ]);

            $pdf = new Fpdi();
            $pdf->AddPage();
            $pdf->SetFont('Helvetica', '', 12);
            $pdf->MultiCell(0, 7, "No fue posible unir los documentos PDF anexos.\n\nCausa: los archivos anexos no pudieron ser leídos por FPDI, probablemente por compresión no soportada o porque Ghostscript no está instalado en el servidor.\n\nSe recomienda instalar Ghostscript y validar que los PDFs sean compatibles.", 0, 'L');
        }

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO FINAL
        |--------------------------------------------------------------------------
        */

        $directorioFinal = "Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/";
        $rutaDirectorioFinal = storage_path("app/public/" . $directorioFinal);

        if (!File::exists($rutaDirectorioFinal)) {
            File::makeDirectory($rutaDirectorioFinal, 0777, true);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR PDF FINAL
        |--------------------------------------------------------------------------
        */

        $nombreArchivoFinal = "QR_FOR_PINS_11_02_{$Contrato}_{$No_Reporte}.pdf";
        $rutaPdfFinal = $rutaDirectorioFinal . $nombreArchivoFinal;

        $pdf->Output($rutaPdfFinal, 'F');

        /*
        |--------------------------------------------------------------------------
        | URL PUBLICA DEL PDF (TOKEN)
        |--------------------------------------------------------------------------
        */

        $rutaPublicaPdf = route('qr.reporte', ['token' => $token]);

        /*
        |--------------------------------------------------------------------------
        | GENERAR QR
        |--------------------------------------------------------------------------
        */

        $nombreQR = "QR_{$Contrato}_{$No_Reporte}.svg";
        $directorioQR = storage_path("app/public/Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/QR_REPORTES");

        if (!File::exists($directorioQR)) {
            File::makeDirectory($directorioQR, 0777, true);
        }

        $rutaQrCompleta = $directorioQR . DIRECTORY_SEPARATOR . $nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate($rutaPublicaPdf, $rutaQrCompleta);

        /*
        |--------------------------------------------------------------------------
        | RUTAS RELATIVAS
        |--------------------------------------------------------------------------
        */

        $rutaQrPublica = "storage/Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/QR_REPORTES/" . $nombreQR;
        $rutaPdfUnificado = "storage/{$directorioFinal}{$nombreArchivoFinal}";

        /*
        |--------------------------------------------------------------------------
        | LIMPIAR TEMPORALES
        |--------------------------------------------------------------------------
        */

        File::deleteDirectory($directorioTemporal);

        return [
            'pdf' => $rutaPdfUnificado,
            'qr' => $rutaQrPublica
        ];
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
        /*Instancias*/
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

    public function FOR_PINS_11_02_store(Request $request)
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
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|string',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.FREC_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string',
            'Datos_Equipo.GANANCIA' => 'nullable|string',
            'Datos_Equipo.RANGO' => 'nullable|string',
            'Datos_Equipo.RECHAZO' => 'nullable|string',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string',
            'Datos_Equipo.PINTURA' => 'nullable|string',
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_TR' => 'nullable|string',
            'Datos_Equipo.ID_BLOCK' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            /*Titulos Juntas */
            //'titulos' => 'nullable|array',  // Asegura que sea un array
            //'titulos.*' => 'string',  // Cada título debe ser un string válido

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'titulos_data' => 'nullable|string', // JSON con [{id,text},...]
            'ID' => 'nullable|array',
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

            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',
            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.ID_TECNICO' => 'nullable|string',
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

            'Firmas_Reportes3.ID_TECNICO' => 'nullable|string',
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

            'Firmas_Reportes4.ID_TECNICO' => 'nullable|string',
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
        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
        );

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

        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'] ?? null;
        $idEquipo = $validatedData['Datos_Equipo']['ID_EQUIPO'] ?? null;
        $idTransductor = $validatedData['Datos_Equipo']['ID_TR'] ?? null;
        $idBlock = $validatedData['Datos_Equipo']['ID_BLOCK'] ?? null;
        $idProcedimiento = $validatedData['Detalles_Generales']['idProcedimiento'] ?? null;

        if (empty($validatedData['Datos_Equipo']['QR_TOKEN'])) {
            $validatedData['Datos_Equipo']['QR_TOKEN'] = (string) Str::uuid();
        }

        $ID_TECNICO = $request->input('Firmas_Reportes1.ID_TECNICO')
            ?? $request->input('Firmas_Reportes2.ID_TECNICO')
            ?? $request->input('Firmas_Reportes3.ID_TECNICO')
            ?? $request->input('Firmas_Reportes4.ID_TECNICO')
            ?? null;

        $datosParaCrearQR = [
            'Contrato' => $Contrato,
            'No_Reporte' => $No_Reporte,
            'idSolicitud' => $idSolicitud,
            'idEquipo' => $idEquipo,
            'idTransductor' => $idTransductor,
            'idBlock' => $idBlock,
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'ID_TECNICO' => $ID_TECNICO,
            'idProcedimiento' => $idProcedimiento,
        ];

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RUTAS EN DATOS_EQUIPO
        |--------------------------------------------------------------------------
        */

        $Reportes->update([
            'Datos_Equipo' => json_encode(array_merge(
                $validatedData['Datos_Equipo'],
                [
                    'QR_PDF' => $resultadoQR['qr'],
                    'PDF_UNIFICADO' => $resultadoQR['pdf'],
                ]
            )),
        ]);

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
        $maxFilasPorBloque = 11; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

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
                $filasPorLongitud = 10;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$sinTituloKey.$i"),
                    'elemento_tubo' => $request->input("elemento_tubo.$sinTituloKey.$i"),
                    'no_aceptacion' => $request->input("no_aceptacion.$sinTituloKey.$i"),
                    'no_serie' => $request->input("no_serie.$sinTituloKey.$i"),
                    'no_colada' => $request->input("no_colada.$sinTituloKey.$i"),
                    'tnominal' => $request->input("tnominal.$sinTituloKey.$i"),
                    'diametro' => $request->input("diametro.$sinTituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$sinTituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$sinTituloKey.$i"),
                    'nr' => $request->input("nr.$sinTituloKey.$i"),
                    'ni' => $request->input("ni.$sinTituloKey.$i"),
                    'ht' => $request->input("ht.$sinTituloKey.$i"),
                    'prof' => $request->input("prof.$sinTituloKey.$i"),
                    'la' => $request->input("la.$sinTituloKey.$i"),
                    'lc' => $request->input("lc.$sinTituloKey.$i"),
                    'tmax' => $request->input("tmax.$sinTituloKey.$i"),
                    'tmin' => $request->input("tmin.$sinTituloKey.$i"),
                    'metros_lineales' => $request->input("metros_lineales.$sinTituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$sinTituloKey.$i"),
                    'observaciones' => $request->input("observaciones.$sinTituloKey.$i"),
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
                    'elemento_tubo' => $request->input("elemento_tubo.$tituloKey.$i"),
                    'no_aceptacion' => $request->input("no_aceptacion.$tituloKey.$i"),
                    'no_serie' => $request->input("no_serie.$tituloKey.$i"),
                    'no_colada' => $request->input("no_colada.$tituloKey.$i"),
                    'tnominal' => $request->input("tnominal.$tituloKey.$i"),
                    'diametro' => $request->input("diametro.$tituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$tituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$tituloKey.$i"),
                    'nr' => $request->input("nr.$tituloKey.$i"),
                    'ni' => $request->input("ni.$tituloKey.$i"),
                    'ht' => $request->input("ht.$tituloKey.$i"),
                    'prof' => $request->input("prof.$tituloKey.$i"),
                    'la' => $request->input("la.$tituloKey.$i"),
                    'lc' => $request->input("lc.$tituloKey.$i"),
                    'tmax' => $request->input("tmax.$tituloKey.$i"),
                    'tmin' => $request->input("tmin.$tituloKey.$i"),
                    'metros_lineales' => $request->input("metros_lineales.$tituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$tituloKey.$i"),
                    'observaciones' => $request->input("observaciones.$tituloKey.$i"),
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
        // Guardar en el modelo
        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($bloques, JSON_UNESCAPED_UNICODE);
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
            $rutaCarpeta = "public/Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/Fotos";
            
            // Guardar la imagen en la ruta personalizada
            Storage::put("{$rutaCarpeta}/{$imageName}", $image);

            // Guardar la ruta en el array con su comentario correspondiente
            $imagenesGuardadas[] = [
                'ruta' => "storage/Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}",
                'comentario' => $request->comments[$index] ?? null, // Guardar comentario si existe
                'una_hoja' => $request->imagen_hoja[$index] ?? 0, //
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
            'ID_TECNICO' => $ID_TECNICO
            
        ];

        // Regenera el PDF final con la tabla, firmas y fotos ya guardadas.
        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($validatedData['Datos_Equipo']['QR_PDF'] ?? null);
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? ($validatedData['Datos_Equipo']['PDF_UNIFICADO'] ?? null);

        $Reportes->update([
            'Datos_Equipo' => json_encode(array_merge(
                $validatedData['Datos_Equipo'],
                [
                    'QR_PDF' => $validatedData['Datos_Equipo']['QR_PDF'],
                    'PDF_UNIFICADO' => $validatedData['Datos_Equipo']['PDF_UNIFICADO'],
                ]
            )),
        ]);

        $this->OS_OC($datosParaCrearOS_OC);

        // Obtener el valor de 'Detalles_Generales.Contrato'
        $contratoSeleccionado = $validatedData['Detalles_Generales']['Contrato'];
        

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }

    public function FOR_PINS_11_02_update(Request $request, $id)
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
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|string',
            
            /*DATOS DEL EQUIPO Y OBSERVACIONES*/
            'Datos_Equipo' => 'required|array',  // Asegura que es un array
            'Datos_Equipo.MARCA_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MODELO_EQUIPO' => 'nullable|string',
            'Datos_Equipo.N_S_EQUIPO' => 'nullable|string',
            'Datos_Equipo.MARCA_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.MODELO_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.N_S_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.FREC_TRANSDUCTOR' => 'nullable|string',
            'Datos_Equipo.MARCA_BLOCK' => 'nullable|string',
            'Datos_Equipo.MODELO_BLOCK' => 'nullable|string',
            'Datos_Equipo.N_S_BLOCK' => 'nullable|string',
            'Datos_Equipo.ACOPLANTE' => 'nullable|string',
            'Datos_Equipo.LONGITUD_CABLE' => 'nullable|string',
            'Datos_Equipo.GANANCIA' => 'nullable|string',
            'Datos_Equipo.RANGO' => 'nullable|string',
            'Datos_Equipo.RECHAZO' => 'nullable|string',
            'Datos_Equipo.SUPERFICIE' => 'nullable|string',
            'Datos_Equipo.PINTURA' => 'nullable|string',
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'Datos_Equipo.ID_EQUIPO' => 'nullable|string',
            'Datos_Equipo.ID_TR' => 'nullable|string',
            'Datos_Equipo.ID_BLOCK' => 'nullable|string',
            'Datos_Equipo.QR_TOKEN' => 'nullable|string',
            'Datos_Equipo.QR_PDF' => 'nullable|string',
            'Datos_Equipo.PDF_UNIFICADO' => 'nullable|string',

            /*Titulos Juntas */
            //'titulos' => 'nullable|array',  // Asegura que sea un array
            //'titulos.*' => 'string',  // Cada título debe ser un string válido

            /*Resultados_Juntas*/
            /* FILAS DINÁMICAS */
            'titulos_data' => 'nullable|string', // JSON con [{id,text},...]
            'ID' => 'nullable|array',
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

            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',

            //Validar el campo NumFirmas
            'numFirmas' => 'nullable|integer|in:1,2,3,4',

            /*1 FIRMAS */
            'Firmas_Reportes1' => 'required|array',  // Asegura que es un array

            'Firmas_Reportes1.Realizo' => 'nullable|string',
            'Firmas_Reportes1.ID_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.NOMBRE_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.CARGO_TECNICO' => 'nullable|string',
            'Firmas_Reportes1.EMPRESA_TECNICO' => 'nullable|string',

            /*2 FIRMAS */
            'Firmas_Reportes2' => 'required|array',  // Asegura que es un array
            'Firmas_Reportes2.Realizo' => 'nullable|string',
            'Firmas_Reportes2.Vobo1' => 'nullable|string',

            'Firmas_Reportes2.ID_TECNICO' => 'nullable|string',
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

            'Firmas_Reportes3.ID_TECNICO' => 'nullable|string',
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

            'Firmas_Reportes4.ID_TECNICO' => 'nullable|string',
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

        $datosEquipoActuales = json_decode($Reporte->Datos_Equipo, true) ?? [];

        $validatedData['Datos_Equipo']['ID_EQUIPO'] =
            $validatedData['Datos_Equipo']['ID_EQUIPO']
            ?? $datosEquipoActuales['ID_EQUIPO']
            ?? null;
        $validatedData['Datos_Equipo']['ID_TR'] =
            $validatedData['Datos_Equipo']['ID_TR']
            ?? $datosEquipoActuales['ID_TR']
            ?? null;
        $validatedData['Datos_Equipo']['ID_BLOCK'] =
            $validatedData['Datos_Equipo']['ID_BLOCK']
            ?? $datosEquipoActuales['ID_BLOCK']
            ?? null;
        $validatedData['Datos_Equipo']['QR_TOKEN'] =
            $validatedData['Datos_Equipo']['QR_TOKEN']
            ?? $datosEquipoActuales['QR_TOKEN']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN QR PUBLICO
        |--------------------------------------------------------------------------
        */

        if (empty($validatedData['Datos_Equipo']['QR_TOKEN'])) {
            $validatedData['Datos_Equipo']['QR_TOKEN'] = (string) Str::uuid();
        }

        /*
        |--------------------------------------------------------------------------
        | DATOS PARA CREAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $datosParaCrearQR = [
            'Contrato' => $Contrato,
            'No_Reporte' => $No_Reporte,
            'idSolicitud' => $validatedData['Detalles_Generales']['idSolicitud'] ?? null,
            'idEquipo' => $validatedData['Datos_Equipo']['ID_EQUIPO'],
            'idTransductor' => $validatedData['Datos_Equipo']['ID_TR'],
            'idBlock' => $validatedData['Datos_Equipo']['ID_BLOCK'],
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'idProcedimiento' => $validatedData['Detalles_Generales']['idProcedimiento'] ?? null,
            'ID_TECNICO' => $request->input('Firmas_Reportes1.ID_TECNICO')
            ?? $request->input('Firmas_Reportes2.ID_TECNICO')
            ?? $request->input('Firmas_Reportes3.ID_TECNICO')
            ?? $request->input('Firmas_Reportes4.ID_TECNICO')
            ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | GENERAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $resultadoQR = $this->Datos_QR($datosParaCrearQR);

        /*
        |--------------------------------------------------------------------------
        | GUARDAR RUTAS EN DATOS_EQUIPO
        |--------------------------------------------------------------------------
        */

        $validatedData['Datos_Equipo']['QR_PDF'] =
            $resultadoQR['qr']
            ?? $datosEquipoActuales['QR_PDF']
            ?? null;
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] =
            $resultadoQR['pdf']
            ?? $datosEquipoActuales['PDF_UNIFICADO']
            ?? null;

        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
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
        $maxFilasPorBloque = 11; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

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
                $filasPorLongitud = 10;
                for ($i = 0; $i < $numFilasSin; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $sinTituloKey,
                    'data' => [
                    'ID' => $request->input("ID.$sinTituloKey.$i"),
                    'elemento_tubo' => $request->input("elemento_tubo.$sinTituloKey.$i"),
                    'no_aceptacion' => $request->input("no_aceptacion.$sinTituloKey.$i"),
                    'no_serie' => $request->input("no_serie.$sinTituloKey.$i"),
                    'no_colada' => $request->input("no_colada.$sinTituloKey.$i"),
                    'tnominal' => $request->input("tnominal.$sinTituloKey.$i"),
                    'diametro' => $request->input("diametro.$sinTituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$sinTituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$sinTituloKey.$i"),
                    'nr' => $request->input("nr.$sinTituloKey.$i"),
                    'ni' => $request->input("ni.$sinTituloKey.$i"),
                    'ht' => $request->input("ht.$sinTituloKey.$i"),
                    'prof' => $request->input("prof.$sinTituloKey.$i"),
                    'la' => $request->input("la.$sinTituloKey.$i"),
                    'lc' => $request->input("lc.$sinTituloKey.$i"),
                    'tmax' => $request->input("tmax.$sinTituloKey.$i"),
                    'tmin' => $request->input("tmin.$sinTituloKey.$i"),
                    'metros_lineales' => $request->input("metros_lineales.$sinTituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$sinTituloKey.$i"),
                    'observaciones' => $request->input("observaciones.$sinTituloKey.$i"),
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
                    'elemento_tubo' => $request->input("elemento_tubo.$tituloKey.$i"),
                    'no_aceptacion' => $request->input("no_aceptacion.$tituloKey.$i"),
                    'no_serie' => $request->input("no_serie.$tituloKey.$i"),
                    'no_colada' => $request->input("no_colada.$tituloKey.$i"),
                    'tnominal' => $request->input("tnominal.$tituloKey.$i"),
                    'diametro' => $request->input("diametro.$tituloKey.$i"),
                    'no_ind' => $request->input("no_ind.$tituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$tituloKey.$i"),
                    'nr' => $request->input("nr.$tituloKey.$i"),
                    'ni' => $request->input("ni.$tituloKey.$i"),
                    'ht' => $request->input("ht.$tituloKey.$i"),
                    'prof' => $request->input("prof.$tituloKey.$i"),
                    'la' => $request->input("la.$tituloKey.$i"),
                    'lc' => $request->input("lc.$tituloKey.$i"),
                    'tmax' => $request->input("tmax.$tituloKey.$i"),
                    'tmin' => $request->input("tmin.$tituloKey.$i"),
                    'metros_lineales' => $request->input("metros_lineales.$tituloKey.$i"),
                    'evaluacion' => $request->input("evaluacion.$tituloKey.$i"),
                    'observaciones' => $request->input("observaciones.$tituloKey.$i"),
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
        // Actualizar el campo en la base de datos
        $Grupo_Juntas_Detalles_Re->update([
            'Juntas_Grupo_Re' => json_encode($bloques, JSON_UNESCAPED_UNICODE)
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
        $rutaCarpeta = "public/Reportes/FOR_PINS_11_02/{$Contrato}/{$No_Reporte}/Fotos";

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
                continue; // ⛔ ya fue procesada arriba
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


    public function FOR_PINS_11_02($id)
    {
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
        // Decodificar el campo Grupo_Juntas_Detalles_Re para obtener el nombre del proyecto
        $Grupo_Juntas_Detalles_Re = json_decode($Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re, true);

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

        $qrPdf = null;

        if (!empty($Datos_Equipo['QR_PDF'])) {
            $qrPdf = public_path(
                str_replace('storage/', 'storage/', $Datos_Equipo['QR_PDF'])
            );
        }

        $data = [
            'title' => 'Reporte_FOR-PINS-11/02.PDF',
            'Logo' => $Logo,
            'QR_PDF' => $qrPdf,
            //Detalles_Generales
            'Detalles_Generales' => $Detalles_Generales,
            //Datos_Equipo
            'Datos_Equipo' => $Datos_Equipo,
            //Grupo_Juntas_Detalles_Re
            'Grupo_Juntas_Detalles_Re' => $Grupo_Juntas_Detalles_Re,
            'tablaCombinacionConfig' => $tablaCombinacionConfig,
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
        $pdf1 = PDF::loadView('Reportes.ReportesPDF.Reporte_FOR_PINS_11_02_PDF', $data)->setPaper('letter', 'landscape');

        // Generar el PDF adicional en orientación vertical
        $pdf2 = PDF::loadView('Reportes.ReportesFotosPDF.Reporte_FOTOS_FOR_PINS_11_02_PDF', $data)->setPaper('letter', 'portrait');

        // Combinar los PDFs
        $pdf1Content = $pdf1->output();
        $pdf2Content = $pdf2->output();

       // Crear objetos FPDI independientes para contar páginas
        $tempPdf1 = new Fpdi();
        $pageCount1 = $tempPdf1->setSourceFile(StreamReader::createByString($pdf1Content));

        $tempPdf2 = new Fpdi();
        $pageCount2 = $tempPdf2->setSourceFile(StreamReader::createByString($pdf2Content));

        // Ahora sí combinamos
        $combinedPdf = new Fpdi();
        $totalPageCount = $pageCount1 + $pageCount2;

        // Añadir páginas del primer PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf1Content));
        for ($i = 1; $i <= $pageCount1; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('L');
            $combinedPdf->useTemplate($tplId, 0, 0, 297, 210);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(178, -181);
            $combinedPdf->Cell(0, 10, "$i de $totalPageCount", 0, 0, 'C');
        }

        // Añadir páginas del segundo PDF
        $combinedPdf->setSourceFile(StreamReader::createByString($pdf2Content));
        for ($i = 1; $i <= $pageCount2; $i++) {
            $tplId = $combinedPdf->importPage($i);
            $combinedPdf->AddPage('P');
            $combinedPdf->useTemplate($tplId, 0, 0, 210, 297);
            $combinedPdf->SetFont('Arial', 'B', 8);
            $combinedPdf->SetXY(138.5, -266);
            // Para que el conteo sea consecutivo
            $combinedPdf->Cell(0, 10, ($i + $pageCount1) . " de $totalPageCount", 0, 0, 'C');
        }

        return response($combinedPdf->Output('Reporte_FOR_PINS_11_02.PDF', 'I'), 200)
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
