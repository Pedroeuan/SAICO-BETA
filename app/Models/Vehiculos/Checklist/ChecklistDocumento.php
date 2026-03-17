<?php 
namespace App\Models\Vehiculos\Checklist;

use Illuminate\Database\Eloquent\Model;

class ChecklistDocumento extends Model
{
    protected $fillable = [
        'salida_checklist_id',
        'documento',
        'estatus',
    ];
}
