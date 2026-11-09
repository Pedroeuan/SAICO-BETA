<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OCComentario extends Model
{
    use HasFactory;

    protected $table = 'OC_Comentarios';
    protected $primaryKey = 'idComentario';

    protected $fillable = [
        'idOC',
        'tipo',
        'revision',
        'comentario',
        'autor',
        'idUsers',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function oc()
    {
        return $this->belongsTo(OC::class, 'idOC', 'idOC');
    }
}
