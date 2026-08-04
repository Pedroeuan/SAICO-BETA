<?php

namespace App\Models\Normas_IM;

use Illuminate\Database\Eloquent\Model;

class PatronGranoIM extends Model
{
    protected $table = 'patrones_grano_im';

    protected $fillable = [
        'nombre',
        'valor_grano',
        'ruta_imagen',
    ];

    protected $casts = [
        'valor_grano' => 'decimal:1',
    ];
}
