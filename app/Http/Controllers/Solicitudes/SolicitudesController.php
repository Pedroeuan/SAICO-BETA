<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitudes\Solicitudes;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        //$generalConEquipos = general_eyc::with('Equipos')->get();
        return view('Solicitud.index', compact('general','generalConCertificados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Solicitud.edit");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSolicitud(Request $request)
    {
        dd('dentro');
       // Iterar sobre los datos recibidos y guardarlos en la base de datos
       foreach ($request->input('cantidad') as $id => $cantidad) {
        $unidad = $request->input("unidad.$id");
        $destino = $request->input("destino.$id");

        // Validar y guardar los datos
        // Aquí puedes agregar tu lógica para guardar los datos en la base de datos
        // Ejemplo:
        // $equipo = Equipo::find($id);
        // $equipo->cantidad = $cantidad;
        // $equipo->unidad = $unidad;
        // $equipo->destino = $destino;
        // $equipo->save();
    }
    return view("Solicitud.index");

    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view("Solicitud.aprobacion");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitudes $solicitudes)
    {
        //
    }
}
