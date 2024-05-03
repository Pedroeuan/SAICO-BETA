<?php

namespace App\Http\Controllers\Equipos;

use App\Models\Equipos\Equipo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquiposController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $Equipos=Equipo::all();
        //dd($Equipos);
        return view('Equipos.index',compact('Equipos'));
        //return view('Equipos.index');
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
    public function show(Equipos $equipos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipos $equipos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipos $equipos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipos $equipos)
    {
        //
    }
}
