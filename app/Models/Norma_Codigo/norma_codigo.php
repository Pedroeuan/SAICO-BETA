<?php

namespace App\Models\Norma_Codigo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Prueba\prueba; 
use App\Models\Formato\formato; 

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

    // Relación inversa con Prueba
    public function prueba()
    {
        return $this->belongsTo(prueba::class, 'idPrueba', 'idPrueba');
    }

    // Relación uno a muchos con Formato
    public function formato()
    {
        return $this->hasMany(formato::class, 'idNorma_Codigo', 'idNorma_Codigo');
    }
}
