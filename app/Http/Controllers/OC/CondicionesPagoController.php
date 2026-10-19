<?php

namespace App\Http\Controllers\OC;

use App\Models\Condiciones_Pago\Condiciones_Pago;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
class CondicionesPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Condiciones_Pago = Condiciones_Pago::all();
        return view('OC.CondicionesPago.index',compact('Condiciones_Pago'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('OC.CondicionesPago.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$request->all();
        $request->validate([
            'Nombre' => 'required|string',
            'Descripcion' => 'required|string',
        ]);

        $Condiciones_Pago = new Condiciones_Pago;
        $Condiciones_Pago->Nombre = $request->input('Nombre');
        $Condiciones_Pago->Descripcion = $request->input('Descripcion');
        $Condiciones_Pago->save();

        return redirect()->route('OC.indexPago');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condiciones_Pago $condiciones_Pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $Condiciones_Pago = Condiciones_Pago::find($id);
        if (!$Condiciones_Pago) {
            return redirect()->route('OC.indexPago')->with('error', 'Condiciones de Pago no encontrado.');
        }
        return view('OC.CondicionesPago.edit', compact('Condiciones_Pago', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'Nombre' => 'required|string',
            'Descripcion' => 'required|string',
        ]);

        $Condiciones_Pago = Condiciones_Pago::find($id);
        $Condiciones_Pago->update([
            'Nombre' => $request->input('Nombre'),
            'Descripcion' => $request->input('Descripcion'),
        ]);

        return redirect()->route('OC.indexPago');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $Condiciones_Pago = Condiciones_Pago::find($id);
        if ($Condiciones_Pago) {
            $Condiciones_Pago->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Condiciones de Pago no encontrado.']);
        }
        return redirect()->route('OC.indexPago');
    }
}
