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
    ];

    public function checklist()
    {
        return $this->belongsTo(SalidaChecklist::class,'salida_checklist_id');
    }
}
