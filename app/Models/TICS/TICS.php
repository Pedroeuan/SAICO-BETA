<?php

namespace App\Models\TICS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TICS extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idTICS',
        'idGeneral_EyC',
    ];
    protected $table = 'TICS';
    protected $primaryKey = 'idTICS';
    public $timestamps = false;
    use HasFactory;
}
