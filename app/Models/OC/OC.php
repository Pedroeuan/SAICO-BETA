<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OC extends Model
{
    use HasFactory;
    
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idOC',
        'Contrato',
        'Tipo_servicio',
        'idClientes',
        'Fecha_solicitud',
        'Lugar_trabajo',
        'Num_OC',
        'Requisicion',
        'Proyecto',
        'Estatus',
        'OC_archivo',
        'Revision_Marcas',
        'Revision_Autor',
        'Revision_At',
        // El historial de comentarios vive en OC_Comentarios.
    ];
    protected $table = 'OC';
    protected $primaryKey = 'idOC';
    public $timestamps = false; 

    public function detalles_OC()
    {
        return $this->hasMany(detallesOC::class, 'idOC', 'idOC');
    }

    public function comentarios()
    {
        return $this->hasMany(OCComentario::class, 'idOC', 'idOC')
            ->orderBy('created_at')
            ->orderBy('idComentario');
    }

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'idClientes', 'idClientes');
    }

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['Fecha_solicitud'])->format('d-m-Y');
    }
}
