<?php

namespace App\Http\Controllers\solicitud_AD;

use App\Models\solicitud_AD\solicitud_AD;
use App\Models\solicitud_AD\users_has_solicitud_AD;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SolicitudADController extends Controller
{
    /**
     * Mostrar todas las solicitudes.
     */
    public function index()
    {
        $user = Auth::user();
        $rol = $user->rol;

        $solicitudes = solicitud_AD::all();

        return view('solicitud_AD.index', compact('solicitudes', 'rol'));
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
        // Buscar la solicitud por su ID correcto
        $solicitud = solicitud_AD::where('idsolicitud_AD', $id)->firstOrFail();

        // Obtener todos los usuarios y los asociados a la solicitud
        $usuarios = User::all();
        $usuariosAsociados = users_has_solicitud_AD::where('idsolicitud_AD', $id)->pluck('users_id')->toArray();

        return view('solicitud_AD.edit', compact('solicitud', 'usuarios', 'usuariosAsociados'));
    }

    /**
     * Actualizar una solicitud existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha' => 'required|date',
            'estatus' => 'required|string',
            'comentario' => 'nullable|string',
            'usuarios' => 'required|array|min:1',
        ]);

        // Buscar la solicitud
        $solicitud = solicitud_AD::where('idsolicitud_AD', $id)->firstOrFail();

        // Actualizar los campos
        $solicitud->update([
            'fecha' => $request->fecha,
            'estatus' => $request->estatus,
            'Comentario' => $request->comentario,
        ]);

        // Actualizar usuarios asociados
        users_has_solicitud_AD::where('idsolicitud_AD', $id)->delete();

        foreach ($request->usuarios as $userId) {
            users_has_solicitud_AD::create([
                'users_id' => $userId,
                'idsolicitud_AD' => $id,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una solicitud.
     */
public function destroy($id)
{
    $solicitud = solicitud_AD::where('idsolicitud_AD', $id)->firstOrFail();
    $solicitud->delete();

    return response()->json(['success' => true]);
}

}
