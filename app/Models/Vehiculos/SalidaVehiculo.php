<?php

namespace App\Models\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use App\Models\User;

class SalidaVehiculo extends Model
{
    //
    protected $table = 'salidas_vehiculo';

    protected $fillable =[
        'vehiculo_id',
        'chofer_id',
        'solicitado_por',
        'fecha_salida',
        'fecha_regreso',
        'motivo',
        'estatus',
    ];
    public function vehiculo(){
        return $this->belongsTo(Vehiculo::class);
    }
    public function chofer(){
        return $this->belongsTo(User::class,'chofer_id');
    }
    public function solicitante(){
        return $this->belongsTo(User::class,'solicitado_por');//f
    }
    public function checklists(){
        return $this->hasMany(\App\Models\Vehiculos\Checklist\SalidaChecklist::class,'salida_vehiculo_id');
    }
    public function getChecklistSalidaAttribute(){
        return $this->checklists()->where('tipo', 'salida')->with('condicion')->first();
        }

    public function getChecklistEntradaAttribute()
    {
    return $this->checklists->where('tipo', 'entrada')->first();
    }

}
