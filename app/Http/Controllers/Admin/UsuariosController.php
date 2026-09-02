<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Admin\Usuario;
use App\Models\Clientes\clientes;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;

        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios','rol'));
        //dd($Usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;
        
        return view('Admin.create', compact('rol'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;

        $EsperaDato = 'ESPERA DE DATO';
        //Registro de Usuarios
        // Validar los datos de entrada
        $request->validate([
            'NombreUsuario' => 'required|string|max:255',
            'CorreoUsuario' => 'required|string|max:255|unique:users,email',
            'ContrasenaUsuario' => 'required|string|max:255',
            'RepetirContrasena' => 'required|string|max:255|same:ContrasenaUsuario',
            'RolUsuario' => [
                'required',
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos,Laboratorio,Tics,SGI',
            ],
            'Estatus' => 'required|string|max:255',
        ]);

        if ($request->input('RolUsuario') === 'Cliente') {

            $Cliente = new clientes;
            $Cliente->Cliente = $request->input('NombreUsuario');
            $Cliente->RFC = $EsperaDato;
            $Cliente->Telefono = $EsperaDato;
            if ($request->input('CorreoUsuario') == null) {

            $Cliente->Correo = $EsperaDato;

        } else {

            $Cliente->Correo = $request->input('CorreoUsuario');

        }

            
        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN DEL PORTAL
        |--------------------------------------------------------------------------
        */

        $Cliente->portal_token = (string) Str::uuid();


        /*
        |--------------------------------------------------------------------------
        | GUARDAR LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $rutaLogo = $request->file('logo')->store('clientes', 'public');

            $Cliente->logo = $rutaLogo;

        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR CLIENTE
        |--------------------------------------------------------------------------
        */

        $Cliente->save();


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA PARA AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'message' => 'Cliente guardado correctamente.',
                'idCliente' => $Cliente->idClientes,
            ]);

        }
        /*
        |--------------------------------------------------------------------------
        | RESPUESTA NORMAL
        |--------------------------------------------------------------------------
        */
        //return redirect()->route('clientes.index');
        }

        // Crear una nueva instancia de Usuario
        $Usuario = new Usuario;
        $EsperaDato ='ESPERA DE DATO';
        
        // Asignar valores a los atributos del usuario
        $Usuario->name = $request->input('NombreUsuario') ?? $EsperaDato;
        $Usuario->email = $request->input('CorreoUsuario') ?? $EsperaDato;
        
        // Cifrar la contraseña antes de guardarla
        $Usuario->password = Hash::make($request->input('ContrasenaUsuario'));
        
        $Usuario->rol = $request->input('RolUsuario') ?? $EsperaDato;

        $Usuario->Estatus = $request->input('Estatus') ?? $EsperaDato;

        // campos de licencia
        if($request->input('licencia_numero')==null)
        {
            $Usuario->licencia_numero = $EsperaDato;
        }else{
        $Usuario->licencia_numero = $request->input('licencia_numero');
        }
        //fecha de vencimiento
        if($request->input('licencia_vencimiento')==null)
        {
            $Usuario->licencia_vencimiento = '2001-01-01';
        }else{
        $Usuario->licencia_vencimiento = $request->input('licencia_vencimiento');
        }
            
        //Guardar pdf licencia
        if($request->hasFile('licencia_pdf')){
            $rutaLicencia = $request->file('licencia_pdf')->store('usuarios/licencias', 'public');
            $Usuario->licencia_pdf = $rutaLicencia;
        }else{
            $Usuario->licencia_pdf = $EsperaDato;
        }

        // Guardar CV
        if($request->hasFile('cv_pdf')){
                $rutaCV = $request->file('cv_pdf')->store('usuario/cv', 'public');
                $Usuario->cv_pdf = $rutaCV;
        }else{
            $Usuario->cv_pdf = $EsperaDato;
        }
        // Guardar el usuario en la base de datos
        $Usuario->save();

        // Redirigir a la página de administración
        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios','rol'));
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

        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;
        
        return view('Admin.edit', compact('id','Usuario','rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;
        $EsperaDato ='ESPERA DE DATO';
        if ($request->filled('ContrasenaUsuario') && $request->filled('RepetirContrasena')) {
        // Validar los datos de entrada
        $request->validate([
            'NombreUsuario' => 'required|string|max:255',
            //'CorreoUsuario' => 'required|string|max:255|unique:users,email',
            'ContrasenaUsuario' => 'required|string|max:255',
            'RepetirContrasena' => 'required|string|max:255|same:ContrasenaUsuario',
            'RolUsuario' => [
                'required',
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos,Laboratorio,Tics,SGI',
            ],
            'Estatus' => 'required|string|max:255',
        ]);
        // Obtener el usuario existente
        $Usuario  = Usuario::find($id);

        // ===== CAMPOS LICENCIA =====
        if($request->input('licencia_numero')==null)
        {
            $Usuario->licencia_numero = $EsperaDato;
        }else{
        $Usuario->licencia_numero = $request->input('licencia_numero');
        }
        if($request->input('licencia_vencimiento')==null)
        {
            $Usuario->licencia_vencimiento = '2001-01-01';
        }else{
            $Usuario->licencia_vencimiento = $request->input('licencia_vencimiento');
        }
        // Subir nueva licencia si existe
        if ($request->hasFile('licencia_pdf')) {
            $rutaLicencia = $request->file('licencia_pdf')
                ->store('usuarios/licencias', 'public');
            $Usuario->licencia_pdf = $rutaLicencia;
        }

        // Subir nuevo CV si existe
        if ($request->hasFile('cv_pdf')) {
            $rutaCV = $request->file('cv_pdf')
                ->store('usuarios/cv', 'public');
            $Usuario->cv_pdf = $rutaCV;
        }

        // Actualizar los datos del usuario
        $Usuario ->update([
            'name' => $request->input('NombreUsuario'),
            'email' => $request->input('CorreoUsuario'),
            'password' => Hash::make($request->input('ContrasenaUsuario')),
            'rol' => $request->input('RolUsuario'),
            'Estatus' => $request->input('Estatus'),
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
                'in:Super Administrador,Administrador,Cliente,Ventas,Técnicos,Planeación,Equipos,Laboratorio,Tics,SGI',
            ],
            'Estatus' => 'required|string|max:255',
        ]);
        // Obtener el usuario existente
        $Usuario  = Usuario::find($id);

        // ===== CAMPOS LICENCIA =====
        if($request->input('licencia_numero')==null)
        {
            $Usuario->licencia_numero = $EsperaDato;
        }else{
        $Usuario->licencia_numero = $request->input('licencia_numero');
        }
        if($request->input('licencia_vencimiento')==null)
        {
            $Usuario->licencia_vencimiento = '2001-01-01';
        }else{
            $Usuario->licencia_vencimiento = $request->input('licencia_vencimiento');
        }
        // Subir nueva licencia si existe
        if ($request->hasFile('licencia_pdf')) {
            $rutaLicencia = $request->file('licencia_pdf')
                ->store('usuarios/licencias', 'public');
            $Usuario->licencia_pdf = $rutaLicencia;
        }

        // Subir nuevo CV si existe
        if ($request->hasFile('cv_pdf')) {
            $rutaCV = $request->file('cv_pdf')
                ->store('usuarios/cv', 'public');
            $Usuario->cv_pdf = $rutaCV;
        }

        // Actualizar los datos del usuario
        $Usuario ->update([
            'name' => $request->input('NombreUsuario'),
            'email' => $request->input('CorreoUsuario'),
            //'password' => Hash::make($request->input('ContrasenaUsuario')),
            'rol' => $request->input('RolUsuario'),
            'Estatus' => $request->input('Estatus'),
        ]);

    }
        // Redirigir a la página de administración
        $Usuarios = Usuario::all();
        return view('Admin.index', compact('Usuarios','rol'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Usuario = Usuario::find($id);
            if (! $Usuario) {
                return response()->json(['success' => false, 'message' => 'No se pudo encontrar el Usuario.'], 404);
            }

            // Actualizar el estatus a 'Baja'
            $Usuario->Estatus = 'BAJA';
            $Usuario->save();

            return response()->json(['success' => true, 'message' => 'Usuario dado de baja correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al dar de baja usuario: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al procesar la petición.'], 500);
        }

    }
}
