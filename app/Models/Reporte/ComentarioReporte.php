<?php

namespace App\Models\Reporte;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Reporte\reporte;

class ComentarioReporte extends Model
{
    use HasFactory;

    protected $table = 'comentarios_reporte';
    protected $primaryKey = 'idComentarios';

    protected $fillable = [
        'idReportes',
        'comentario',
        'autor',
        'email',
        'tipo_autor',
        'idClientes',
        'idUsuario',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación inversa con reporte
    public function reporte()
    {
        return $this->belongsTo(reporte::class, 'idReportes', 'idReportes');
    }
}
