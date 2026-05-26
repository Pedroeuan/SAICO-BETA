<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicacionMetricaHistorial extends Model
{
    protected $table = 'publicacion_metricas_historial';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'publicacion_id',
        'fecha_corte',
        'reacciones',
        'comentarios',
        'compartidos',
        'alcance',
        'impresiones',
        'clicks',
        'engagement',
        'detalle_json',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_corte' => 'date',
            'reacciones' => 'integer',
            'comentarios' => 'integer',
            'compartidos' => 'integer',
            'alcance' => 'integer',
            'impresiones' => 'integer',
            'clicks' => 'integer',
            'engagement' => 'float',
            'detalle_json' => 'array',
        ];
    }

    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(Publicacion::class, 'publicacion_id');
    }
}
