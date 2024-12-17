<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OC extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOC',
        'Num_OC',
        'Proyecto',
        'Lugar',
        'Fecha_solicitud',
        'Tipo_servicio',
        'Estatus',
        'OC_archivo',
    ];
    protected $table = 'OC';
    protected $primaryKey = 'idOC';
    public $timestamps = false; 

    public function detalles_OC()
    {
        return $this->hasMany(detallesOC::class, 'idOC', 'idOC');
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Fecha_solicitud'])->format('d-m-Y');
    }
}
