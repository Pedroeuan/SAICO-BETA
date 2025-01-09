<?php

namespace App\Models\Prueba;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class prueba extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idPrueba',
        'Nombre',
    ];

    protected $table = 'Prueba';
    protected $primaryKey = 'idPrueba';
    public $timestamps = false;
}
