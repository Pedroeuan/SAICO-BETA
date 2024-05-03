<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipos extends Model
{
    protected $table = 'general_eyc'; // Nombre de la tabla en la base de datos
    use HasFactory;
}
