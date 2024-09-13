<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Models\EquipoyConsumibles\ExcelEyC;
use Illuminate\Http\Request;
use App\Models\MExcelEyC;


class ExcelEyCController extends Controller
{
    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ExcelEyC, $request->file('archivo'));

        return back()->with('success', 'Datos importados correctamente.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExcelEyC $excelEyC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExcelEyC $excelEyC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExcelEyC $excelEyC)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExcelEyC $excelEyC)
    {
        //
    }
}
