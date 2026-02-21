<?php

namespace App\Models\Vehiculos\Checklist;
use App\Models\Vehiculos\SalidaVehiculo; 
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculos\Checklist\ChecklistCondicion;
use App\Models\Vehiculos\Checklist\ChecklistDocumento;
use App\Models\Vehiculos\Checklist\ChecklistHerramienta;

class SalidaChecklist extends Model
{
    protected $table ='salidas_checklists';
    
    protected $fillable = [
        'salida_vehiculo_id',
        'tipo',
    ];

    public function salida()
    {
        return $this->belongsTo(SalidaVehiculo::class, 'salida_vehiculo_id');
    }
    public function condicion(){
        return $this->hasOne(ChecklistCondicion::class);
    }
    public function documentos(){
        return $this->hasMany(ChecklistDocumento::class);
    }
    public function herramientas(){
        return $this->hasMany(ChecklistHerramienta::class);
    }
    public function evidencias(){
        return $this->hasMany(checklistEvidencia::class, 'salida_checklist_id');
    }
}
