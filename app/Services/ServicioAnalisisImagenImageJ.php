<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class ServicioAnalisisImagenImageJ
{
    /**
     * Obtiene el histograma exacto que produce ImageJ después de convertir la imagen a 8 bits.
     * Este método es temporal: no crea registros ni conserva archivos del usuario.
     */
    public function obtenerHistograma8Bit(UploadedFile $imagen): array
    {
        // Cada ejecución usa un UUID para impedir colisiones entre usuarios o peticiones simultáneas.
        $token = (string) Str::uuid();
        $directorioTrabajo = storage_path("app/imagej-work/{$token}");
        File::ensureDirectoryExists($directorioTrabajo);
        $extension = strtolower($imagen->getClientOriginalExtension() ?: 'jpg');
        $rutaOriginal = "{$directorioTrabajo}/original.{$extension}";
        $rutaResultado = "{$directorioTrabajo}/histograma.txt";
        $rutaMacro = "{$directorioTrabajo}/histograma.ijm";

        try {
            // UploadedFile se mueve al espacio temporal aislado; el bloque finally lo elimina siempre.
            $imagen->move($directorioTrabajo, basename($rutaOriginal));
            $originalMacro = $this->rutaMacro($rutaOriginal);
            $resultadoMacro = $this->rutaMacro($rutaResultado);
            // El macro trabaja sin interfaz, convierte a 8 bits y serializa las 256 frecuencias.
            File::put($rutaMacro, <<<IJM
// Macro temporal para obtener el histograma idéntico al mostrado por Fiji/ImageJ.
setBatchMode(true);
open("{$originalMacro}");
run("8-bit");
getDimensions(ancho, alto, canales, cortes, cuadros);
getHistogram(valores, cantidades, 256);
serie = "";
for (i = 0; i < 256; i++) {
    serie = serie + d2s(cantidades[i], 0);
    if (i < 255) serie = serie + ",";
}
File.saveString("ancho=" + ancho + "\\nalto=" + alto + "\\nhistograma=" + serie + "\\n", "{$resultadoMacro}");
close();
setBatchMode(false);
IJM);

            // Se invoca directamente el Java incluido en Fiji para evitar abrir la aplicación de escritorio.
            $proceso = new Process([
                $this->resolverJava(),
                '-jar',
                $this->resolverJarImageJ(),
                '-batch',
                $rutaMacro,
            ]);
            $proceso->setTimeout(max(10, (int) config('imagej.timeout', 300)));
            $proceso->mustRun();

            if (!File::exists($rutaResultado)) {
                throw new RuntimeException('ImageJ no generó el histograma esperado.');
            }

            // El archivo usa el formato clave=valor para mantener el intercambio con el macro simple y estable.
            $valores = [];
            foreach (preg_split('/\R/', trim(File::get($rutaResultado))) as $linea) {
                if (str_contains($linea, '=')) {
                    [$clave, $valor] = explode('=', $linea, 2);
                    $valores[trim($clave)] = trim($valor);
                }
            }
            $histograma = array_map('intval', explode(',', $valores['histograma'] ?? ''));
            if (count($histograma) !== 256) {
                throw new RuntimeException('ImageJ devolvió un histograma incompleto.');
            }

            return [
                'ancho' => (int) ($valores['ancho'] ?? 0),
                'alto' => (int) ($valores['alto'] ?? 0),
                'histograma' => $histograma,
            ];
        } finally {
            // Limpieza garantizada incluso si ImageJ termina con error o excede el tiempo máximo.
            File::deleteDirectory($directorioTrabajo);
        }
    }

    /**
     * Ejecuta la medición definitiva de fracción de fases y conserva sus evidencias.
     *
     * El umbral delimita la fase oscura (perlita); la ferrita se obtiene como complemento a 100.
     * La fase seleccionada solo define qué desea revisar el técnico en la interfaz.
     */
    public function procesarFraccionFases(
        UploadedFile $imagen,
        int $umbralMinimo,
        int $umbralMaximo,
        string $faseSeleccionada,
        int $usuarioId
    ): array {
        if ($umbralMinimo > $umbralMaximo) {
            throw new RuntimeException('El umbral mínimo no puede ser mayor que el máximo.');
        }
        if (!in_array($faseSeleccionada, ['perlita', 'ferrita'], true)) {
            throw new RuntimeException('La fase seleccionada no es válida.');
        }

        // El mismo token identifica los archivos, el resultado JSON y la posterior asociación con el reporte.
        $token = (string) Str::uuid();
        $directorioTrabajo = storage_path("app/imagej-work/{$token}");
        File::ensureDirectoryExists($directorioTrabajo);

        $extension = strtolower($imagen->getClientOriginalExtension() ?: 'jpg');
        $extension = $extension === 'jpeg' ? 'jpg' : $extension;
        $rutaOriginal = "{$directorioTrabajo}/original.{$extension}";
        $rutaVisual = "{$directorioTrabajo}/imagen-visual.png";
        $rutaGrises = "{$directorioTrabajo}/imagen-8-bit.png";
        $rutaBinaria = "{$directorioTrabajo}/imagen-binaria.png";
        $rutaResultado = "{$directorioTrabajo}/resultado.txt";
        $rutaMacro = "{$directorioTrabajo}/fraccion-fases.ijm";
        $directorioPublico = null;

        try {
            // Primero se preparan el original y el macro que reproducirá el flujo manual del técnico.
            $imagen->move($directorioTrabajo, basename($rutaOriginal));
            File::put($rutaMacro, $this->crearMacro(
                $rutaOriginal,
                $rutaVisual,
                $rutaGrises,
                $rutaBinaria,
                $rutaResultado,
                $umbralMinimo,
                $umbralMaximo
            ));

            // ImageJ se ejecuta en modo batch/headless; no aparece ninguna ventana en el escritorio.
            $proceso = new Process([
                $this->resolverJava(),
                '-jar',
                $this->resolverJarImageJ(),
                '-batch',
                $rutaMacro,
            ]);
            $proceso->setTimeout(max(10, (int) config('imagej.timeout', 300)));
            $proceso->mustRun();

            if (
                !File::exists($rutaResultado)
                || !File::exists($rutaVisual)
                || !File::exists($rutaGrises)
                || !File::exists($rutaBinaria)
            ) {
                throw new RuntimeException('ImageJ no generó los archivos esperados.');
            }

            // Solo después de verificar la salida se publican las evidencias permanentes.
            $resultado = $this->leerResultado($rutaResultado);
            $directorioPublico = "Reportes/Analisis_Imagen/{$usuarioId}/{$token}";
            $archivos = [
                'original' => ["original.{$extension}", $rutaOriginal],
                // PNG compatible con navegador y DomPDF, incluso cuando el original es TIFF.
                'imagen_visual' => ['imagen-visual.png', $rutaVisual],
                'imagen_8_bit' => ['imagen-8-bit.png', $rutaGrises],
                'imagen_binaria' => ['imagen-binaria.png', $rutaBinaria],
            ];

            // Se conserva original, copia visible, 8 bits y máscara binaria para auditoría completa.
            $rutas = [];
            foreach ($archivos as $clave => [$nombre, $origen]) {
                $ruta = "{$directorioPublico}/{$nombre}";
                Storage::disk('public')->put($ruta, File::get($origen));
                $rutas[$clave] = 'storage/' . str_replace('\\', '/', $ruta);
            }

            // En un material bifásico, la fase clara es el complemento exacto de la fase oscura.
            $porcentajeOscuro = round($resultado['porcentaje_seleccionado'], 3);
            $porcentajeClaro = round(100 - $resultado['porcentaje_seleccionado'], 3);
            $metadata = [
                'version' => 1,
                'token' => $token,
                'usuario_id' => $usuarioId,
                'archivo_original' => $imagen->getClientOriginalName(),
                'umbral_minimo' => $umbralMinimo,
                'umbral_maximo' => $umbralMaximo,
                'fase_seleccionada' => $faseSeleccionada,
                'metodo_medicion' => 'ImageJ Analyze > Set Measurements > Area Fraction > Measure',
                'porcentaje_perlita' => $porcentajeOscuro,
                'porcentaje_ferrita' => $porcentajeClaro,
                'ancho' => $resultado['ancho'],
                'alto' => $resultado['alto'],
                'rutas' => $rutas,
                'procesado_en' => now()->toIso8601String(),
            ];

            // resultado.json es la fuente confiable que luego resuelve el controlador mediante el token.
            Storage::disk('public')->put(
                "{$directorioPublico}/resultado.json",
                json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );

            return $this->agregarUrls($metadata);
        } catch (\Throwable $error) {
            // Si una escritura falla a mitad del proceso, no se dejan evidencias incompletas publicadas.
            if ($directorioPublico !== null) {
                Storage::disk('public')->deleteDirectory($directorioPublico);
            }
            throw $error;
        } finally {
            // Las copias de trabajo y el macro nunca permanecen en storage/app/imagej-work.
            File::deleteDirectory($directorioTrabajo);
        }
    }

    /** Recupera un análisis persistido y verifica que pertenezca al usuario actual. */
    public function obtenerPorToken(string $token, int $usuarioId): array
    {
        if (!Str::isUuid($token)) {
            throw new RuntimeException('El identificador del análisis no es válido.');
        }

        $ruta = "Reportes/Analisis_Imagen/{$usuarioId}/{$token}/resultado.json";
        if (!Storage::disk('public')->exists($ruta)) {
            throw new RuntimeException('No se encontró el análisis de imagen solicitado.');
        }

        $metadata = json_decode(Storage::disk('public')->get($ruta), true);
        if (!is_array($metadata) || (int) ($metadata['usuario_id'] ?? 0) !== $usuarioId) {
            throw new RuntimeException('El análisis de imagen no pertenece al usuario actual.');
        }

        return $metadata;
    }

    /**
     * Localiza java.exe: primero respeta IMAGEJ_JAVA y después busca el JDK/JRE incluido en Fiji.
     */
    private function resolverJava(): string
    {
        $configurado = config('imagej.java');
        if (is_string($configurado) && $configurado !== '' && File::exists($configurado)) {
            return $configurado;
        }

        $home = rtrim((string) config('imagej.home'), '\\/');
        $candidatos = glob($home . '/java/win64/*/bin/java.exe') ?: [];
        if ($candidatos === []) {
            $candidatos = glob($home . '/java/win64/*/jre/bin/java.exe') ?: [];
        }

        if ($candidatos === []) {
            throw new RuntimeException('No se encontró Java dentro de Fiji. Configure IMAGEJ_JAVA.');
        }

        return $candidatos[0];
    }

    /**
     * Localiza el motor clásico ij-*.jar: permite una ruta explícita o autodetección por versión.
     */
    private function resolverJarImageJ(): string
    {
        $configurado = config('imagej.jar');
        if (is_string($configurado) && $configurado !== '' && File::exists($configurado)) {
            return $configurado;
        }

        $home = rtrim((string) config('imagej.home'), '\\/');
        $candidatos = glob($home . '/jars/ij-*.jar') ?: [];
        rsort($candidatos, SORT_NATURAL);

        if ($candidatos === []) {
            throw new RuntimeException('No se encontró ij-*.jar dentro de Fiji. Configure IMAGEJ_JAR.');
        }

        return $candidatos[0];
    }

    /** Construye el macro que reproduce 8-bit, Threshold, Apply, Area Fraction y Measure. */
    private function crearMacro(
        string $original,
        string $visual,
        string $grises,
        string $binaria,
        string $resultado,
        int $minimo,
        int $maximo
    ): string {
        $original = $this->rutaMacro($original);
        $visual = $this->rutaMacro($visual);
        $grises = $this->rutaMacro($grises);
        $binaria = $this->rutaMacro($binaria);
        $resultado = $this->rutaMacro($resultado);

        return <<<IJM
// Reproduce el procedimiento documentado por el técnico sin mostrar ventanas.
setBatchMode(true);
open("{$original}");
// Copia PNG compatible para vista y PDF; el archivo original se conserva por separado.
saveAs("PNG", "{$visual}");
// Image > Type > 8-bit.
run("8-bit");
saveAs("PNG", "{$grises}");
getDimensions(ancho, alto, canales, cortes, cuadros);
// Image > Adjust > Threshold y Apply.
setThreshold({$minimo}, {$maximo});
setOption("BlackBackground", true);
run("Convert to Mask");
// Analyze > Set Measurements > Area Fraction y Analyze > Measure.
run("Set Measurements...", "area area_fraction redirect=None decimal=3");
run("Measure");
porcentaje = getResult("%Area", nResults - 1);
// Convención de evidencia: fase oscura negra y fase clara blanca.
run("Invert");
saveAs("PNG", "{$binaria}");
File.saveString("porcentaje_seleccionado=" + d2s(porcentaje, 6) + "\\nancho=" + ancho + "\\nalto=" + alto + "\\n", "{$resultado}");
close();
setBatchMode(false);
IJM;
    }

    /** Escapa rutas de Windows para que el lenguaje de macros de ImageJ pueda leerlas. */
    private function rutaMacro(string $ruta): string
    {
        return str_replace(['\\', '"'], ['/', '\\"'], $ruta);
    }

    /** Convierte la salida clave=valor del macro en datos tipados para Laravel. */
    private function leerResultado(string $ruta): array
    {
        $valores = [];
        foreach (preg_split('/\R/', trim(File::get($ruta))) as $linea) {
            if (!str_contains($linea, '=')) {
                continue;
            }
            [$clave, $valor] = explode('=', $linea, 2);
            $valores[trim($clave)] = trim($valor);
        }

        if (!isset($valores['porcentaje_seleccionado'], $valores['ancho'], $valores['alto'])) {
            throw new RuntimeException('ImageJ devolvió un resultado incompleto.');
        }

        return [
            'porcentaje_seleccionado' => (float) $valores['porcentaje_seleccionado'],
            'ancho' => (int) $valores['ancho'],
            'alto' => (int) $valores['alto'],
        ];
    }

    /** Agrega URLs web sin reemplazar las rutas relativas que se guardan en el reporte. */
    private function agregarUrls(array $metadata): array
    {
        $metadata['urls'] = [];
        foreach (($metadata['rutas'] ?? []) as $clave => $ruta) {
            $metadata['urls'][$clave] = asset($ruta);
        }

        return $metadata;
    }
}
