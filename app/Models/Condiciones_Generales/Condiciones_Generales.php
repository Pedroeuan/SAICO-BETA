<?php

namespace App\Models\Condiciones_Generales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condiciones_Generales extends Model
{
    //
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idCondiciones_Generales',
        'Nombre',
        'Descripcion',
    ];
    protected $table = 'Condiciones_Generales';
    protected $primaryKey = 'idCondiciones_Generales';
}
