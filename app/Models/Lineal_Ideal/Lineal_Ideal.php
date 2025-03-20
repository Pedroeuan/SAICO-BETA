<?php

namespace App\Models\Lineal_Ideal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lineal_Ideal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idLineal_Ideal',
        'idOC',
        'idOrden_Servicio',
        'idSolicitud',
        'idReportes',
        'Estatus',
    ];
    protected $table = 'Lineal_Ideal';
    protected $primaryKey = 'idLineal_Ideal';
    public $timestamps = false; 
}
