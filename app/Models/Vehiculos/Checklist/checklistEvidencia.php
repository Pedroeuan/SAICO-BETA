<?php
namespace App\Models\Vehiculos\Checklist;
use Illuminate\Database\Eloquent\Model;

class ChecklistEvidencia extends Model
{
    protected $table = 'checklist_evidencias';

    protected $fillable = [
        'salida_checklist_id',
        'foto',
    ];

    public function checklist()
    {
        return $this->belongsTo(SalidaChecklist::class, 'salida_checklist_id');
    }
}