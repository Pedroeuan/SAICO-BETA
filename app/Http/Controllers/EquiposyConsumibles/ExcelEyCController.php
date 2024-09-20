<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use Maatwebsite\Excel\Facades\Excel; // Para usar Excel::import
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ImporExcelEyC;

class ExcelEyCController extends Controller
{
    //
    public function importarExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Usa la clase de importación correctamente
        Excel::import(new ImporExcelEyC, $request->file('archivo'));

         // Respuesta JSON
        return response()->json(['success' => '¡Importación completada con éxito!']);
    }
}
