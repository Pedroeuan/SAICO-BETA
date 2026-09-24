<?php

namespace App\Models\Encuesta;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'encuesta';
    protected $primaryKey = 'idEncuesta';

    protected $fillable = [
        'idOrden_Servicio',
        'idClientes',
        'Detalles_Generales',
        'Preguntas',
        'Promedio',
        'Comentario',
        'Firmas',
        'Estatus',
    ];
}
