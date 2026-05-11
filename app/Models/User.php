<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        //Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;
        return $rol;
    }

    public function adminlte_profile_url()
    {
        return 'profile/username';
    }

     // salidas usuario como chofer
    public function salidasComoChofer(){
        return $this->hasMany(\App\Models\Vehiculos\SalidaVehiculo::class, 'chofer_id');
    }

    //salidas usuario como solicitante
    public function salidasComoSolicitante(){
        return $this->hasMany(\App\Models\Vehiculos\SalidaVehiculo::class, 'solicitado_por');
    }

    //validacion de documentos de usuario
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($user){
            //si no tiene pdf
            if(!$user->licencia_pdf){
                $user->licencia_estatus='no_aplica';
                return;
            }

            //si tiene PDF pero no fecha
            if(!$user->licencia_vencimiento){
                $user->licencia_estatus ='no_aplica';
                return;
            }
            $fechaVencimiento = Carbon::parse($user->licencia_vencimiento)->endOfDay();
            $hoy = Carbon::now();

            //si esta vencida 
            if($fechaVencimiento->lt($hoy)){
                $user->licencia_estatus ='vencida';
            }else{
                $user->licencia_estatus = 'vigente';
            }
        });
    }

}
