<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class herramientas extends Model
{
    protected $fillable = [
        'idEquipos_Tools_Complementos',
        'idGeneral_EyC',
        'Garantia',
    ];
    protected $table = 'equipos_tools_complementos';
    protected $primaryKey = 'idEquipos_Tools_Complementos';
    public $timestamps = false; 
    use HasFactory;
}
