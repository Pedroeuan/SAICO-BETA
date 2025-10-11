<?php

namespace App\Http\Controllers\solicitud_AD;

use App\Models\solicitud_AD\solicitud_AD;
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
        try {
            $solicitudes = solicitud_AD::all();
            return response()->json([
                'success' => true,
                'data' => $solicitudes
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener solicitudes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las solicitudes.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * (Normalmente no se usa en API REST)
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Guarda una nueva solicitud en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'estatus' => 'required|string|max:50',
            'comentario' => 'nullable|string'
        ]);

        try {
            $solicitud = solicitud_AD::create([
                'fecha' => $request->fecha,
                'estatus' => $request->estatus,
                'comentario' => $request->comentario,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud creada exitosamente.',
                'data' => $solicitud
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * Muestra una solicitud específica.
     */
    public function show($id)
    {
        try {
            $solicitud = solicitud_AD::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            Log::warning("Solicitud con ID $id no encontrada.");
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada.'
            ], 404);
        }
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
        $request->validate([
            'fecha' => 'sometimes|date',
            'estatus' => 'sometimes|string|max:50',
            'comentario' => 'nullable|string'
        ]);

        try {
            $solicitud = solicitud_AD::findOrFail($id);
            $solicitud->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Solicitud actualizada correctamente.',
                'data' => $solicitud
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Elimina una solicitud.
     */
    public function destroy($id)
    {
        try {
            $solicitud = solicitud_AD::findOrFail($id);
            $solicitud->delete();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar solicitud: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la solicitud.'
            ], 500);
        }
    }
}
