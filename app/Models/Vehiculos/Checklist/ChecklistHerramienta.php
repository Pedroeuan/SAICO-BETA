<?php 
namespace App\Models\Vehiculos\Checklist;

use Illuminate\Database\Eloquent\Model;

class ChecklistHerramienta extends Model
{
    protected $fillable = [
        'salida_checklist_id',
        'herramienta',
        'disponible',
    ];
}
