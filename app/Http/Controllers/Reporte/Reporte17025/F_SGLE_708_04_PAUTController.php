<?php

namespace App\Http\Controllers\Reporte\Reporte17025;

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
use App\Services\ArchivosReportePt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/*PDF*/
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;
use Barryvdh\DomPDF\Facade\Pdf;
/*QR*/
use Illuminate\Support\Str;

class F_SGLE_708_02_PTController extends Controller
{
    private function completarNombreFirmaPt(array $datos): array
    {
        $cantidad = (int) ($datos['numFirmas'] ?? 1);
        $campo = 'Firmas_Reportes' . $cantidad;
        $idTecnico = $datos[$campo]['ID_TECNICO'] ?? null;
        if ($idTecnico) {
            $tecnico = Usuario::find($idTecnico);
            if (!$tecnico) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $campo . '.ID_TECNICO' => 'El técnico seleccionado no existe. Selecciona nuevamente la firma.',
                ]);
            }
        }
        return $datos;
    }

    /** Adapta nombres anteriores sin perder los datos de reportes ya capturados. */
    public static function adaptarDatosPt(array $generales, array $equipo): array
    {
        foreach ($equipo as $clave => $valor) {
            $actual = str_replace(['REVELEADOR', 'APLICACION_', 'TIPO_GRUPO_'], ['REVELADOR', 'METODO_', 'TIPO_'], $clave);
            if (!array_key_exists($actual, $equipo)) $equipo[$actual] = $valor;
        }
        foreach (['Intensidad' => 'Intensidad_luminosa', 'Codigo_estandar' => 'Codigo_Aplicable', 'Fecha_Ensayo' => 'Fecha'] as $actual => $anterior) {
            if (!array_key_exists($actual, $generales) && array_key_exists($anterior, $generales)) $generales[$actual] = $generales[$anterior];
        }
        foreach (['Tipo_luz' => 'TIPO_LUZ', 'Intensidad' => 'INTENSIDAD', 'Condicion_superficie' => 'CONDICION_SUPERFICIAL', 'Temperatura_material' => 'TEMPERATURA_PRUEBA'] as $actual => $anterior) {
            if (!array_key_exists($actual, $generales)) $generales[$actual] = $equipo[$anterior] ?? '';
        }
        // Los servicios de QR/OS y los PDF anteriores utilizan estos alias.
        $generales['Fecha'] = $generales['Fecha_Ensayo'] ?? '';
        $generales['Codigo_Aplicable'] = $generales['Codigo_estandar'] ?? '';
        $generales['Isometrico_Plano'] = implode(', ', array_filter((array) ($equipo['Isometrico'] ?? ($generales['Isometrico_Plano'] ?? []))));
        $generales['Pieza'] = implode(', ', array_filter((array) ($equipo['Item'] ?? ($generales['Pieza'] ?? []))));
        foreach (['Folio', 'Cliente', 'Contrato', 'Proyecto', 'Material', 'No_Reporte', 'idSolicitud', 'Orden_Trabajo', 'Partida', 'Lugar', 'Procedimiento', 'Direccion', 'Contacto', 'Trazabilidad', 'Fecha_Recepcion', 'Fecha_Ensayo', 'Fecha_Emision', 'OT_AICO', 'Temperatura_ambiental', 'Irradiancia', 'Ensayo_realizado', 'Tipo_metodo', 'Criterio_aceptacion'] as $clave) $generales[$clave] ??= '';
        foreach (['TIPO_LUZ' => 'Tipo_luz', 'INTENSIDAD' => 'Intensidad', 'CONDICION_SUPERFICIAL' => 'Condicion_superficie', 'TEMPERATURA_PRUEBA' => 'Temperatura_material'] as $anterior => $actual) {
            $equipo[$anterior] = $generales[$actual] ?? '';
        }
        foreach (['PENETRANTES', 'REMOVEDOR', 'REVELADOR'] as $consumible) {
            foreach (['ID', 'MARCA', 'MODELO', 'LOTE', 'TIEMPO', 'METODO', 'TIPO'] as $campo) $equipo[$campo . '_' . $consumible] ??= '';
            $equipo['APLICACION_' . $consumible] = $equipo['METODO_' . $consumible];
            $equipo['TIPO_GRUPO_' . $consumible] = $equipo['TIPO_' . $consumible];
        }
        foreach ($equipo as $clave => $valor) {
            if (str_contains($clave, 'REVELADOR')) $equipo[str_replace('REVELADOR', 'REVELEADOR', $clave)] = $valor;
        }
        $equipo['ADICIONES_METODO'] ??= $equipo['Adiciones'] ?? '';
        $equipo['Observaciones'] ??= '';
        if (!empty($equipo['TABLA_COMBINACION_CONFIG'])) {
            $configuracion = json_decode($equipo['TABLA_COMBINACION_CONFIG'], true);
            if (is_array($configuracion)) {
                foreach ($configuracion as &$celda) {
                    if (($celda['field'] ?? '') === 'componente') $celda['field'] = 'no_junta';
                    if (($celda['field'] ?? '') === 'evaluacion') $celda['field'] = 'declaracion';
                }
                unset($celda);
                $equipo['TABLA_COMBINACION_CONFIG'] = json_encode($configuracion);
            }
        }
        return [$generales, $equipo];
    }

    private function normalizarEquiposMedicionPt(array $datos): array
    {
        unset($datos['REQUIERE_EQUIPOS']);
        $filas = $datos['EQUIPOS_MEDICION'] ?? [];
        $inventario = general_eyc::with('certificados')->whereIn('idGeneral_EyC', array_column($filas, 'ID'))->get()->keyBy('idGeneral_EyC');
        $datos['EQUIPOS_MEDICION'] = collect($filas)->unique('ID')->map(function ($fila) use ($inventario) {
            $equipo = $inventario->get($fila['ID']);
            return [
                'ID' => (string) $equipo->idGeneral_EyC,
                'DESCRIPCION' => array_key_exists('DESCRIPCION', $fila) ? $fila['DESCRIPCION'] : $equipo->No_economico,
                'MARCA' => array_key_exists('MARCA', $fila) ? $fila['MARCA'] : $equipo->Marca,
                'MODELO' => array_key_exists('MODELO', $fila) ? $fila['MODELO'] : $equipo->Modelo,
                'NO_SERIE' => array_key_exists('NO_SERIE', $fila) ? $fila['NO_SERIE'] : $equipo->Serie,
                'NO_CERTIFICADO' => array_key_exists('NO_CERTIFICADO', $fila) ? $fila['NO_CERTIFICADO'] : $equipo->certificados?->No_certificado,
                'TRAZABILIDA' => $fila['TRAZABILIDA'] ?? '',
                'REFERENCIA' => $fila['REFERENCIA'] ?? '',
            ];
        })->values()->all();
        foreach (['ID','DESCRIPCION','MARCA','MODELO','NO_SERIE','NO_CERTIFICADO','TRAZABILIDA','REFERENCIA'] as $campo) {
            $datos[$campo] = $datos['EQUIPOS_MEDICION'][0][$campo] ?? '';
        }
        return $datos;
    }

    public const ANCHOS_EQUIPOS_PT = ['DESCRIPCION'=>16.2, 'MARCA'=>10.8, 'MODELO'=>12.6, 'NO_SERIE'=>13.5, 'TRAZABILIDA'=>25.3, 'REFERENCIA'=>9, 'NO_CERTIFICADO'=>12.6];

    public static function anchoTextoEquipoPt(string $campo): float
    {
        return 509.9527 * self::ANCHOS_EQUIPOS_PT[$campo] / 100 - 6;
    }

    public static function lineasEquipoPt($valor, string $campo): array
    {
        return self::lineasTextoPt($valor, self::anchoTextoEquipoPt($campo));
    }

    public static function lineasTextoPt($valor, float $ancho): array
    {
        $metricas = app('dompdf')->getFontMetrics();
        $normal = $metricas->getFont('Times-Roman', 'normal') ?: $metricas->getFont('serif', 'normal');
        $unicode = $metricas->getFont('DejaVu Serif', 'normal');
        $medir = static function ($texto) use ($metricas, $normal, $unicode) {
            $ancho = 0;
            foreach (preg_split('/([^\x{0000}-\x{00FF}]+)/u', $texto, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) as $parte) {
                $ancho += $metricas->getTextWidth($parte, preg_match('/[^\x{0000}-\x{00FF}]/u', $parte) ? $unicode : $normal, 6);
            }
            return $ancho;
        };
        $lineas = [];
        foreach (explode("\n", str_replace(["\r\n", "\r", "\t"], ["\n", "\n", ' '], is_scalar($valor) ? (string) $valor : '')) as $parrafo) {
            $linea = '';
            foreach (preg_split('/\s+/u', trim($parrafo)) as $palabra) {
                if ($palabra === '') continue;
                $propuesta = $linea === '' ? $palabra : $linea . ' ' . $palabra;
                if ($medir($propuesta) <= $ancho) {
                    $linea = $propuesta;
                    continue;
                }
                if ($linea !== '') $lineas[] = $linea;
                $linea = '';
                foreach (preg_split('//u', $palabra, -1, PREG_SPLIT_NO_EMPTY) as $caracter) {
                    if ($linea !== '' && $medir($linea . $caracter) > $ancho) {
                        $lineas[] = $linea;
                        $linea = '';
                    }
                    $linea .= $caracter;
                }
            }
            $lineas[] = $linea;
        }
        return $lineas;
    }

    public static function paginasEquiposMedicionPt(array $equipo): array
    {
        if (array_key_exists('EQUIPOS_MEDICION', $equipo)) {
            $equipos = array_values((array) $equipo['EQUIPOS_MEDICION']);
        } else {
            $equipos = !empty($equipo['ID'])
                ? [array_intersect_key($equipo, array_flip(['ID', 'DESCRIPCION', 'MARCA', 'MODELO', 'NO_SERIE', 'TRAZABILIDA', 'REFERENCIA', 'NO_CERTIFICADO']))]
                : [];
        }

        $campos = ['DESCRIPCION','MARCA','MODELO','NO_SERIE','TRAZABILIDA','REFERENCIA','NO_CERTIFICADO'];
        $paginas = [];
        $pagina = [];
        $alto = 0;
        foreach ($equipos as $fila) {
            $lineas = [];
            foreach ($campos as $campo) $lineas[$campo] = self::lineasEquipoPt($fila[$campo] ?? '', $campo);
            $maximo = max(array_map('count', $lineas));
            // Una fila puede continuar en otra página si no cabe completa.
            for ($inicio = 0; $inicio < $maximo; $inicio += 8) {
                $cantidad = min(8, $maximo - $inicio);
                $altura = max(10, $cantidad * 6.48 + 2.5);
                if ($pagina && (count($pagina) >= 5 || $alto + $altura > 62)) {
                    $paginas[] = $pagina;
                    $pagina = [];
                    $alto = 0;
                }
                $fragmento = $fila;
                if ($maximo > 8) {
                    foreach ($campos as $campo) $fragmento[$campo] = implode("\n", array_slice($lineas[$campo], $inicio, 8));
                }
                $pagina[] = $fragmento;
                $alto += $altura;
            }
        }
        if ($pagina || !$paginas) $paginas[] = $pagina;
        return $paginas;
    }
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

    /** El C.V. debe pertenecer al técnico del grupo de firmas visible. */
    private function tecnicoQrSeleccionado(Request $request): ?string
    {
        $numero = (int) ($request->input('numFirmas') ?: 1);
        $id = $request->input("Firmas_Reportes{$numero}.ID_TECNICO");
        return filled($id) ? (string) $id : null;
    }

    public function Datos_QR($datosParaCrearQR)
    {
        $Contrato = ArchivosReportePt::componente($datosParaCrearQR['Contrato'] ?? 'SinContrato');
        $No_Reporte = ArchivosReportePt::componente($datosParaCrearQR['No_Reporte'] ?? 'SinReporte');
        $ID_TECNICO = $datosParaCrearQR['ID_TECNICO'] ?? null;
        $idsConsumibles = $datosParaCrearQR['idsConsumibles'] ?? [];
        $idProcedimiento = $datosParaCrearQR['idProcedimiento'] ?? null;
        $token = $datosParaCrearQR['qr_token'] ?? null;

        $idsConsumibles = [
            $datosParaCrearQR['idPenetrante'],
            $datosParaCrearQR['idRemovedor'],
            $datosParaCrearQR['idRevelador']
        ];

        /*
        |--------------------------------------------------------------------------
        | OBTENER FACTURAS Y CERTIFICADOS
        |--------------------------------------------------------------------------
        */

        $facturas = general_eyc::whereIn(
            'idGeneral_EyC',
            $idsConsumibles
        )
        ->whereNotNull('Factura')
        ->pluck('Factura')
        ->toArray();

        $certificados = certificados::whereIn(
            'idGeneral_EyC',
            $idsConsumibles
        )
        ->whereNotNull('Certificado_Actual')
        ->pluck('Certificado_Actual')
        ->toArray();

        $idsEquipos = array_values(array_unique(array_filter(array_column(
            $datosParaCrearQR['equiposMedicion'] ?? [], 'ID'
        ))));
        $certificadosEquipos = certificados::whereIn('idGeneral_EyC', $idsEquipos)
            ->whereNotNull('Certificado_Actual')
            ->pluck('Certificado_Actual')
            ->toArray();

        // Adjuntar la documentación del técnico elegida para este servicio y procedimiento.
        $tecnicos = app(\App\Services\DocumentosUsuarioReporte::class)->rutas(
            $ID_TECNICO ? Usuario::find($ID_TECNICO) : null,
            $datosParaCrearQR['Documentos_Usuario'] ?? null,
            'F-SGLE-708-02-PT',
            $idProcedimiento ? (int) $idProcedimiento : null
        );

        $Procedimiento = Procedimiento::where('idProcedimiento', $idProcedimiento)
            ->whereNotNull('PDF')
            ->pluck('PDF')
            ->toArray();

            $todasLasRutas = array_values(array_merge($facturas, $certificados, $certificadosEquipos, $tecnicos, $Procedimiento));

        Log::info('todasLasRutas', $todasLasRutas);

        /*
        |--------------------------------------------------------------------------
        | FILTRAR RUTAS INVALIDAS
        |--------------------------------------------------------------------------
        */

        $rutasInvalidas = [
            'EN ESPERA DE DATOS',
            'ESPERA DE DATO',
            'N/A'
        ];

        $rutasValidas = array_filter(
            $todasLasRutas,
            function ($ruta) use ($rutasInvalidas) {

                if (!$ruta) {
                    return false;
                }

                return !in_array(
                    trim(strtoupper($ruta)),
                    $rutasInvalidas
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | DIRECTORIO TEMPORAL
        |--------------------------------------------------------------------------
        */

        $directorioTemporal = storage_path(
            "app/temp_pdfs/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/" . Str::uuid()
        );

        if (!File::exists($directorioTemporal)) {
            File::makeDirectory(
                $directorioTemporal,
                0777,
                true
            );
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

        $archivosAgregados = [];
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

            $identidad = realpath($rutaOriginal) ?: $rutaOriginal;
            if (isset($archivosAgregados[$identidad])) {
                continue;
            }
            $archivosAgregados[$identidad] = true;

            $nombreArchivo = count($pdfsTemporales) . '_' . basename($rutaOriginal);

            $rutaTemporal =
                $directorioTemporal .
                DIRECTORY_SEPARATOR .
                $nombreArchivo;

            if (!File::copy(
                $rutaOriginal,
                $rutaTemporal
            )) {
                throw new \RuntimeException('No se pudo copiar un anexo del reporte PT.');
            }

            $pdfsTemporales[] = $rutaTemporal;
        }

        /*
        |--------------------------------------------------------------------------
        | URL PUBLICA DEL PDF (TOKEN)
        |--------------------------------------------------------------------------
        */

        $rutaPublicaPdf = route(
            'qr.reporte',
            ['token' => $token]
        );

        /*
        |--------------------------------------------------------------------------
        | GENERAR QR
        |--------------------------------------------------------------------------
        */

        $nombreQR =
            "QR_{$Contrato}_{$No_Reporte}.svg";

        $directorioQR = storage_path(
            "app/public/Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/QR_REPORTES"
        );

        if (!File::exists($directorioQR)) {

            File::makeDirectory(
                $directorioQR,
                0777,
                true
            );
        }

        $rutaQrCompleta =
            $directorioQR .
            DIRECTORY_SEPARATOR .
            $nombreQR;

        \QrCode::format('svg')
            ->size(300)
            ->margin(0)
            ->generate(
                $rutaPublicaPdf,
                $rutaQrCompleta
            );

        $rutaQrPublica =
            "storage/Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/QR_REPORTES/"
            . $nombreQR;

        /*
        |--------------------------------------------------------------------------
        | VALIDAR PDFs
        |--------------------------------------------------------------------------
        */

        if (empty($pdfsTemporales)) {

            File::deleteDirectory($directorioTemporal);
            Log::warning(
                'No hay PDFs válidos para unir.'
            );

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
        $paginasImportadas = 0;

        foreach ($pdfsTemporales as $archivoPdf) {

            try {

                /*
                |--------------------------------------------------------------------------
                | HACER PDF COMPATIBLE CON FPDI
                |--------------------------------------------------------------------------
                */

                $archivoCompatible = str_replace(
                    '.pdf',
                    '_compatible.pdf',
                    $archivoPdf
                );

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

                $cantidadPaginas =
                    $pdf->setSourceFile(
                        $archivoCompatible
                    );

                for (
                    $pagina = 1;
                    $pagina <= $cantidadPaginas;
                    $pagina++
                ) {

                    $template =
                        $pdf->importPage($pagina);

                    $size =
                        $pdf->getTemplateSize(
                            $template
                        );

                    $pdf->AddPage(
                        $size['orientation'],
                        [
                            $size['width'],
                            $size['height']
                        ]
                    );

                    $pdf->useTemplate(
                        $template
                    );
                    $paginasImportadas++;
                }

            } catch (\Exception $e) {

                Log::error(
                    'Error procesando PDF',
                    [
                        'archivo' => $archivoPdf,
                        'error' => $e->getMessage()
                    ]
                );

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

        $directorioFinal =
            "Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/";

        $rutaDirectorioFinal = storage_path(
            "app/public/" . $directorioFinal
        );

        if (!File::exists($rutaDirectorioFinal)) {

            File::makeDirectory(
                $rutaDirectorioFinal,
                0777,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR PDF FINAL
        |--------------------------------------------------------------------------
        */

        $nombreArchivoFinal =
            "QR_F_SGLE_708_02_PT_{$Contrato}_{$No_Reporte}.pdf";

        $rutaPdfFinal =
            $rutaDirectorioFinal .
            $nombreArchivoFinal;

        $pdf->Output(
            $rutaPdfFinal,
            'F'
        );

        $rutaPdfRelativa =
            "storage/Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/"
            . $nombreArchivoFinal;

        /*
        |--------------------------------------------------------------------------
        | LIMPIAR TEMPORALES
        |--------------------------------------------------------------------------
        */

        File::deleteDirectory(
            $directorioTemporal
        );

        Log::info(
            'PDF final generado',
            [
                'pdf' => $rutaPdfRelativa,
                'qr' => $rutaQrPublica
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return [
            'pdf' => $rutaPdfRelativa,
            'qr' => $rutaQrPublica
        ];
    }
        
    public function OS_OC($datosParaCrearOS_OC)
    {
        $idPrueba_Aplica = $datosParaCrearOS_OC['idPrueba_Aplica'];
        $Cliente = $datosParaCrearOS_OC['Cliente'];
        $Lugar = $datosParaCrearOS_OC['Lugar'];
        $Contrato= $datosParaCrearOS_OC['Contrato'];
        $Proyecto = $datosParaCrearOS_OC['Proyecto'];
        $Material = $datosParaCrearOS_OC['Material'];
        $Isometrico_Plano = $datosParaCrearOS_OC['Isometrico_Plano'];
        $Pieza = $datosParaCrearOS_OC['Pieza'];
        $Norma_cod_Criterio_Eva = $datosParaCrearOS_OC['Norma_cod_Criterio_Eva'];
        $ResultadosJuntas = $datosParaCrearOS_OC['ResultadosJuntas'];
        $idSolicitud = $datosParaCrearOS_OC['idSolicitud'];
        $idReportes = $datosParaCrearOS_OC['idReportes'];

        $EsperaDato = "ESPERA DE DATOS";

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
            //log::info("ID del cliente encontrado: " . $idCliente);
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
            $OC->idClientes = $idCliente;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = '[]';
            $Detalles_OC->NumCotizacion = $EsperaDato;
            $Detalles_OC->SolicitudCliente = $EsperaDato;
            $Detalles_OC->Contacto = $EsperaDato;
            $Detalles_OC->Puesto = $EsperaDato;
            $Detalles_OC->Ciudad = $EsperaDato;
            $Detalles_OC->Telefono = $EsperaDato;
            $Detalles_OC->Correo = $EsperaDato;
            $Detalles_OC->Vigencia = $EsperaDato;
            $Detalles_OC->Notas = $EsperaDato;
            $Detalles_OC->Condiciones_pago = $EsperaDato;
            $Detalles_OC->Condiciones_generales = $EsperaDato;
            $Detalles_OC->save();
            }
            
            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->idEncuesta = null;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();

        } else {
            // Cliente no encontrado
            //$Cliente = "POR DEFINIR";
            //$Busqueda2Cliente = clientes::where('Cliente', $Cliente)->first();
            // Si no existe, crea el cliente "POR DEFINIR"
            //if (!$Busqueda2Cliente) {
                $NewCliente = new clientes();
                $NewCliente->Cliente = $Cliente;
                $NewCliente->RFC = $EsperaDato;
                $NewCliente->Telefono = $EsperaDato;
                $NewCliente->Correo = $EsperaDato;
                $NewCliente->Logo = $EsperaDato;
            $NewCliente->portal_token = (string) Str::uuid();
            $NewCliente->save();
            $idClientes = $NewCliente->idClientes;
            //}

            $BusquedaContratoOS = Orden_Servicio::where('Contrato', $Contrato)->first();

            if($BusquedaContratoOS)
            {
                $idOrdenServicio = $BusquedaContratoOS->idOrden_Servicio;
            } else{
            // Obtén el ID del cliente que se creo recientemente
            $idClientes = $NewCliente->idClientes;
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
            $OC->Contrato = $Contrato;
            $OC->Num_OC = $EsperaDato;
            $OC->Requisicion = $EsperaDato;
            $OC->Proyecto = $Proyecto;
            $OC->idClientes = $idClientes;
            $OC->Lugar_trabajo = $EsperaDato;
            $OC->Fecha_Solicitud = '2001/01/01';
            $OC->Tipo_Servicio = $EsperaDato;
            $OC->Estatus = 'OC';
            $OC->OC_archivo = $EsperaDato;
            $OC->save();

            $idOC = $OC->idOC;
            $Detalles_OC->idOC = $idOC;
            $Detalles_OC->Detalles = '[]';
            $Detalles_OC->NumCotizacion = $EsperaDato;
            $Detalles_OC->SolicitudCliente = $EsperaDato;
            $Detalles_OC->Contacto = $EsperaDato;
            $Detalles_OC->Puesto = $EsperaDato;
            $Detalles_OC->Ciudad = $EsperaDato;
            $Detalles_OC->Telefono = $EsperaDato;
            $Detalles_OC->Correo = $EsperaDato;
            $Detalles_OC->Vigencia = $EsperaDato;
            $Detalles_OC->Notas = $EsperaDato;
            $Detalles_OC->Condiciones_pago = $EsperaDato;
            $Detalles_OC->Condiciones_generales = $EsperaDato;
            $Detalles_OC->save();
            }

            $Lineal_Ideal->idOC = $idOC;
            $Lineal_Ideal->idOrden_Servicio = $idOrdenServicio;
            $Lineal_Ideal->idSolicitud = $idSolicitud;
            $Lineal_Ideal->idReportes = $idReportes;
            $Lineal_Ideal->idEncuesta = null;
            $Lineal_Ideal->Estatus = 'CREADO';
            $Lineal_Ideal->save();
        }

    }

    public function F_SGLE_708_02_PT_store(Request $request)
    {
        $Estatus = "CREADO";
        // Validar los Detalles_Generales
        $resultadosOrdenadosPt = self::validarResultadosOrdenadosPt($request);
        $validatedData = $request->validate([
            /*DATOS CLIENTES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.No_Reporte' => 'required|string', // NO. DE INFORME
            'Detalles_Generales.Cliente' => 'nullable|string', // NOMBRE DEL CLIENTE 
            'Detalles_Generales.Direccion' => 'nullable|string', // DIRECCIÓN DEL CLIENTE
            'Detalles_Generales.Contacto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string', // ORDEN DE COMPRA DEL CLIENTE
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            /*FECHAS */
            'Detalles_Generales.Fecha_Recepcion' => 'nullable|date',
            'Detalles_Generales.Fecha_Emision' => 'nullable|date',
            'Detalles_Generales.Fecha_Ensayo' => 'nullable|date',
            /*DATOS AICO */
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.OT_AICO' => 'nullable|string', // ORDEN DE COMPRA AICO

            /*DATOS DE LA INSPECCIÓN*/
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.No' => 'nullable|array|max:500',
            'Datos_Equipo.No.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Item' => 'nullable|array|max:500',
            'Datos_Equipo.Item.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Id_aico' => 'nullable|array|max:500',
            'Datos_Equipo.Id_aico.*' => 'nullable|string|max:2000',
            'Datos_Equipo.MMN' => 'nullable|array|max:500',
            'Datos_Equipo.MMN.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Colada' => 'nullable|array|max:500',
            'Datos_Equipo.Colada.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Trazabilidad' => 'nullable|array|max:500',
            'Datos_Equipo.Trazabilidad.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Isometrico' => 'nullable|array|max:500',
            'Datos_Equipo.Isometrico.*' => 'nullable|string|max:2000',

            'Datos_Equipo.DECLARACION_RESULTADOS' => 'nullable|string|max:20000',
            'Datos_Equipo.ADICIONES_METODO' => 'nullable|string|max:20000',
            'Datos_Equipo.EQUIPOS_MEDICION' => 'nullable|array|max:50',
            'Datos_Equipo.EQUIPOS_MEDICION.*' => 'array',
            'Datos_Equipo.EQUIPOS_MEDICION.*.ID' => ['required', 'integer', \Illuminate\Validation\Rule::exists('general_eyc', 'idGeneral_EyC')->where('Tipo', 'EQUIPOS')],
            'Datos_Equipo.EQUIPOS_MEDICION.*.DESCRIPCION' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.MARCA' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.MODELO' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.NO_SERIE' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.NO_CERTIFICADO' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.TRAZABILIDA' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.REFERENCIA' => 'nullable|string|max:2000',  // Asegura que es un array
            'Datos_Equipo.ID' => 'nullable|string',
            'Datos_Equipo.DESCRIPCION' => 'nullable|string',
            'Datos_Equipo.MARCA' => 'nullable|string',
            'Datos_Equipo.MODELO' => 'nullable|string',
            'Datos_Equipo.NO_SERIE' => 'nullable|string',
            'Datos_Equipo.TRAZABILIDA' => 'nullable|string',
            'Datos_Equipo.REFERENCIA' => 'nullable|string',
            'Datos_Equipo.NO_CERTIFICADO' => 'nullable|string',

            /*CONDICIONES DE LA PRUEBA */
            'Detalles_Generales.Lugar' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Tipo_luz' => 'nullable|string',
            'Detalles_Generales.Intensidad' => 'nullable|string',
            'Detalles_Generales.Condicion_superficie' => 'nullable|string',
            'Detalles_Generales.Temperatura_ambiental' => 'nullable|string',
            'Detalles_Generales.Irradiancia' => 'nullable|string',
            'Detalles_Generales.Temperatura_material' => 'nullable|string',
            /*REFERENCIA Y MÉTODO */
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Ensayo_realizado' => 'nullable|string',
            'Detalles_Generales.Tipo_metodo' => 'nullable|string',
            'Detalles_Generales.Codigo_estandar' => 'nullable|string',
            'Detalles_Generales.Criterio_aceptacion' => 'nullable|string',

            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|string',
            
            /* Adiciones, desviaciones o exclusiones del método  */
            'Datos_Equipo.Adiciones' => 'nullable|string',

            /* Datos del/los Item/s a Inspeccionar */


            /*DATOS DE LA INSPECCIÓN*/
            'Datos_Equipo.ID_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.TIPO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.MARCA_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.MODELO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.LOTE_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.METODO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.TIEMPO_PENETRANTES' => 'nullable|string',

            'Datos_Equipo.ID_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.TIPO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.MARCA_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.MODELO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.LOTE_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.METODO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.TIEMPO_REMOVEDOR' => 'nullable|string',

            'Datos_Equipo.ID_REVELADOR' => 'nullable|string',
            'Datos_Equipo.TIPO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.MARCA_REVELADOR' => 'nullable|string',
            'Datos_Equipo.MODELO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.LOTE_REVELADOR' => 'nullable|string',
            'Datos_Equipo.METODO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.TIEMPO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.Material_absorbente' => 'nullable|string',

            /*DECLARACIÓN DE LA CONFORMIDAD*/
            'Datos_Equipo.Criterio_Acpt' => 'nullable|string',
            'Datos_Equipo.Valores_referencia' => 'nullable|string',

            /*Resultados_Juntas*/
            'titulos_data' => 'nullable|json', // JSON con [{id,text},...]
            'No' => 'nullable|array',
            'item' => 'nullable|array',
            'no_junta' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'dr' => 'nullable|array',
            'declaracion' => 'nullable|array',
            'long_inspeccionada' => 'nullable|array',

            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',

            //Validar el campo NumFirmas
            'numFirmas' => 'required|integer|in:1,2,3,4',

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
            'Datos_Equipo.Observaciones' => 'nullable|string',
            'No.*' => 'array',
            'No.*.*' => 'nullable|string|max:2000',
            'item.*' => 'array',
            'item.*.*' => 'nullable|string|max:2000',
            'no_junta.*' => 'array',
            'no_junta.*.*' => 'nullable|string|max:2000',
            'no_indicacion.*' => 'array',
            'no_indicacion.*.*' => 'nullable|string|max:2000',
            'tipo_indicacion.*' => 'array',
            'tipo_indicacion.*.*' => 'nullable|string|max:2000',
            'largo.*' => 'array',
            'largo.*.*' => 'nullable|string|max:2000',
            'ancho.*' => 'array',
            'ancho.*.*' => 'nullable|string|max:2000',
            'diametro.*' => 'array',
            'diametro.*.*' => 'nullable|string|max:2000',
            'ht.*' => 'array',
            'ht.*.*' => 'nullable|string|max:2000',
            'dr.*' => 'array',
            'dr.*.*' => 'nullable|string|max:2000',
            'declaracion.*' => 'array',
            'declaracion.*.*' => 'nullable|string|max:2000',
            'long_inspeccionada.*' => 'array',
            'long_inspeccionada.*.*' => 'nullable|string|max:2000',
        ]);
        $imagenesPt = ArchivosReportePt::imagenes($request->input('images_base64', []));
        // Recupera la serie del servidor para conservarla al actualizar los datos generales.
        $seriePnd = app(\App\Services\PaginacionReportesPnd::class)->prepararCaptura($request, null);
        $validatedData = $this->completarNombreFirmaPt($validatedData);
        $validatedData['Datos_Equipo'] = $this->normalizarEquiposMedicionPt($validatedData['Datos_Equipo']);
        [$validatedData['Detalles_Generales'], $validatedData['Datos_Equipo']] = self::adaptarDatosPt($validatedData['Detalles_Generales'], $validatedData['Datos_Equipo']);
        unset($validatedData['Detalles_Generales']['SERIE_REPORTES']);
        if ($seriePnd) $validatedData['Detalles_Generales']['SERIE_REPORTES'] = $seriePnd;

        DB::beginTransaction();
        try {

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
        // Después de $validatedData = $request->validate(...)

        // ========================================
        // GENERAR TOKEN QR (si no existe)
        // ========================================
        if (empty($validatedData['Datos_Equipo']['QR_TOKEN'])) {
            $validatedData['Datos_Equipo']['QR_TOKEN'] = (string) Str::uuid();
        }
        
        // Guardar Detalles_Generales como JSON en la base de datos
        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
        );

        $Reportes->Detalles_Generales = json_encode($validatedData['Detalles_Generales']);
        // Guardar Datos_Equipo como JSON en la base de datos
        $Reportes->Datos_Equipo = json_encode($validatedData['Datos_Equipo']);

        $Reportes->Estatus = $Estatus; // Asignar el estatus

        // Guardar el registro en la base de datos   
        $Reportes->save();

        //QR
        // Obtener datos desde validatedData
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'] ?? null;
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $idPenetrante = $validatedData['Datos_Equipo']['ID_PENETRANTES'] ?? null;
        $idRemovedor = $validatedData['Datos_Equipo']['ID_REMOVEDOR'] ?? null;
        $idRevelador = $validatedData['Datos_Equipo']['ID_REVELADOR'] ?? null;
        $idProcedimiento = $validatedData['Detalles_Generales']['idProcedimiento'] ?? null;
        $ID_TECNICO = $this->tecnicoQrSeleccionado($request);

        // Datos para el QR/PDF
        $datosParaCrearQR = [
            'Documentos_Usuario' => $validatedData['Datos_Equipo']['Documentos_Usuario'] ?? null,
            'Contrato'     => $Contrato,
            'No_Reporte'   => $No_Reporte,
            'idSolicitud'  => $idSolicitud,
            'idPenetrante' => $idPenetrante,
            'idRemovedor'  => $idRemovedor,
            'idRevelador'  => $idRevelador,
            'equiposMedicion' => $validatedData['Datos_Equipo']['EQUIPOS_MEDICION'],
            'qr_token'     => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'ID_TECNICO' => $ID_TECNICO,
            'idProcedimiento' => $idProcedimiento,
        ];

        // Obtener el idReportes del registro recién creado
        $idReportes = $Reportes->idReportes;
        $Grupo_Juntas_Detalles_Re->idReportes = $idReportes;

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("No.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 16; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

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
                $filasPorLongitud = 15;

                for ($i = 0; $i < $numFilasSin; $i++) {
                    $agregarElemento([
                        'tipo' => 'fila',
                        'grupo' => $sinTituloKey,
                        'data' => [
                            'No' => $request->input("No.$sinTituloKey.$i"),
                            'item' => $request->input("item.$sinTituloKey.$i"),
                            'no_junta' => $request->input("no_junta.$sinTituloKey.$i"),
                            'no_indicacion' => $request->input("no_indicacion.$sinTituloKey.$i"),
                            'tipo_indicacion' => $request->input("tipo_indicacion.$sinTituloKey.$i"),
                            'largo' => $request->input("largo.$sinTituloKey.$i"),
                            'ancho' => $request->input("ancho.$sinTituloKey.$i"),
                            'diametro' => $request->input("diametro.$sinTituloKey.$i"),
                            'ht' => $request->input("ht.$sinTituloKey.$i"),
                            'dr' => $request->input("dr.$sinTituloKey.$i"),
                            'declaracion' => $request->input("declaracion.$sinTituloKey.$i"),
                            'long_inspeccionada' => $request->input("long_inspeccionada.$sinTituloKey.$i"),
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

            $filas = $request->input("No.$tituloKey", []);
            $numFilas = count($filas);
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'No' => $request->input("No.$tituloKey.$i"),
                    'item' => $request->input("item.$tituloKey.$i"),
                            'no_junta' => $request->input("no_junta.$tituloKey.$i"),
                    'no_indicacion' => $request->input("no_indicacion.$tituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$tituloKey.$i"),
                    'largo' => $request->input("largo.$tituloKey.$i"),
                    'ancho' => $request->input("ancho.$tituloKey.$i"),
                    'diametro' => $request->input("diametro.$tituloKey.$i"),
                    'ht' => $request->input("ht.$tituloKey.$i"),
                    'dr' => $request->input("dr.$tituloKey.$i"),
                            'declaracion' => $request->input("declaracion.$tituloKey.$i"),
                    'long_inspeccionada' => $request->input("long_inspeccionada.$tituloKey.$i"),
                    ]
                ]);
            }

            // Obtener longitud inspeccionada asociada a este título (si existe)
            //$long = $request->input("Long_Inspecc.$tituloKey", null);
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
        //$Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($datosAgrupados, JSON_UNESCAPED_UNICODE);
        $Grupo_Juntas_Detalles_Re->Juntas_Grupo_Re = json_encode($resultadosOrdenadosPt ?? $bloques, JSON_UNESCAPED_UNICODE);
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
        if($imageCount>=1 || $imagenesPt)
        {
        $imagenesGuardadas = []; // Para almacenar rutas de imágenes guardadas

        foreach ($imagenesPt as $index => $image) {
            $No_Reporte = ArchivosReportePt::componente($validatedData['Detalles_Generales']['No_Reporte']);
            $Contrato = ArchivosReportePt::componente($validatedData['Detalles_Generales']['Contrato']);
            
            // Crear un nombre único para la imagen
            $imageName = 'imagen_' . bin2hex(random_bytes(16)) . '.png';

            // Definir la ruta personalizada
            $rutaCarpeta = "public/Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/Fotos"; /* Ruta personalizada CAMBIAR */
            
            // Guardar la imagen en la ruta personalizada
            if (!Storage::put("{$rutaCarpeta}/{$imageName}", $image)) {
                throw new \RuntimeException('No se pudo guardar la imagen del reporte PT.');
            }

            // Guardar la ruta en el array con su comentario correspondiente
            $imagenesGuardadas[] = [
                'ruta' => "storage/Reportes/F_SGLE_708_02_PT/{$Contrato}/{$No_Reporte}/Fotos/{$imageName}", /* Ruta personalizada CAMBIAR */
                'comentario' => $request->comments[$index] ?? null, // Guardar comentario si existe
                'una_hoja' => $request->imagen_hoja[$index] ?? 0, // 👈 AQUÍ
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
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $Proyecto = $validatedData['Detalles_Generales']['Proyecto'];
        $Material = $validatedData['Detalles_Generales']['Material'];
        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $Isometrico_Plano = $validatedData['Detalles_Generales']['Isometrico_Plano'];
        $Pieza = $validatedData['Detalles_Generales']['Pieza'];
        $Norma_cod_Criterio_Eva = $validatedData['Detalles_Generales']['Codigo_Aplicable'];

        $idPenetrante = $validatedData['Datos_Equipo']['ID_PENETRANTES'];
        $idRemovedor = $validatedData['Datos_Equipo']['ID_REMOVEDOR'];
        $idRevelador = $validatedData['Datos_Equipo']['ID_REVELADOR'];

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
            'ID_TECNICO' => $ID_TECNICO,
            
        ];

        // Regenera el PDF final con la tabla, firmas y fotos ya guardadas.
        $resultadoQR = $this->Datos_QR($datosParaCrearQR);
        $validatedData['Datos_Equipo']['QR_PDF'] = $resultadoQR['qr'] ?? ($validatedData['Datos_Equipo']['QR_PDF'] ?? null);
        // No recuperar un PDF anterior cuando se retiran los anexos del QR.
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] = $resultadoQR['pdf'] ?? null;

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
        

        app(\App\Services\PaginacionReportesPnd::class)->guardarConfiguracion($request, (int) $idReportes, 'F-SGLE-708-02-PT');

        DB::commit();
        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function F_SGLE_708_02_PT_update(Request $request, $id)
    {
        //dd($request->all());
        $Estatus = "ACTUALIZADO";
        // Validar los Detalles_Generales
        $resultadosOrdenadosPt = self::validarResultadosOrdenadosPt($request);
        $validatedData = $request->validate([
            /*DATOS CLIENTES */
            'Detalles_Generales' => 'required|array',  // Asegura que es un array
            'Detalles_Generales.No_Reporte' => 'required|string', // NO. DE INFORME
            'Detalles_Generales.Cliente' => 'nullable|string', // NOMBRE DEL CLIENTE 
            'Detalles_Generales.Direccion' => 'nullable|string', // DIRECCIÓN DEL CLIENTE
            'Detalles_Generales.Contacto' => 'nullable|string',
            'Detalles_Generales.Orden_Trabajo' => 'nullable|string', // ORDEN DE COMPRA DEL CLIENTE
            'Detalles_Generales.Proyecto' => 'nullable|string',
            'Detalles_Generales.Trazabilidad' => 'nullable|string',
            'Detalles_Generales.Partida' => 'nullable|string',
            /*FECHAS */
            'Detalles_Generales.Fecha_Recepcion' => 'nullable|date',
            'Detalles_Generales.Fecha_Emision' => 'nullable|date',
            'Detalles_Generales.Fecha_Ensayo' => 'nullable|date',
            /*DATOS AICO */
            'Detalles_Generales.Contrato' => 'nullable|string',
            'Detalles_Generales.OT_AICO' => 'nullable|string', // ORDEN DE COMPRA AICO

            /*DATOS DE LA INSPECCIÓN*/
            'Datos_Equipo' => 'required|array',
            'Datos_Equipo.No' => 'nullable|array|max:500',
            'Datos_Equipo.No.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Item' => 'nullable|array|max:500',
            'Datos_Equipo.Item.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Id_aico' => 'nullable|array|max:500',
            'Datos_Equipo.Id_aico.*' => 'nullable|string|max:2000',
            'Datos_Equipo.MMN' => 'nullable|array|max:500',
            'Datos_Equipo.MMN.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Colada' => 'nullable|array|max:500',
            'Datos_Equipo.Colada.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Trazabilidad' => 'nullable|array|max:500',
            'Datos_Equipo.Trazabilidad.*' => 'nullable|string|max:2000',
            'Datos_Equipo.Isometrico' => 'nullable|array|max:500',
            'Datos_Equipo.Isometrico.*' => 'nullable|string|max:2000',

            'Datos_Equipo.DECLARACION_RESULTADOS' => 'nullable|string|max:20000',
            'Datos_Equipo.ADICIONES_METODO' => 'nullable|string|max:20000',
            'Datos_Equipo.EQUIPOS_MEDICION' => 'nullable|array|max:50',
            'Datos_Equipo.EQUIPOS_MEDICION.*' => 'array',
            'Datos_Equipo.EQUIPOS_MEDICION.*.ID' => ['required', 'integer', \Illuminate\Validation\Rule::exists('general_eyc', 'idGeneral_EyC')->where('Tipo', 'EQUIPOS')],
            'Datos_Equipo.EQUIPOS_MEDICION.*.DESCRIPCION' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.MARCA' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.MODELO' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.NO_SERIE' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.NO_CERTIFICADO' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.TRAZABILIDA' => 'nullable|string|max:2000',
            'Datos_Equipo.EQUIPOS_MEDICION.*.REFERENCIA' => 'nullable|string|max:2000',  // Asegura que es un array
            'Datos_Equipo.ID' => 'nullable|string',
            'Datos_Equipo.DESCRIPCION' => 'nullable|string',
            'Datos_Equipo.MARCA' => 'nullable|string',
            'Datos_Equipo.MODELO' => 'nullable|string',
            'Datos_Equipo.NO_SERIE' => 'nullable|string',
            'Datos_Equipo.TRAZABILIDA' => 'nullable|string',
            'Datos_Equipo.REFERENCIA' => 'nullable|string',
            'Datos_Equipo.NO_CERTIFICADO' => 'nullable|string',

            /*CONDICIONES DE LA PRUEBA */
            'Detalles_Generales.Lugar' => 'nullable|string',
            'Detalles_Generales.Material' => 'nullable|string',
            'Detalles_Generales.Tipo_luz' => 'nullable|string',
            'Detalles_Generales.Intensidad' => 'nullable|string',
            'Detalles_Generales.Condicion_superficie' => 'nullable|string',
            'Detalles_Generales.Temperatura_ambiental' => 'nullable|string',
            'Detalles_Generales.Irradiancia' => 'nullable|string',
            'Detalles_Generales.Temperatura_material' => 'nullable|string',
            /*REFERENCIA Y MÉTODO */
            'Detalles_Generales.Procedimiento' => 'nullable|string',
            'Detalles_Generales.Ensayo_realizado' => 'nullable|string',
            'Detalles_Generales.Tipo_metodo' => 'nullable|string',
            'Detalles_Generales.Codigo_estandar' => 'nullable|string',
            'Detalles_Generales.Criterio_aceptacion' => 'nullable|string',

            'Detalles_Generales.idSolicitud' => 'nullable|string',
            'Detalles_Generales.Num_Soldador' => 'nullable|string',
            'Detalles_Generales.Nombre_Soldador' => 'nullable|string',
            'Detalles_Generales.idProcedimiento' => 'nullable|string',
            
            /* Adiciones, desviaciones o exclusiones del método  */
            'Datos_Equipo.Adiciones' => 'nullable|string',

            /* Datos del/los Item/s a Inspeccionar */


            /*DATOS DE LA INSPECCIÓN*/
            'Datos_Equipo.ID_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.TIPO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.MARCA_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.MODELO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.LOTE_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.METODO_PENETRANTES' => 'nullable|string',
            'Datos_Equipo.TIEMPO_PENETRANTES' => 'nullable|string',

            'Datos_Equipo.ID_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.TIPO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.MARCA_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.MODELO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.LOTE_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.METODO_REMOVEDOR' => 'nullable|string',
            'Datos_Equipo.TIEMPO_REMOVEDOR' => 'nullable|string',

            'Datos_Equipo.ID_REVELADOR' => 'nullable|string',
            'Datos_Equipo.TIPO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.MARCA_REVELADOR' => 'nullable|string',
            'Datos_Equipo.MODELO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.LOTE_REVELADOR' => 'nullable|string',
            'Datos_Equipo.METODO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.TIEMPO_REVELADOR' => 'nullable|string',
            'Datos_Equipo.Material_absorbente' => 'nullable|string',

            /*DECLARACIÓN DE LA CONFORMIDAD*/
            'Datos_Equipo.Criterio_Acpt' => 'nullable|string',
            'Datos_Equipo.Valores_referencia' => 'nullable|string',

            /*Resultados_Juntas*/
            'titulos_data' => 'nullable|json', // JSON con [{id,text},...]
            'No' => 'nullable|array',
            'item' => 'nullable|array',
            'no_junta' => 'nullable|array',
            'no_indicacion' => 'nullable|array',
            'tipo_indicacion' => 'nullable|array',
            'largo' => 'nullable|array',
            'ancho' => 'nullable|array',
            'diametro' => 'nullable|array',
            'ht' => 'nullable|array',
            'dr' => 'nullable|array',
            'declaracion' => 'nullable|array',
            'long_inspeccionada' => 'nullable|array',

            'Long_Inspecc' => 'nullable|array',
            'Long_Inspecc.*' => 'nullable|array',
            'Long_Inspecc.*.*' => 'nullable|string|max:255',
            'Tabla_CombinacionConfig' => 'nullable|string',

            //Validar el campo NumFirmas
            'numFirmas' => 'required|integer|in:1,2,3,4',

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
                    'Datos_Equipo.Observaciones' => 'nullable|string',
            'No.*' => 'array',
            'No.*.*' => 'nullable|string|max:2000',
            'item.*' => 'array',
            'item.*.*' => 'nullable|string|max:2000',
            'no_junta.*' => 'array',
            'no_junta.*.*' => 'nullable|string|max:2000',
            'no_indicacion.*' => 'array',
            'no_indicacion.*.*' => 'nullable|string|max:2000',
            'tipo_indicacion.*' => 'array',
            'tipo_indicacion.*.*' => 'nullable|string|max:2000',
            'largo.*' => 'array',
            'largo.*.*' => 'nullable|string|max:2000',
            'ancho.*' => 'array',
            'ancho.*.*' => 'nullable|string|max:2000',
            'diametro.*' => 'array',
            'diametro.*.*' => 'nullable|string|max:2000',
            'ht.*' => 'array',
            'ht.*.*' => 'nullable|string|max:2000',
            'dr.*' => 'array',
            'dr.*.*' => 'nullable|string|max:2000',
            'declaracion.*' => 'array',
            'declaracion.*.*' => 'nullable|string|max:2000',
            'long_inspeccionada.*' => 'array',
            'long_inspeccionada.*.*' => 'nullable|string|max:2000',
        ]);
        // Recupera la serie del servidor para conservarla al actualizar los datos generales.
        $seriePnd = app(\App\Services\PaginacionReportesPnd::class)->prepararCaptura($request, (int) $id);
        $validatedData['Detalles_Generales']['Cliente'] = $request->input($request->input('TieneCliente', 'si') === 'si' ? 'ClienteSelect' : 'ClienteInput', $validatedData['Detalles_Generales']['Cliente'] ?? '');
        $validatedData = $this->completarNombreFirmaPt($validatedData);
        $validatedData['Datos_Equipo'] = $this->normalizarEquiposMedicionPt($validatedData['Datos_Equipo']);
        [$validatedData['Detalles_Generales'], $validatedData['Datos_Equipo']] = self::adaptarDatosPt($validatedData['Detalles_Generales'], $validatedData['Datos_Equipo']);
        unset($validatedData['Detalles_Generales']['SERIE_REPORTES']);
        if ($seriePnd) $validatedData['Detalles_Generales']['SERIE_REPORTES'] = $seriePnd;


        // Encontrar el Reporte, Fotos_Reportes, Firmas_Reportes, Grupo_Juntas_Detalles_Re para actualizar los datos en la base de datos
        $Reporte = reporte::where('idReportes',$id)->firstOrFail();
        $request->validate([
            'Detalles_Generales.Reporte_Firmado' => 'nullable|file|mimes:pdf',
        ]);
        $Grupo_Juntas_Detalles_Re = Grupo_Juntas_Detalles_Re::where('idReportes',$id)->first();
        $Firmas = Firma_Reporte::where('idReportes',$id)->first();
        $Fotos_Reportes = Fotos_Reporte::where('idReportes',$id)->first();

        $idSolicitud = $validatedData['Detalles_Generales']['idSolicitud'];
        $Contrato = $validatedData['Detalles_Generales']['Contrato'];
        $No_Reporte = $validatedData['Detalles_Generales']['No_Reporte'];
        $idPenetrante = $validatedData['Datos_Equipo']['ID_PENETRANTES'];
        $idRemovedor = $validatedData['Datos_Equipo']['ID_REMOVEDOR'];
        $idRevelador = $validatedData['Datos_Equipo']['ID_REVELADOR'];

        // 1. Obtener los detalles actuales que ya están en la base de datos
        $detallesActuales = json_decode($Reporte->Detalles_Generales, true) ?? [];

        if ($request->hasFile('Detalles_Generales.Reporte_Firmado')) {
            
            // 2. PROCESAR NUEVO ARCHIVO
            $file = $request->file('Detalles_Generales.Reporte_Firmado');
            $contratoRuta = ArchivosReportePt::componente($Contrato);
            $reporteRuta = ArchivosReportePt::componente($No_Reporte);
            $rutaBase = "public/Reportes/F_SGLE_708_02_PT/{$contratoRuta}/{$reporteRuta}/Reporte_Firmado";
            $nombreArchivo = 'Reporte_Firmado_' . $reporteRuta . '_' . Str::uuid() . '.pdf';
            
            if (!$file->storeAs($rutaBase, $nombreArchivo)) {
                throw new \RuntimeException('No se pudo guardar el reporte firmado.');
            }

            $rutaPublica = str_replace('public/', 'storage/', $rutaBase) . '/' . $nombreArchivo;
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $rutaPublica;

        } else {
            $validatedData['Detalles_Generales']['Reporte_Firmado'] = $detallesActuales['Reporte_Firmado'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN QR PUBLICO
        |--------------------------------------------------------------------------
        */

        // Solo generarlo si no existe
        $equipoActual = json_decode($Reporte->Datos_Equipo, true) ?: [];
        $validatedData['Datos_Equipo']['QR_TOKEN'] = !empty($equipoActual['QR_TOKEN']) ? $equipoActual['QR_TOKEN'] : (string) Str::uuid();
        /*
        |--------------------------------------------------------------------------
        | DATOS PARA CREAR PDF + QR
        |--------------------------------------------------------------------------
        */

        $datosParaCrearQR = [
            'Documentos_Usuario' => $validatedData['Datos_Equipo']['Documentos_Usuario'] ?? null,
            'Contrato' => $Contrato,
            'No_Reporte' => $No_Reporte,
            'idSolicitud' => $idSolicitud,
            'idPenetrante' => $idPenetrante,
            'idRemovedor' => $idRemovedor,
            'idRevelador' => $idRevelador,
            'equiposMedicion' => $validatedData['Datos_Equipo']['EQUIPOS_MEDICION'],
            'idProcedimiento' => $validatedData['Detalles_Generales']['idProcedimiento'] ?? null,

            // Token del QR público
            'qr_token' => $validatedData['Datos_Equipo']['QR_TOKEN'],
            'ID_TECNICO' => $this->tecnicoQrSeleccionado($request),
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

        // Ruta del QR (svg/png)
        $validatedData['Datos_Equipo']['QR_PDF'] =
            $resultadoQR['qr'];

        // Ruta del PDF unificado
        $validatedData['Datos_Equipo']['PDF_UNIFICADO'] =
            $resultadoQR['pdf'];

        /*
        |--------------------------------------------------------------------------
        | VARIABLES DE APOYO (OPCIONAL)
        |--------------------------------------------------------------------------
        */

        $QR_PDF =
            $validatedData['Datos_Equipo']['QR_PDF'];

        $PDF_UNIFICADO =
            $validatedData['Datos_Equipo']['PDF_UNIFICADO'];

        $QR_TOKEN =
            $validatedData['Datos_Equipo']['QR_TOKEN'];

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR REPORTE
        |--------------------------------------------------------------------------
        */

        $validatedData['Datos_Equipo']['TABLA_COMBINACION_CONFIG'] = json_encode(
            $this->sanitizarConfiguracionCombinacionTabla($request->input('Tabla_CombinacionConfig', '[]')),
            JSON_UNESCAPED_UNICODE
        );

        $Reporte->update([
            'Detalles_Generales' => json_encode($validatedData['Detalles_Generales']),
            'Datos_Equipo' => json_encode($validatedData['Datos_Equipo'])
        ]);

        $titulos_json = $request->input('titulos_data', '[]');
        $titulos = json_decode($titulos_json, true); // array asociativo
        $datosAgrupados = [];
        
        // 1. Procesar filas SIN título (si existen)
        $sinTituloKey = 'sin_titulo';
        $filasSinTitulo = $request->input("No.$sinTituloKey", []);
        //$longitudesSin = $request->input("Long_Inspecc.$sinTituloKey", []);
        $numFilasSin = count($filasSinTitulo);//agregar

        // 🔹 cuántas filas debe tener cada bloque
        $maxFilasPorBloque = 16; //Agregar 1 + que en create y edit para que la longitud entre en el mismo bloque

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
                $filasPorLongitud = 15;

                for ($i = 0; $i < $numFilasSin; $i++) {
                    $agregarElemento([
                        'tipo' => 'fila',
                        'grupo' => $sinTituloKey,
                        'data' => [
                            'No' => $request->input("No.$sinTituloKey.$i"),
                            'item' => $request->input("item.$sinTituloKey.$i"),
                            'no_junta' => $request->input("no_junta.$sinTituloKey.$i"),
                            'no_indicacion' => $request->input("no_indicacion.$sinTituloKey.$i"),
                            'tipo_indicacion' => $request->input("tipo_indicacion.$sinTituloKey.$i"),
                            'largo' => $request->input("largo.$sinTituloKey.$i"),
                            'ancho' => $request->input("ancho.$sinTituloKey.$i"),
                            'diametro' => $request->input("diametro.$sinTituloKey.$i"),
                            'ht' => $request->input("ht.$sinTituloKey.$i"),
                            'dr' => $request->input("dr.$sinTituloKey.$i"),
                            'declaracion' => $request->input("declaracion.$sinTituloKey.$i"),
                            'long_inspeccionada' => $request->input("long_inspeccionada.$sinTituloKey.$i"),
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

            $filas = $request->input("No.$tituloKey", []);
            $numFilas = count($filas);
        
            //$resultados = [];
        
            for ($i = 0; $i < $numFilas; $i++) {
                $agregarElemento([
                    'tipo' => 'fila',
                    'grupo' => $tituloKey,
                    'data' => [
                    'No' => $request->input("No.$tituloKey.$i"),
                    'item' => $request->input("item.$tituloKey.$i"),
                            'no_junta' => $request->input("no_junta.$tituloKey.$i"),
                    'no_indicacion' => $request->input("no_indicacion.$tituloKey.$i"),
                    'tipo_indicacion' => $request->input("tipo_indicacion.$tituloKey.$i"),
                    'largo' => $request->input("largo.$tituloKey.$i"),
                    'ancho' => $request->input("ancho.$tituloKey.$i"),
                    'diametro' => $request->input("diametro.$tituloKey.$i"),
                    'ht' => $request->input("ht.$tituloKey.$i"),
                    'dr' => $request->input("dr.$tituloKey.$i"),
                            'declaracion' => $request->input("declaracion.$tituloKey.$i"),
                    'long_inspeccionada' => $request->input("long_inspeccionada.$tituloKey.$i"),
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
            'Juntas_Grupo_Re' => json_encode($resultadosOrdenadosPt ?? $bloques, JSON_UNESCAPED_UNICODE)
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
        $contratoRuta = ArchivosReportePt::componente($Contrato);
        $reporteRuta = ArchivosReportePt::componente($No_Reporte);
        $rutaCarpeta = "public/Reportes/F_SGLE_708_02_PT/{$contratoRuta}/{$reporteRuta}/Fotos";

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
            // Las marcas ya están integradas en el PNG: priorizarlo sobre el archivo original.
            if ($request->hasFile("replace_images.$index") && empty($imagesBase64[$index])) {
                // **Reemplazo de imagen existente**
                $newImage = $request->file("replace_images.$index");

                // Conservar el archivo anterior para evitar perderlo si falla el guardado.
                $imageName = 'imagen_' . bin2hex(random_bytes(16)) . '.' . $newImage->getClientOriginalExtension();
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
                $imageName = 'imagen_' . bin2hex(random_bytes(16)) . '.png';
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
            if (isset($existingImages[$index]) || in_array((string) $index, array_map('strval', $deletedImages), true)) {
                continue; // ⛔ ya fue procesada arriba
            }

            if (!empty($base64Image)) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
                $imageName = 'imagen_' . bin2hex(random_bytes(16)) . '.png';
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

        app(\App\Services\PaginacionReportesPnd::class)->guardarConfiguracion($request, (int) $id, 'F-SGLE-708-02-PT');

        return redirect()->route('indexINS2', ['contratoSeleccionado' => $contratoSeleccionado, 'Proyecto' => $Proyecto]);
    }


    public function F_SGLE_708_02_PT($id)
    {
        $data = $this->prepararDatosPdfPt($id);
        $principal = PDF::loadView('Reportes.Reporte17025.PDF.Reporte_F_SGLE_708_02_PT_PDF', $data)
            ->setPaper('letter', 'portrait')->output();
        $fotografias = PDF::loadView('Reportes.Reporte17025.FotosPDF.Reporte_FOTOS_F_SGLE_708_02_PT_PDF', $data)
            ->setPaper('letter', 'portrait')->output();

        // Conserva las dimensiones carta originales; no estira los documentos a A4.
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);
        foreach ([$principal, $fotografias] as $documento) {
            $paginas = $pdf->setSourceFile(StreamReader::createByString($documento));
            for ($pagina = 1; $pagina <= $paginas; $pagina++) {
                $plantilla = $pdf->importPage($pagina);
                $tamano = $pdf->getTemplateSize($plantilla);
                $pdf->AddPage($tamano['orientation'], [$tamano['width'], $tamano['height']]);
                $pdf->useTemplate($plantilla, 0, 0, $tamano['width'], $tamano['height']);
            }
        }
        return response($pdf->Output('Reporte_F_SGLE_708_02_PT.PDF', 'S'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    public static function declaracionPredeterminadaPt(): string
    {
        return '*Este informe es único y exclusivamente válido en su estado original y completo; contando con las firmas de los responsables.
*Los procedimientos utilizados y resultados obtenidos en esta prueba se basan en el cumplimiento de la norma NMX-EC-17025-IMNC-2018.
*Los resultados emitidos se relacionan solamente con el ITEM con Id. interno _____ descrito en el presente informe, no aplica a items similares.
*Los datos emitidos en este iforme provienen del  Reporte de Campo de Líquidos Penetrantes con Folio _______.
*Los datos referentes al ITEM son proporcionados por el cliente y plasmados en el reporte de campo y el informe de ensayo de la misma forma.
*Los resultados se expresan de acuerdo con la NOM-008-SCFI-NOM-008-SE-2021.
*La incertidumbre de medida se estima en base a la norma NMX-CH-140-IMNC-2002 "Guía para la evaluación de la incertidumbre de medida en los resultados de las mediciones".
*La incertidumbre de medida se expresa cuando el cliente lo solicite. 
*La incertidumbre del laboratorio será informada con un factor de cobertura k= 2 , es decir con un 95.45% de confianza.
*Las mediciones se realizan en unidades del SI, sin embargo se realiza la converción de milimetros a pulgadas, siendo el factor 1 in = 25.4 mm.
*Los resultados de las mediciones serán evaluados de acuerdo con los criterios de aceptación dentro del alcance del laboratorio.
*La regla de decisión será aplicada a los resultados de la medición para determinar si se ecuentra dentro o fuera de los criterios de aceptación.
*Cuando se menciona en el informe la fecha próxima de la siguiente prueba, es a solicitud del cliente.
*El laboratorio aplica la declaración binaria con zona de seguridad a los resultados obtenidos a menos que el cliente solicite y/o prescribe una regla de desición o criterios de aceptación y/o rechazo.';
    }

    public static function paginasItemsPt(array $items): array
    {
        return array_chunk($items, 10) ?: [[]];
    }

    public static function paginasTextoHoja2Pt(array $equipo): array
    {
        $declaracion = $equipo['DECLARACION_RESULTADOS'] ?? '';
        if ($declaracion === '') $declaracion = self::declaracionPredeterminadaPt();
        $secciones = [
            'declaracion' => self::lineasTextoPt($declaracion, 498),
            'adiciones' => self::lineasTextoPt($equipo['ADICIONES_METODO'] ?? '', 498),
        ];
        // Reservar espacio suficiente para diez ítems, consumibles y firmas.
        if (count($secciones['declaracion']) + count($secciones['adiciones']) <= 22) {
            return [['declaracion' => implode("\n", $secciones['declaracion']), 'adiciones' => implode("\n", $secciones['adiciones']), 'conItems' => true]];
        }
        $paginas = [];
        foreach ($secciones as $clave => $lineas) {
            foreach (array_chunk($lineas, 60) as $trozo) {
                $paginas[] = [$clave => implode("\n", $trozo), 'conItems' => false];
            }
        }
        return $paginas;
    }

    /** El formato ordenado es opcional para clientes anteriores; nunca convierte longitudes antiguas. */
    public static function validarResultadosOrdenadosPt(Request $request): ?array
    {
        if (!$request->exists('resultados_ordenados_pt')) return null;
        $json = $request->input('resultados_ordenados_pt');
        $datos = is_string($json) ? json_decode($json, true) : null;
        $reglas = [
            'resultados' => 'required|array|max:2000',
            'resultados.*.tipo' => 'required|in:fila,titulo,longitud,resumen',
            'resultados.*.grupo' => 'required|string|max:255',
            'resultados.*.texto' => 'nullable|string|max:2000',
            'resultados.*.valor' => 'nullable|string|max:255',
            'resultados.*.incertidumbre' => 'nullable|string|max:255',
            'resultados.*.total' => 'nullable|string|max:255',
            'resultados.*.data' => 'required_if:resultados.*.tipo,fila|array',
        ];
        foreach (['No', 'item', 'no_junta', 'no_indicacion', 'tipo_indicacion', 'largo', 'ancho', 'diametro', 'ht', 'dr', 'declaracion', 'long_inspeccionada'] as $campo) {
            $reglas["resultados.*.data.$campo"] = 'nullable|string|max:2000';
        }
        $validados = \Illuminate\Support\Facades\Validator::make(['resultados' => $datos], $reglas)->validate()['resultados'];
        return array_chunk($validados, 16);
    }

    /** Cada resumen cierra su página; las páginas sin resumen conservan el cálculo histórico. */
    public static function paginasResultadosPt(array $resultados): array
    {
        $paginas = [];
        $pagina = [];
        $resumenes = [];
        foreach ($resultados as $elemento) {
            if (($elemento['tipo'] ?? '') === 'resumen') {
                $paginas[] = $pagina;
                $resumenes[] = $elemento;
                $pagina = [];
                continue;
            }
            if (count($pagina) >= 20) {
                $paginas[] = $pagina;
                $resumenes[] = null;
                $pagina = [];
            }
            $pagina[] = $elemento;
        }
        if ($pagina || !$paginas) {
            $paginas[] = $pagina;
            $resumenes[] = null;
        }
        return [$paginas, $resumenes];
    }

    /** Recuperación de datos exclusiva del PDF; no modifica la captura ni los registros. */
    private function prepararDatosPdfPt($id): array
    {
        $reporte = reporte::findOrFail($id);
        [$generales, $equipo] = self::adaptarDatosPt(
            json_decode($reporte->Detalles_Generales, true) ?: [],
            json_decode($reporte->Datos_Equipo, true) ?: []
        );
        $registroFirmas = Firma_Reporte::where('idReportes', $id)->first();
        $firmas = $registroFirmas ? (json_decode($registroFirmas->Firmas, true) ?: []) : [];
        if (empty($firmas['NOMBRE_TECNICO']) && !empty($firmas['ID_TECNICO'])) {
            $firmas['NOMBRE_TECNICO'] = Usuario::find($firmas['ID_TECNICO'])?->name ?? '';
        }
        $registroResultados = Grupo_Juntas_Detalles_Re::where('idReportes', $id)->first();
        $bloques = $registroResultados ? (json_decode($registroResultados->Juntas_Grupo_Re, true) ?: []) : [];
        $resultados = [];
        foreach ($bloques as $bloque) {
            if (!is_array($bloque)) continue;
            foreach ($bloque as $elemento) {
                if (!is_array($elemento)) continue;
                if (($elemento['tipo'] ?? '') === 'fila') {
                    $elemento['data']['no_junta'] ??= $elemento['data']['componente'] ?? '';
                    $elemento['data']['declaracion'] ??= $elemento['data']['evaluacion'] ?? '';
                }
                if (in_array($elemento['tipo'] ?? '', ['fila', 'titulo', 'longitud', 'resumen'], true)) $resultados[] = $elemento;
            }
        }
        $camposItems = ['No', 'Item', 'Id_aico', 'MMN', 'Colada', 'Trazabilidad', 'Isometrico'];
        $columnasItems = [];
        foreach ($camposItems as $campo) {
            $columnasItems[$campo] = array_values((array) ($equipo[$campo] ?? []));
        }
        $items = [];
        $cantidadItems = max(array_map('count', $columnasItems));
        for ($indice = 0; $indice < $cantidadItems; $indice++) {
            $fila = [];
            foreach ($camposItems as $campo) $fila[$campo] = $columnasItems[$campo][$indice] ?? '';
            $items[] = $fila;
        }
        $registroFotos = Fotos_Reporte::where('idReportes', $id)->first();
        $fotos = [];
        foreach (($registroFotos ? (json_decode($registroFotos->Fotos_Reportes, true) ?: []) : []) as $foto) {
            if (!is_array($foto)) continue;
            $ruta = !empty($foto['ruta']) ? $this->resolvePdfPath($foto['ruta']) : null;
            // Mantiene la posición y el comentario aun si falta físicamente una imagen.
            $fotos[] = ['path' => $ruta, 'comment' => $foto['comentario'] ?? ''];
        }
        // La matriz admite 5 equipos, 10 ítems, 20 resultados y 4 fotografías por página.
        // Los datos adicionales continúan con la misma plantilla, sin descartarlos.
        $paginasEquipos = self::paginasEquiposMedicionPt($equipo);
        $paginasItems = self::paginasItemsPt($items);
        $paginasTextoHoja2 = self::paginasTextoHoja2Pt($equipo);
        $cantidadHojas2 = count($paginasItems) + ($paginasTextoHoja2[0]['conItems'] ? 0 : count($paginasTextoHoja2));
        [$paginasResultados, $resumenesResultados] = self::paginasResultadosPt($resultados);
        $paginasFotos = array_chunk($fotos, 4) ?: [[]];
        $totales = [];
        foreach ($paginasResultados as $pagina) {
            $longitudes = array_map(fn($fila) => $fila['data']['long_inspeccionada'] ?? '', array_filter($pagina, fn($fila) => ($fila['tipo'] ?? '') === 'fila'));
            $sumas = [];
            foreach ($longitudes as $longitud) {
                if (preg_match('/^\s*(\d+(?:[.,]\d+)?)\s*(m(?:2|²)?)?\s*$/u', (string) $longitud, $coincidencia)) {
                    $unidad = $coincidencia[2] ?? '';
                    $sumas[$unidad] = ($sumas[$unidad] ?? 0) + (float) str_replace(',', '.', $coincidencia[1]);
                }
            }
            $total = [];
            foreach ($sumas as $unidad => $suma) $total[] = rtrim(rtrim(number_format($suma, 3, '.', ''), '0'), '.') . ($unidad ? ' ' . $unidad : '');
            $totales[] = implode(' / ', $total);
        }
        $recursos = [];
        foreach (['aico' => 'logo-aico.png', 'ema' => 'logo-ema.jpeg', 'superior' => 'franja-superior.png', 'inferior' => 'franja-inferior.png', 'marca' => 'marca-agua.png'] as $clave => $archivo) {
            $ruta = public_path('images/Reportes17025/PT/' . $archivo);
            $tipo = str_ends_with($archivo, '.jpeg') ? 'image/jpeg' : 'image/png';
            $recursos[$clave] = 'data:' . $tipo . ';base64,' . base64_encode(file_get_contents($ruta));
        }
        $paginaInicialFotos = count($paginasEquipos) + $cantidadHojas2 + count($paginasResultados) + 1;
        return [
            'Detalles_Generales' => $generales, 'Datos_Equipo' => $equipo, 'Firmas_Reportes' => $firmas,
            'paginasEquiposPt' => $paginasEquipos, 'paginasItemsPt' => $paginasItems, 'paginasTextoHoja2Pt' => $paginasTextoHoja2,
            'paginasResultadosPt' => $paginasResultados, 'paginasFotosPt' => $paginasFotos,
            'totalesResultadosPt' => $totales, 'resumenesResultadosPt' => $resumenesResultados, 'paginaInicialFotosPt' => $paginaInicialFotos,
            'totalPaginasPt' => $paginaInicialFotos - 1 + count($paginasFotos), 'recursosFormatoPt' => $recursos,
            'incertidumbrePt' => $equipo['Incertidumbre'] ?? $equipo['INCERTIDUMBRE'] ?? $equipo['incertidumbre'] ?? '',
        ];
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
