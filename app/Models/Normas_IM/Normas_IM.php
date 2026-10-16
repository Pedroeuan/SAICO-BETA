<?php

namespace App\Models\Normas_IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normas_IM extends Model
{
    protected $fillable = [
        'idnormas_im',
        'Nombre_Espe',
        'Variable',
        'Tabla',
        'Observaciones',
        'Tablas_Adicionales',
    ];

    protected $table = 'Normas_IM';

    protected $primaryKey = 'idnormas_im';

    public $timestamps = false;

    protected $casts = ['Tablas_Adicionales' => 'array'];

    use HasFactory;
}
