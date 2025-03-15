<?php

namespace App\Models\OrdenServicio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Firmantes_OS extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idFirmantes_OS',
        'idOrden_Servicio',
        'Nombre_Cargo',
    ];

    protected $table = 'Firmantes_OS';
    protected $primaryKey = 'idFirmantes_OS';
    public $timestamps = false;

}
