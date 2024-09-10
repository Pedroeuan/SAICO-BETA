<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    
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
    //public $timestamps = false; 
    use HasFactory;

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])->format('d-m-Y');
    }
}
