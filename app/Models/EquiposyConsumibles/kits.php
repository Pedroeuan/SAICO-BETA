<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kits extends Model
{
    protected $fillable = [
        'idKits',
        'Nombre',
        'Prueba',
    ];
    protected $table = 'Kits';
    protected $primaryKey = 'idKits';
    public $timestamps = false; 
    use HasFactory;
    
     // Definir la relación de uno a muchos con DetalleSolicitud
    public function detalles_kits()
    {
        return $this->hasMany(detalles_kits::class, 'idKits', 'idKits');
    }
}
