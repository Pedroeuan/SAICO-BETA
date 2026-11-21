<?php

namespace App\Http\Controllers\TICS;

use App\Models\TICS\Prestamos_TICS;
use Illuminate\Http\Request;

class PrestamosTICSController extends Controller
{
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
        return view('TICS.create');
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
    public function show(Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamos_TICS $prestamos_TICS)
    {
        //
    }
}
