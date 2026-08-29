<?php

namespace App\Services;

use App\Jobs\Procesamiento\GenerarReportePdfJob;
use App\Models\Procesamiento\TrabajoProcesamiento;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\Reporte\reporte;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Conserva la ultima representacion PDF de un reporte mientras sus datos,
 * archivos de entrada y plantilla permanezcan iguales.
 *
 * Los modelos historicos de reportes no tienen updated_at. Por eso la vigencia
 * se determina mediante una huella del contenido real y no por una fecha.
 */
class ServicioPdfGenerado
{
    private const VERSION = 1;

    /** @var array<string, array<int, string>> */
    private const ARCHIVOS_GENERADOR = [
        '03_B_01' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_03_B_01Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_03_B_01_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_03_B_01_PDF.blade.php',
        ],
        '04_02' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_04_02Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_04_02_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_04_02_PDF.blade.php',
        ],
        '04_03:es' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_04_03Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_04_03_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_04_03_PDF.blade.php',
        ],
        '04_03:en' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_04_03Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_04_B_03_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_04_B_03_PDF.blade.php',
        ],
        '05_B_01' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_05_B_01Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_05_B_01_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_05_B_01_PDF.blade.php',
        ],
        '06_B_01' => [
            'app/Http/Controllers/Reporte/IM/FOR_PIMP_06_B_01Controller.php',
            'resources/views/Reportes/ReportesPDFIM/Reporte_FOR_PIMP_06_B_01_PDF.blade.php',
            'resources/views/Reportes/ReportesFotosPDFIM/Reporte_FOTOS_FOR_PIMP_06_B_01_PDF.blade.php',
            'app/Services/ServicioSerieReportes.php',
        ],
    ];

    public function firma(int $reporteId, string $formato, string $idioma = 'es'): string
    {
        $reporte = reporte::query()->findOrFail($reporteId);
        $grupos = Grupo_Juntas_Detalles_Re::query()
            ->where('idReportes', $reporteId)
            ->orderBy('idGrupo_Juntas_Detalles_Re')
            ->get()
            ->map->getAttributes()
            ->all();
        $firmas = Firma_Reporte::query()
            ->where('idReportes', $reporteId)
            ->orderBy('idFirmas_Reportes')
            ->get()
            ->map->getAttributes()
            ->all();
        $fotos = Fotos_Reporte::query()
            ->where('idReportes', $reporteId)
            ->orderBy('idFotos_Reportes')
            ->get()
            ->map->getAttributes()
            ->all();

        $estado = [
            'version' => self::VERSION,
            'formato' => $formato,
            'idioma' => $idioma,
            'reporte' => $reporte->getAttributes(),
            'grupos' => $grupos,
            'firmas' => $firmas,
            'fotos' => $fotos,
            // La numeracion visible del 06_B_01 depende tambien de cuantos
            // integrantes/paginas tenga el resto de su serie.
            'serie' => $formato === '06_B_01' ? $this->estadoSerie($reporte) : null,
            'archivos_reporte' => $this->metadatosArchivos([$reporte->getAttributes(), $fotos]),
            'generador' => $this->huellasGenerador($formato, $idioma),
        ];

        return hash('sha256', serialize($estado));
    }

    /** @return array<string, mixed>|null */
    private function estadoSerie(reporte $reporteActual): ?array
    {
        $detalles = json_decode((string) $reporteActual->Detalles_Generales, true);
        $uuid = is_array($detalles)
            ? data_get($detalles, 'SERIE_REPORTES.serie_uuid')
            : null;

        if (!is_string($uuid) || $uuid === '') {
            return null;
        }

        $rutaJson = '$.SERIE_REPORTES.serie_uuid';
        $consulta = reporte::query()->whereNotNull('Detalles_Generales');
        if ($consulta->getConnection()->getDriverName() === 'sqlite') {
            $consulta->whereRaw('json_valid(Detalles_Generales) = 1')
                ->whereRaw('json_extract(Detalles_Generales, ?) = ?', [$rutaJson, $uuid]);
        } else {
            $consulta->whereRaw('JSON_VALID(Detalles_Generales) = 1')
                ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(Detalles_Generales, ?)) = ?', [$rutaJson, $uuid]);
        }

        $miembros = $consulta->orderBy('idReportes')->get();
        $ids = $miembros->pluck('idReportes')->map(fn ($id) => (int) $id)->all();

        return [
            'reportes' => $miembros->map->getAttributes()->all(),
            // Estos dos bloques pueden alterar el numero real de paginas de un
            // integrante y, por tanto, el total impreso en toda la serie.
            'grupos' => Grupo_Juntas_Detalles_Re::query()
                ->whereIn('idReportes', $ids)
                ->orderBy('idReportes')
                ->orderBy('idGrupo_Juntas_Detalles_Re')
                ->get()
                ->map->getAttributes()
                ->all(),
            'fotos' => Fotos_Reporte::query()
                ->whereIn('idReportes', $ids)
                ->orderBy('idReportes')
                ->orderBy('idFotos_Reportes')
                ->get()
                ->map->getAttributes()
                ->all(),
        ];
    }

    public function rutaVigente(int $reporteId, string $formato, string $idioma = 'es'): ?string
    {
        $firma = $this->firma($reporteId, $formato, $idioma);
        $ruta = $this->rutaRelativa($reporteId, $formato, $idioma, $firma);

        return Storage::disk('local')->exists($ruta)
            ? Storage::disk('local')->path($ruta)
            : null;
    }

    /**
     * Programa una sola generacion por tecnico y version. Se usa tanto al
     * guardar el 06_B_01 como al abrir cualquiera de los formatos habilitados.
     */
    public function programar(
        int $reporteId,
        string $formato,
        string $idioma,
        int $usuarioId
    ): ?TrabajoProcesamiento {
        if ($this->rutaVigente($reporteId, $formato, $idioma)) {
            return null;
        }

        $firma = $this->firma($reporteId, $formato, $idioma);
        $existente = TrabajoProcesamiento::query()
            ->where('usuario_id', $usuarioId)
            ->where('tipo', 'reporte_pdf')
            ->whereIn('estado', [
                TrabajoProcesamiento::ESTADO_PENDIENTE,
                TrabajoProcesamiento::ESTADO_PROCESANDO,
            ])
            ->latest('created_at')
            ->limit(20)
            ->get()
            ->first(function (TrabajoProcesamiento $candidato) use ($reporteId, $formato, $idioma, $firma): bool {
                return (int) data_get($candidato->contexto, 'reporte_id') === $reporteId
                    && data_get($candidato->contexto, 'formato') === $formato
                    && data_get($candidato->contexto, 'idioma', 'es') === $idioma
                    && data_get($candidato->contexto, 'firma') === $firma;
            });

        if ($existente) {
            return $existente;
        }

        $trabajo = TrabajoProcesamiento::create([
            'id' => (string) Str::uuid(),
            'usuario_id' => $usuarioId,
            'tipo' => 'reporte_pdf',
            'estado' => TrabajoProcesamiento::ESTADO_PENDIENTE,
            'mensaje' => 'Generando reporte PDF...',
            'contexto' => [
                'reporte_id' => $reporteId,
                'formato' => $formato,
                'idioma' => $idioma,
                'firma' => $firma,
            ],
            'expira_at' => now()->addHours(8),
        ]);

        GenerarReportePdfJob::dispatch($trabajo->id);

        return $trabajo;
    }

    /** Guarda atomica y privadamente el PDF; elimina versiones obsoletas del mismo idioma. */
    public function guardar(int $reporteId, string $formato, string $idioma, string $contenido): string
    {
        $firma = $this->firma($reporteId, $formato, $idioma);
        $directorio = $this->directorioRelativo($reporteId, $formato, $idioma);
        $ruta = "{$directorio}/{$firma}.pdf";

        Storage::disk('local')->makeDirectory($directorio);
        Storage::disk('local')->put($ruta, $contenido);

        foreach (Storage::disk('local')->files($directorio) as $archivo) {
            if ($archivo !== $ruta && str_ends_with(strtolower($archivo), '.pdf')) {
                Storage::disk('local')->delete($archivo);
            }
        }

        return Storage::disk('local')->path($ruta);
    }

    private function rutaRelativa(
        int $reporteId,
        string $formato,
        string $idioma,
        string $firma
    ): string {
        return $this->directorioRelativo($reporteId, $formato, $idioma) . "/{$firma}.pdf";
    }

    private function directorioRelativo(int $reporteId, string $formato, string $idioma): string
    {
        $formatoSeguro = preg_replace('/[^A-Za-z0-9_-]/', '_', $formato) ?: 'formato';
        $idiomaSeguro = preg_replace('/[^A-Za-z0-9_-]/', '_', $idioma) ?: 'es';

        return "reportes_generados/{$formatoSeguro}/{$reporteId}/{$idiomaSeguro}";
    }

    /** @return array<string, string> */
    private function huellasGenerador(string $formato, string $idioma): array
    {
        $clave = $formato === '04_03' ? "{$formato}:{$idioma}" : $formato;
        $archivos = self::ARCHIVOS_GENERADOR[$clave] ?? [];
        $archivos[] = 'resources/views/Reportes/partials/firmas_im_pdf.blade.php';

        $huellas = [];
        foreach ($archivos as $archivo) {
            $ruta = base_path($archivo);
            $huellas[$archivo] = is_file($ruta) ? (hash_file('sha256', $ruta) ?: '') : 'ausente';
        }

        return $huellas;
    }

    /**
     * Incluye tamano y fecha de las imagenes/PDF referenciados. Evita leer varios
     * megabytes en cada clic, pero detecta reemplazos realizados por el sistema.
     *
     * @return array<string, array{size:int, mtime:int}>
     */
    private function metadatosArchivos(mixed $valor): array
    {
        $rutas = [];
        $this->recolectarRutas($valor, $rutas);
        ksort($rutas);

        return $rutas;
    }

    /** @param array<string, array{size:int, mtime:int}> $rutas */
    private function recolectarRutas(mixed $valor, array &$rutas): void
    {
        if (is_array($valor)) {
            foreach ($valor as $elemento) {
                $this->recolectarRutas($elemento, $rutas);
            }
            return;
        }

        if (!is_string($valor) || $valor === '') {
            return;
        }

        // Algunos campos contienen arreglos JSON completos dentro de una columna.
        $decodificado = json_decode($valor, true);
        if (is_array($decodificado)) {
            $this->recolectarRutas($decodificado, $rutas);
        }

        $ruta = $this->resolverArchivo($valor);
        if ($ruta === null || isset($rutas[$ruta])) {
            return;
        }

        $rutas[$ruta] = [
            'size' => (int) filesize($ruta),
            'mtime' => (int) filemtime($ruta),
        ];
    }

    private function resolverArchivo(string $valor): ?string
    {
        $valor = trim($valor);
        $candidatas = [];

        if (preg_match('/^[A-Za-z]:[\\\\\/]/', $valor) === 1) {
            $candidatas[] = $valor;
        }

        if (str_starts_with($valor, 'storage/')) {
            $relativa = ltrim(substr($valor, strlen('storage/')), '/\\');
            $candidatas[] = storage_path('app/public/' . $relativa);
            $candidatas[] = public_path('storage/' . $relativa);
        }

        if (str_starts_with($valor, 'public/')) {
            $candidatas[] = storage_path('app/' . ltrim($valor, '/\\'));
        }

        foreach ($candidatas as $candidata) {
            if (is_file($candidata)) {
                return str_replace('\\', '/', $candidata);
            }
        }

        return null;
    }
}
