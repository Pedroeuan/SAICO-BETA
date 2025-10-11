<?php

namespace App\Http\Controllers;

use App\Models\solicitud_AD\Users_Has_solicitud_AD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersHasSolicitudADController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra todas las relaciones entre usuarios y solicitudes.
     */
    public function index()
    {
        try {
            $relaciones = Users_Has_solicitud_AD::with(['user', 'solicitud'])->get();
            return response()->json([
                'success' => true,
                'data' => $relaciones
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener relaciones: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las relaciones.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * (No se usa en API REST)
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Crea una nueva relación entre un usuario y una solicitud.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'idsolicitud_AD' => 'required|exists:solicitud_AD,idsolicitud_AD'
        ]);

        try {
            $relacion = Users_Has_solicitud_AD::create([
                'user_id' => $request->user_id,
                'idsolicitud_AD' => $request->idsolicitud_AD,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Relación creada exitosamente.',
                'data' => $relacion
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear relación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la relación.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * Muestra una relación específica.
     */
    public function show($id)
    {
        try {
            $relacion = Users_Has_solicitud_AD::with(['user', 'solicitud'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $relacion
            ], 200);
        } catch (\Exception $e) {
            Log::warning("Relación con ID $id no encontrada.");
            return response()->json([
                'success' => false,
                'message' => 'Relación no encontrada.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     * Actualiza una relación existente.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'idsolicitud_AD' => 'sometimes|exists:solicitud_AD,idsolicitud_AD'
        ]);

        try {
            $relacion = Users_Has_solicitud_AD::findOrFail($id);
            $relacion->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Relación actualizada correctamente.',
                'data' => $relacion
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar relación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la relación.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Elimina una relación.
     */
    public function destroy($id)
    {
        try {
            $relacion = Users_Has_solicitud_AD::findOrFail($id);
            $relacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Relación eliminada correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar relación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la relación.'
            ], 500);
        }
    }
}
