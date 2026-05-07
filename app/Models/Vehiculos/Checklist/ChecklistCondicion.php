<?php 
namespace App\Models\Vehiculos\Checklist;
use Illuminate\Database\Eloquent\Model;
class ChecklistCondicion extends Model
{
    protected $table ='checklist_condiciones';

    protected $fillable = [
        'salida_checklist_id', 
        'nivel_gasolina',
        'kilometraje',
        'limpio_exterior',
        'limpio_interior',
        'observaciones',
        'liquido_limpiaparabrisas',
        'aceite',
        'anticongelante',
        'liquido_frenos',
        'estado_llantas',
        'llanta_delantera_izq_calibracion',
        'llanta_delantera_der_calibracion',
        'llanta_trasera_izq_calibracion',
        'llanta_trasera_der_calibracion',
    ];

    public function checklist()
    {
        return $this->belongsTo(SalidaChecklist::class,'salida_checklist_id');
    }
}
