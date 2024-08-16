<?php

namespace App\Models\Clientes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    protected $fillable = [
        'idClientes',
        'Cliente',
        'RFC',
        'Telefono',
        'Correo',
    ];
    protected $table = 'clientes';
    protected $primaryKey = 'idClientes';
    public $timestamps = false;

    use HasFactory;
}
