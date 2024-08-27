<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'id',
        'name',
        'email',
        'password',
        'rol',
    ];
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false; 
    use HasFactory;
}
