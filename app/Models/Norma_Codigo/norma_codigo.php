<?php

namespace App\Models\Norma_Codigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class norma_codigo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idNorma_Codigo',
        'idPrueba',
        'Nombre',
    ];
    protected $table = 'Norma_Codigo';
    protected $primaryKey = 'idNorma_Codigo';
    public $timestamps = false; 
}
