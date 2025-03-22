<?php

namespace App\Models\PruebaAplica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Prueba_Aplica extends Model
{
    //
        //
        use HasFactory;

        protected $fillable = [
            // Agrega aquí otros campos que necesites permitir en asignación masiva
            'idPrueba_Aplica',
            'idPrueba',
            'idNorma_Codigo',
            'idFormato',
        ];
    
        protected $table = 'Prueba_Aplica';
        protected $primaryKey = 'idPrueba_Aplica';
        public $timestamps = false;

    // Relación inversa con Prueba
    public function prueba()
    {
        return $this->belongsTo(Prueba::class, 'idPrueba', 'idPrueba');
    }

    // Relación inversa con Norma_Codigo
    public function normaCodigo()
    {
        return $this->belongsTo(Norma_Codigo::class, 'idNorma_Codigo', 'idNorma_Codigo');
    }

    // Relación inversa con Formato
    public function formato()
    {
        return $this->belongsTo(Formato::class, 'idFormato', 'idFormato');
    }

    public function ordenesServicioPrueba()
    {
        return $this->hasMany(OrdenServicioPrueba::class, 'idPrueba_Aplica');
    }
}
