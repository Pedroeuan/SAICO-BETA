<?php

namespace App\Models\Procesamiento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TrabajoProcesamiento extends Model
{
    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_PROCESANDO = 'procesando';
    public const ESTADO_COMPLETADO = 'completado';
    public const ESTADO_ERROR = 'error';

    protected $table = 'trabajos_procesamiento';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id', 'usuario_id', 'tipo', 'estado', 'mensaje', 'contexto',
        'resultado', 'error', 'iniciado_at', 'completado_at', 'expira_at',
    ];

    protected function casts(): array
    {
        return [
            'contexto' => 'array',
            // resultado se conserva como texto para admitir respuestas grandes sin
            // depender del limite del tipo JSON de distintas versiones de MySQL.
            'iniciado_at' => 'datetime',
            'completado_at' => 'datetime',
            'expira_at' => 'datetime',
        ];
    }

    /** Genera el UUID antes de insertar el trabajo si el llamador no lo proporciono. */
    protected static function booted(): void
    {
        static::creating(function (self $trabajo): void {
            $trabajo->id ??= (string) Str::uuid();
        });
    }

    /** Devuelve el resultado decodificado sin propagar JSON danado al cliente. */
    public function resultadoArray(): ?array
    {
        if (!$this->resultado) {
            return null;
        }

        $resultado = json_decode($this->resultado, true);

        return is_array($resultado) ? $resultado : null;
    }

    /** Marca el inicio real del worker. */
    public function marcarProcesando(string $mensaje = 'Procesando, espere un momento...'): void
    {
        $this->forceFill([
            'estado' => self::ESTADO_PROCESANDO,
            'mensaje' => $mensaje,
            'error' => null,
            'iniciado_at' => now(),
        ])->save();
    }

    /** Guarda de forma atomica la salida publica del proceso. */
    public function marcarCompletado(array $resultado, string $mensaje = 'Procesado correctamente.'): void
    {
        $this->forceFill([
            'estado' => self::ESTADO_COMPLETADO,
            'mensaje' => $mensaje,
            'resultado' => json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'error' => null,
            'completado_at' => now(),
        ])->save();
    }

    /** Registra el fallo tecnico; la interfaz solo mostrara el mensaje controlado. */
    public function marcarError(\Throwable|string $error, string $mensaje = 'No fue posible procesar.'): void
    {
        $detalle = $error instanceof \Throwable ? $error->getMessage() : $error;

        $this->forceFill([
            'estado' => self::ESTADO_ERROR,
            'mensaje' => $mensaje,
            'error' => mb_substr($detalle, 0, 65000),
            'completado_at' => now(),
        ])->save();
    }
}
