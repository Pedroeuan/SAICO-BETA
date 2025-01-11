<?php

namespace App\Models\Prueba;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Norma_Codigo\norma_codigo; 

class prueba extends Model
{
    use HasFactory;

    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idPrueba',
        'Nombre',
    ];

    protected $table = 'Prueba';
    protected $primaryKey = 'idPrueba';
    public $timestamps = false;

    // Relación uno a muchos con NormaCodigo
    public function norma_codigo()
    {
        return $this->hasMany(norma_codigo::class, 'idPrueba', 'idPrueba');
    }
}
