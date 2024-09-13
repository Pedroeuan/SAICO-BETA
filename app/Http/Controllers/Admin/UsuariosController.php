<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


use App\Models\Admin\Usuario;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios'));
        //dd($Usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Registro de Usuarios
        // Validar los datos de entrada
        $request->validate([
            'NombreUsuario' => 'required|string|max:255',
            'CorreoUsuario' => 'required|string|max:255|unique:users,email',
            'ContrasenaUsuario' => 'required|string|max:255',
            'RepetirContrasena' => 'required|string|max:255|same:ContrasenaUsuario',
            'RolUsuario' => [
                'required',
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos',
            ],
        ]);
    
        // Crear una nueva instancia de Usuario
        $Usuario = new Usuario;
        $EsperaDato ='ESPERA DE DATO';
        
        // Asignar valores a los atributos del usuario
        $Usuario->name = $request->input('NombreUsuario') ?? $EsperaDato;
        $Usuario->email = $request->input('CorreoUsuario') ?? $EsperaDato;
        
        // Cifrar la contraseña antes de guardarla
        $Usuario->password = Hash::make($request->input('ContrasenaUsuario'));
        
        $Usuario->rol = $request->input('RolUsuario') ?? $EsperaDato;

        // Guardar el usuario en la base de datos
        $Usuario->save();

        // Redirigir a la página de administración
        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Usuario = Usuario::where('id', $id)->first();

        return view('Admin.edit', compact('id','Usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->filled('ContrasenaUsuario') && $request->filled('RepetirContrasena')) {
        // Validar los datos de entrada
        $request->validate([
            'NombreUsuario' => 'required|string|max:255',
            //'CorreoUsuario' => 'required|string|max:255|unique:users,email',
            'ContrasenaUsuario' => 'required|string|max:255',
            'RepetirContrasena' => 'required|string|max:255|same:ContrasenaUsuario',
            'RolUsuario' => [
                'required',
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos',
            ],
        ]);
        // Obtener el equipo existente
        $Usuario  = Usuario::find($id);

        // Actualizar los datos del equipo
        $Usuario ->update([
            'name' => $request->input('NombreUsuario'),
            'email' => $request->input('CorreoUsuario'),
            'password' => Hash::make($request->input('ContrasenaUsuario')),
            'rol' => $request->input('RolUsuario'),
        ]);
    }
    else{
        // Validar los datos de entrada
        $request->validate([
            'NombreUsuario' => 'required|string|max:255',
            //'CorreoUsuario' => 'required|string|max:255|unique:users,email',
            //'ContrasenaUsuario' => 'required|string|max:255',
            //'RepetirContrasena' => 'required|string|max:255|same:ContrasenaUsuario',
            'RolUsuario' => [
                'required',
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos',
            ],
        ]);
        // Obtener el equipo existente
        $Usuario  = Usuario::find($id);

        // Actualizar los datos del equipo
        $Usuario ->update([
            'name' => $request->input('NombreUsuario'),
            'email' => $request->input('CorreoUsuario'),
            //'password' => Hash::make($request->input('ContrasenaUsuario')),
            'rol' => $request->input('RolUsuario'),
        ]);

    }

        // Redirigir a la página de administración
        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios'));
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
    
        if ($usuario) {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No se pudo encontrar el Usuario.']);
        }
    }
}
