<?php

namespace App\Models\Catalogo_OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogo_OC extends Model
{
    //
    protected $fillable = [
        'idCatalogo_OC',
        'Nombre',
        'Descripcion',
        'Unidad',
        'Imagen',
    ];
    protected $table = 'Catalogo_OC';
    protected $primaryKey = 'idCatalogo_OC';
    public $timestamps = false;

    use HasFactory;
}
