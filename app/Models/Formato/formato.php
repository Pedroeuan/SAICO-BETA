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
        'idNorma_Codigo',
        'idPrueba',
        'Nombre',
    ];

    protected $table = 'Formato';
    protected $primaryKey = 'idFormato';
    public $timestamps = false;

    // Relación inversa con NormaCodigo
    public function norma_codigo()
    {
        return $this->belongsTo(norma_codigo::class, 'idNormaCodigo', 'idNormaCodigo');
    }
}
