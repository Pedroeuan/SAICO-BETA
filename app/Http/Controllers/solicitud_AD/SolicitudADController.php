<?php

namespace App\Http\Controllers\solicitud_AD;

use App\Models\solicitud_AD\solicitud_AD;
use App\Models\solicitud_AD\users_has_solicitud_AD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SolicitudADController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra todas las solicitudes.
     */
    public function index()
    {
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $solicitudes = solicitud_AD::all();

        return view('solicitud_AD.index', compact('solicitudes'));
    }

    /**
     * Show the form for creating a new resource.
     * (Normalmente no se usa en API REST)
     */
    public function create()
    {
        //
        return view('solicitud_AD.create');
    }

    /**
     * Store a newly created resource in storage.
     * Guarda una nueva solicitud en la base de datos.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $solicitud_AD = new solicitud_AD;

        $solicitud_AD->fecha = $request->input('fecha');

        $solicitud_AD->estatus = $request->input('estatus');

        $solicitud_AD->Tema = $request->input('Tema');

        $solicitud_AD->Comentario = $request->input('comentario');

        $solicitud_AD->save();

        $idSolicitud = $solicitud_AD->idsolicitud_AD;

        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $iduser = $user->id;

        $user_has_solicitud = new users_has_solicitud_AD;

        $user_has_solicitud->users_id = $iduser;
        $user_has_solicitud->idsolicitud_AD = $idSolicitud;
        $user_has_solicitud->save();

        return redirect()->route('ADsolicitud.index');

    }

    /**
     * Display the specified resource.
     * Muestra una solicitud específica.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * (No se usa en API)
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * Actualiza una solicitud existente.
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     * Elimina una solicitud.
     */
    public function destroy($id)
    {

    }
}
