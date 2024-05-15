<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class equipos extends Model
{
    protected $fillable = [
        'Proceso',
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idGeneral_EyC',
        'Metodo',
        'Tipo_E',
    ];
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
}
