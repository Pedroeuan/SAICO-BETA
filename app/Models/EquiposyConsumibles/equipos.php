<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipos extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idGeneral_EyC',
        'Proceso',
        'idGeneral_EyC',
        'Metodo',
        'Tipo_E',
    ];
    protected $primaryKey = 'idEquipos';
    public $timestamps = false; 
    use HasFactory;
}
