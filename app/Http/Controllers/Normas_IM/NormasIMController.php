<?php

namespace App\Http\Controllers\Normas_IM;

use App\Http\Controllers\Controller;
use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NormasIMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexNormasIM()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Normas_IM.Create');
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
    public function show(Normas_IM $normas_IM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Normas_IM $normas_IM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Normas_IM $normas_IM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Normas_IM $normas_IM)
    {
        //
    }
}
