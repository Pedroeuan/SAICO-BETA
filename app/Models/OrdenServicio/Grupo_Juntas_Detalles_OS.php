<?php

namespace App\Models\OrdenServicio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo_Juntas_Detalles_OS extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOrden_Servicio',
        'Juntas_grupo',
    ];

    protected $table = 'Grupo_Juntas_Detalles_OS';
    protected $primaryKey = 'idGrupo_Juntas_Detalles_OS';
    public $timestamps = false;
}
