<?php

namespace App\Services\Publicaciones;

use App\Models\Publicacion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PublicacionService
{
    /**
     * Crea una publicación y dispara la publicación en redes sociales.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crear(array $datos, ?UploadedFile $imagen): Publicacion
    {
        $publicacion = DB::transaction(function () use ($datos, $imagen): Publicacion {
            $payload = $this->normalizarDatos($datos, true);

            if ($imagen instanceof UploadedFile) {
                $payload['imagen'] = $this->subirImagen($imagen);
            }

            return Publicacion::create($payload);
        });

        if (!$this->autopublicacionHabilitada()) {
            $publicacion->forceFill([
                'resultado_publicacion' => [
                    '_general' => [
                        'exito' => false,
                        'post_id' => null,
                        'red' => 'general',
                        'error' => 'Publicacion automatica deshabilitada hasta configurar credenciales y entorno Python.',
                    ],
                ],
                'publicado_en_redes' => false,
                'publicado_at' => null,
            ])->save();

            return $publicacion->fresh();
        }

        try {
            $resultado = $this->ejecutarScriptPython($publicacion->id);
            $publicacion->forceFill([
                'resultado_publicacion' => $resultado,
            ])->save();
        } catch (Throwable $exception) {
            Log::channel('publicaciones')->warning('La publicacion se guardo pero fallo la publicacion en redes.', [
                'publicacion_id' => $publicacion->id,
                'error' => $exception->getMessage(),
            ]);

            $publicacion->forceFill([
                'resultado_publicacion' => [
                    '_general' => [
                        'exito' => false,
                        'post_id' => null,
                        'red' => 'general',
                        'error' => $exception->getMessage(),
                    ],
                ],
                'publicado_en_redes' => false,
                'publicado_at' => null,
            ])->save();
        }

        return $publicacion->fresh();
    }

    /**
     * Actualiza una publicación y opcionalmente la republica en redes.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizar(Publicacion $pub, array $datos, ?UploadedFile $imagen): Publicacion
    {
        $pub = DB::transaction(function () use ($pub, $datos, $imagen): Publicacion {
            $payload = $this->normalizarDatos($datos);
            $republicar = (bool) ($payload['republicar_redes'] ?? false);
            unset($payload['republicar_redes']);

            if ($imagen instanceof UploadedFile) {
                $rutaAnterior = $pub->imagen;
                $payload['imagen'] = $this->subirImagen($imagen);

                if ($rutaAnterior) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }

            $pub->fill($payload);
            $pub->save();

            $pub->setAttribute('debe_republicar', $republicar);

            return $pub;
        });

        if ((bool) $pub->getAttribute('debe_republicar')) {
            if (!$this->autopublicacionHabilitada()) {
                Log::channel('publicaciones')->info('Se omite republicacion automatica por configuracion deshabilitada.', [
                    'publicacion_id' => $pub->id,
                ]);

                return $pub->fresh();
            }

            try {
                $resultado = $this->ejecutarScriptPython($pub->id);
                $pub->forceFill([
                    'resultado_publicacion' => $resultado,
                ])->save();
            } catch (Throwable $exception) {
                Log::channel('publicaciones')->warning('La publicacion se actualizo pero fallo la republicacion en redes.', [
                    'publicacion_id' => $pub->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $pub->fresh();
    }

    /**
     * Elimina lógicamente una publicación.
     */
    public function eliminar(Publicacion $pub): void
    {
        $pub->delete();
    }

    /**
     * Ejecuta el script Python orquestador y persiste el resultado.
     *
     * @return array<string, mixed>
     */
    public function ejecutarScriptPython(int $id): array
    {
        $script = (string) config('publicaciones.python_script');
        $python = (string) config('publicaciones.python_bin', 'python');

        if ($script === '' || !is_file($script)) {
            throw new RuntimeException('No se encontro el script Python configurado para publicaciones.');
        }

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            [$python, $script, (string) $id],
            $descriptorSpec,
            $pipes,
            base_path(),
        );

        if (!is_resource($process)) {
            throw new RuntimeException('No fue posible iniciar el proceso de publicacion en redes.');
        }

        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $salida = '';
        $errores = '';
        $inicio = microtime(true);
        $timeout = 30;

        do {
            $salida .= stream_get_contents($pipes[1]);
            $errores .= stream_get_contents($pipes[2]);
            $estado = proc_get_status($process);

            if (!$estado['running']) {
                break;
            }

            if ((microtime(true) - $inicio) >= $timeout) {
                proc_terminate($process);
                throw new RuntimeException('El script de publicaciones supero el tiempo limite de 30 segundos.');
            }

            usleep(100000);
        } while (true);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);

        if ($exitCode !== 0 && trim($salida) === '') {
            throw new RuntimeException('El script Python fallo: ' . trim($errores));
        }

        $resultado = json_decode(trim($salida), true);

        if (!is_array($resultado)) {
            throw new RuntimeException('El script Python no devolvio un JSON valido. STDERR: ' . trim($errores));
        }

        $publicacion = Publicacion::query()->findOrFail($id);
        $publicacion->forceFill([
            'resultado_publicacion' => $resultado,
            'publicado_en_redes' => collect($resultado)->contains(fn (mixed $item): bool => is_array($item) && (bool) ($item['exito'] ?? false)),
            'publicado_at' => now(),
        ])->save();

        Log::channel('publicaciones')->info('Resultado de publicacion en redes.', [
            'publicacion_id' => $id,
            'resultado' => $resultado,
            'stderr' => trim($errores),
        ]);

        return $resultado;
    }

    /**
     * Sube y optimiza una imagen al disco público.
     */
    public function subirImagen(UploadedFile $archivo): string
    {
        $this->validarArchivoImagen($archivo);

        $uuid = (string) Str::uuid();
        $extension = strtolower($archivo->getClientOriginalExtension());
        $nombre = sprintf('%s-%s.%s', $uuid, now()->timestamp, $extension);
        $rutaRelativa = sprintf('publicaciones/%s/%s/%s', now()->format('Y'), now()->format('m'), $nombre);
        $rutaAbsoluta = Storage::disk('public')->path($rutaRelativa);

        $directorio = dirname($rutaAbsoluta);
        if (!is_dir($directorio) && !mkdir($directorio, 0755, true) && !is_dir($directorio)) {
            throw new RuntimeException('No fue posible crear el directorio para almacenar la imagen.');
        }

        $this->redimensionarYGuardarImagen($archivo, $rutaAbsoluta, $extension);

        return $rutaRelativa;
    }

    /**
     * @param  array<string, mixed>  $datos
     * @return array<string, mixed>
     */
    protected function normalizarDatos(array $datos, bool $esNuevo = false): array
    {
        $redes = array_values(array_unique(array_map('strval', $datos['redes'] ?? $datos['redes_objetivo'] ?? [])));

        $payload = [
            'titulo' => (string) $datos['titulo'],
            'contenido' => (string) $datos['contenido'],
            'tipo' => (string) $datos['tipo'],
            'imagen_alt' => $datos['imagen_alt'] ?: null,
            'video' => $datos['video'] ?? null,
            'url_destino' => $datos['url_destino'] ?: null,
            'redes_objetivo' => $redes,
            'activo' => (bool) ($datos['activo'] ?? true),
        ];

        if ($esNuevo) {
            $payload['publicado_en_redes'] = false;
        }

        return $payload;
    }

    protected function validarArchivoImagen(UploadedFile $archivo): void
    {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower($archivo->getClientOriginalExtension());

        if (!in_array($extension, $extensionesPermitidas, true)) {
            throw new RuntimeException('La extension del archivo no esta permitida.');
        }

        if ($archivo->getSize() > (5 * 1024 * 1024)) {
            throw new RuntimeException('La imagen excede el tamano maximo permitido de 5 MB.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $archivo->getRealPath()) : false;

        if ($finfo) {
            finfo_close($finfo);
        }

        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new RuntimeException('El archivo cargado no es una imagen valida.');
        }
    }

    protected function redimensionarYGuardarImagen(UploadedFile $archivo, string $rutaDestino, string $extension): void
    {
        [$ancho, $alto, $tipo] = getimagesize($archivo->getRealPath()) ?: [0, 0, 0];

        if ($ancho < 1 || $alto < 1) {
            throw new RuntimeException('No fue posible leer las dimensiones de la imagen.');
        }

        $origen = match ($tipo) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($archivo->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($archivo->getRealPath()),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($archivo->getRealPath()) : false,
            default => false,
        };

        if ($origen === false) {
            throw new RuntimeException('No fue posible procesar la imagen cargada.');
        }

        $nuevoAncho = $ancho > 1200 ? 1200 : $ancho;
        $nuevoAlto = (int) round(($alto / $ancho) * $nuevoAncho);
        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

        if (in_array($tipo, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($destino, false);
            imagesavealpha($destino, true);
            $transparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
            imagefilledrectangle($destino, 0, 0, $nuevoAncho, $nuevoAlto, $transparente);
        }

        imagecopyresampled($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

        $guardado = match ($extension) {
            'jpg', 'jpeg' => imagejpeg($destino, $rutaDestino, 82),
            'png' => imagepng($destino, $rutaDestino, 6),
            'webp' => function_exists('imagewebp') ? imagewebp($destino, $rutaDestino, 82) : false,
            default => false,
        };

        imagedestroy($origen);
        imagedestroy($destino);

        if ($guardado === false) {
            throw new RuntimeException('No fue posible guardar la imagen optimizada.');
        }
    }

    protected function autopublicacionHabilitada(): bool
    {
        return (bool) config('publicaciones.autopublicar', false);
    }
}
