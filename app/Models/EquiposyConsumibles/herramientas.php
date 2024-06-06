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
    protected $primaryKey = 'idGeneral_EyC';
    public $timestamps = false; 
    use HasFactory;
    
    public function general_eyc()
    {
        return $this->belongsTo(general_eyc::class, 'idGeneral_EyC');
    }
}
