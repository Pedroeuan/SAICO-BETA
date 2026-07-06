<?php

namespace App\Models\Procedimientos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    protected $fillable = [
        'idProcedimiento',
        'Nombre',
        'PDF',
    ];
    protected $table = 'procedimiento';
    protected $primaryKey = 'idProcedimiento';
    public $timestamps = false;

    use HasFactory;

    public function formatos()
{
    return $this->hasMany(Formato::class, 'idProcedimiento', 'idProcedimiento');
}
}
