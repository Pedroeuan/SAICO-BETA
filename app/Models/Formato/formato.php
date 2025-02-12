<?php

namespace App\Models\Formato;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Norma_Codigo\norma_codigo;

class formato extends Model
{
    //
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idFormato',
        'idNorma_codigo',
        'idPrueba',
        'Nombre',
    ];

    protected $table = 'Formato';
    protected $primaryKey = 'idFormato';
    public $timestamps = false;

    // Relación inversa con NormaCodigo
    public function norma_codigo()
    {
        return $this->belongsTo(norma_codigo::class, 'idNorma_codigo', 'idNorma_codigo');
    }

    // Relación inversa con Prueba
    public function prueba()
    {
        return $this->belongsTo(Prueba::class, 'idPrueba', 'idPrueba');
    }

    // Relación uno a muchos con Prueba_Aplica
    public function pruebaAplica()
    {
        return $this->hasMany(Prueba_Aplica::class, 'idFormato', 'idFormato');
    }
}
