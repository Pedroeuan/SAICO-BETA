<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;
use App\Models\Notificacion\Notificacion;

use App\Http\Controllers\Notificacion\NotificacionController;

class DashboardController extends Controller
{
    public function index()
    {
        // Llamar al método del controlador de Notificaciones
        $notificacionController = new NotificacionController();
        $notificacionController->crearNotificacionesCertificados();
        
        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Obtener otros datos necesarios para el dashboard
        // Por ejemplo, cargar los certificados para mostrarlos en la vista
        $certificados = certificados::all();
        
        // Retornar la vista del dashboard con los datos necesarios
        return view('dashboard', compact('certificados', 'user'));
    }

}
