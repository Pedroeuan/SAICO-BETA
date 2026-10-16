<?php

namespace App\Models\Condiciones_Pago;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condiciones_Pago extends Model
{
    //
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idCondiciones_Pago',
        'Nombre',
        'Descripcion',
    ];
    protected $table = 'Condiciones_Pago';
    protected $primaryKey = 'idCondiciones_Pago';
}
