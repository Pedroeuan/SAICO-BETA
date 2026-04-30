<?php

namespace App\Models;

use App\Enums\TipoPublicacion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Publicacion extends Model
{
    use SoftDeletes;

    protected $table = 'publicaciones';

    public const STATUS_REDES = [
        'pendiente',
        'exito',
        'parcial',
        'error',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'titulo',
        'slug',
        'contenido',
        'tipo',
        'imagen',
        'imagen_alt',
        'video',
        'url_destino',
        'redes_objetivo',
        'resultado_publicacion',
        'publicado_en_redes',
        'publicado_at',
        'activo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'redes_objetivo' => 'array',
            'resultado_publicacion' => 'array',
            'publicado_en_redes' => 'boolean',
            'activo' => 'boolean',
            'publicado_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $publicacion): void {
            if (blank($publicacion->uuid)) {
                $publicacion->uuid = (string) Str::uuid();
            }

            $publicacion->slug = static::generarSlugUnico($publicacion->titulo);
        });

        static::updating(function (self $publicacion): void {
            if ($publicacion->isDirty('titulo')) {
                $publicacion->slug = static::generarSlugUnico($publicacion->titulo, $publicacion->getKey());
            }
        });
    }

    public function setTituloAttribute(string $value): void
    {
        $this->attributes['titulo'] = trim($value);
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getImagenUrlAttribute(): ?string
    {
        if (blank($this->imagen)) {
            return null;
        }

        if (Str::startsWith($this->imagen, ['http://', 'https://'])) {
            return $this->imagen;
        }

        return Storage::disk('public')->url($this->imagen);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopePublicados(Builder $query): Builder
    {
        return $query->where('publicado_en_redes', true);
    }

    public function scopePorTipo(Builder $query, TipoPublicacion|string $tipo): Builder
    {
        $valor = $tipo instanceof TipoPublicacion ? $tipo->value : $tipo;

        return $query->where('tipo', $valor);
    }

    /**
     * Aquí podrán agregarse relaciones futuras, por ejemplo autor, categorías o auditoría.
     */

    public function resolveRouteBinding($value, $field = null): ?self
    {
        $campo = $field ?? 'slug';

        return $this->withTrashed()->where($campo, $value)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function estadoRedes(): string
    {
        $resultados = $this->resultado_publicacion ?? [];

        if (empty($resultados)) {
            return 'pendiente';
        }

        if (($resultados['_general']['error'] ?? null) === 'Publicacion automatica deshabilitada hasta configurar credenciales y entorno Python.') {
            return 'pendiente';
        }

        $total = count($resultados);
        $exitos = collect($resultados)->filter(fn (array $item): bool => (bool) ($item['exito'] ?? false))->count();

        if ($exitos === 0) {
            return 'error';
        }

        if ($exitos < $total) {
            return 'parcial';
        }

        return 'exito';
    }

    protected static function generarSlugUnico(string $titulo, ?int $ignorarId = null): string
    {
        $base = Str::limit(Str::slug($titulo), 150, '');
        $slug = $base !== '' ? $base : Str::lower(Str::random(10));
        $contador = 1;

        while (static::withTrashed()
            ->when($ignorarId, fn (Builder $query): Builder => $query->whereKeyNot($ignorarId))
            ->where('slug', $slug)
            ->exists()) {
            $sufijo = '-' . $contador;
            $slug = Str::limit($base, 160 - strlen($sufijo), '') . $sufijo;
            $contador++;
        }

        return $slug;
    }
}
