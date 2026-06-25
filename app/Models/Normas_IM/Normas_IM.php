<?php

namespace App\Models\Normas_IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normas_IM extends Model
{
    protected $fillable = [
        'idnormas_im',
        'Nombre-Espe',
        'Variable',
        'Tabla',
        'Observaciones',
    ];
    protected $table = 'Normas_IM';
    protected $primaryKey = 'idnormas_im';
    public $timestamps = false;

    use HasFactory;
}
