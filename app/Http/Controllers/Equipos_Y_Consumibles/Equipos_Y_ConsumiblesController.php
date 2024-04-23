<?php

namespace App\Http\Controllers\Equipos_Y_Consumibles;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Equipos_Y_ConsumiblesController extends Controller
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
        return view('Equipos_Y_Consumibles.Equipos_Y_Consumibles');
        //return 'Hello World';
    }
}
