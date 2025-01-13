<?php

namespace App\Models\detallesOC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OC\OC; // Importa el modelo OC si está en otro namespace

class detallesOC extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOC',
        'Detalles',
    ];
    protected $table = 'Detalles_OC';
    protected $primaryKey = 'idDetalles_OC';
    public $timestamps = false; 

    public function oc()
    {
        return $this->belongsTo(OC::class, 'idOC', 'idOC');
    }
}
