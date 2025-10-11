<?php

namespace App\Models\Notificacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Notificacion extends Model
{
    protected $fillable = [
        // Agrega aquí otros campos que necesites permitir en asignación masiva
        'idNotificaciones',
        'users_id',
        'Mensaje_Corto',
        'Mensaje_Largo',
        'url',
        'created_at',
        'updated_at',
    ];
    protected $table = 'notificaciones';
    protected $primaryKey = 'idNotificaciones';

    use HasFactory;

       // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

}
