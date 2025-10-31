<?php

namespace App\Http\Controllers\solicitud_AD;

use App\Models\solicitud_AD\solicitud_AD;
use App\Models\solicitud_AD\users_has_solicitud_AD;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SolicitudADController extends Controller
{
    /**
     * Mostrar todas las solicitudes.
     */
    public function index()
    {
        $user = Auth::user();
        $rol = $user->rol;
        $usuarios=[];
        //$solicitudes = solicitud_AD::all();
        $solicitudes = users_has_solicitud_AD::with('solicitud_AD', 'users')->get(); 
        //dd($solicitudes);

        return view('solicitud_AD.index', compact('solicitudes', 'rol','usuarios'));
    }

    /**
     * Mostrar el formulario para crear una nueva solicitud.
     */

    public function show($id)
    {
        // Opcional: devolver JSON, vista o simplemente vacía
        return abort(404); // si no lo necesitas
    }

    public function create()
    {
        return view('solicitud_AD.create');
    }

    /**
     * Guardar una nueva solicitud en la base de datos.
     */
public function store(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'estatus' => 'required|string',
        'Tema' => 'required|string|max:255',
        'comentario' => 'nullable|string',
    ]);

    $solicitud = new solicitud_AD();
    $solicitud->fecha = $request->fecha;
    $solicitud->estatus = $request->estatus;
    $solicitud->Tema = $request->Tema;
    $solicitud->Comentario = $request->comentario;
    $solicitud->save();

    // Asociar al usuario autenticado
    $user = Auth::user();

    $user_has_solicitud = new users_has_solicitud_AD();
    $user_has_solicitud->users_id = $user->id;
    $user_has_solicitud->idsolicitud_AD = $solicitud->idsolicitud_AD;
    $user_has_solicitud->save();

    return redirect()->route('ADsolicitud.index')->with('success', 'Solicitud creada correctamente.');
}

    public function edit($id)
    {
        // Obtener el usuario autenticado
        $Usuario = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $Usuario->name;
        $rol = Auth::user()->rol;
        // Buscar la solicitud por su ID correcto
        $solicitud = solicitud_AD::where('idsolicitud_AD', $id)->firstOrFail();

        // Obtener todos los usuarios y los asociados a la solicitud
        $usuarios = User::all();
        $usuariosAsociados = users_has_solicitud_AD::where('idsolicitud_AD', $id)->pluck('users_id')->toArray();

        return view('solicitud_AD.edit', compact('solicitud', 'usuarios', 'usuariosAsociados','rol','Usuario','id'));
    }

    /**
     * Actualizar una solicitud existente.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $rol = $user->rol;

        $request->validate([
            'fecha' => 'required|date',
            'Tema' => 'required|string|max:255',
            'estatus' => 'required|string',
            'comentario' => 'nullable|string',
            //'usuarios' => 'required|array|min:1',
        ]);

        // Buscar la solicitud
        //$solicitud = solicitud_AD::where('idsolicitud_AD', $id)->first();
        //Log::info('Solicitud encontrada: ' . ($solicitud ? 'Sí' : 'No'));
        $solicitud = solicitud_AD::find($id);

        // Actualizar los campos
        $solicitud->update([
            'fecha' => $request->input('fecha'),
            'Tema' => $request->input('Tema'),
            'estatus' => $request->input('estatus'),
            'comentario' => $request->input('comentario'),
        ]);


        //$solicitudes = solicitud_AD::all();
        $solicitudes = users_has_solicitud_AD::with('solicitud_AD', 'users')->get(); 
        //dd($solicitudes);

        return view('solicitud_AD.index', compact('solicitudes', 'rol'));
    }
    
        public function actualizar(Request $request, $id)
        {
            $solicitud = solicitud_AD::find($id);
            if (!$solicitud) {
                return response()->json(['success' => false, 'message' => 'Solicitud no encontrada.']);
            }

            $request->validate([
                'estatus' => 'required|string'
            ]);

            $solicitud->estatus = $request->estatus;
            $solicitud->save();

            return response()->json(['success' => true, 'message' => 'Estatus actualizado correctamente.']);
        }


    /**
     * Eliminar una solicitud.
     */
public function destroy($id)
{
    //Log::info('Iniciando proceso de eliminación para solicitud con ID: ' . $id);
    $user_has_solicitud = users_has_solicitud_AD::where('idsolicitud_AD', $id);
    $solicitud = solicitud_AD::find($id);
    //log::info('Eliminando solicitud con ID: ' . $id);

    if (!$user_has_solicitud && !$solicitud) {
        return response()->json(['error' => 'Solicitud no encontrada.'], 404);
    }
    $user_has_solicitud->delete();
    $solicitud->delete();

    return response()->json(['success' => true]);
}

/* Actualizar multiples solicitudes */

public function actualizarMultiple(Request $request)
{
    if (!$request->has('solicitudes') || empty($request->solicitudes)) {
        return response()->json(['success' => false, 'message' => 'No se recibieron solicitudes.']);
    }

    foreach ($request->solicitudes as $dato) {
        $solicitud = \App\Models\solicitud_AD\solicitud_AD::find($dato['id']);

        if ($solicitud) {
            $solicitud->estatus = $dato['estatus'];
            $solicitud->save();
        }
    }

    return response()->json(['success' => true, 'message' => 'Solicitudes actualizadas correctamente.']);
}

}
