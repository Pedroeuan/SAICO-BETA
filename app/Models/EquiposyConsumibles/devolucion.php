<?php

namespace App\Models\EquiposyConsumibles;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class devolucion extends Model
{
    protected $fillable = [
        'idDevoluciones',
        'idManifiestos',
        'idSolicitud',
        'Entrega',
        'Recibe',
    ];
    protected $table = 'Devoluciones';
    protected $primaryKey = 'idDevoluciones';
    public $timestamps = false; 
    
    use HasFactory;
}
