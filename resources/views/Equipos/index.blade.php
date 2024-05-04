@endphp

@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
 <br>  
<div
    class="table-responsive"
>
    <table
        class="table table-primary"
    >
        <thead>
            <tr>
                <th scope="col">NOMBRE</th>
                <th scope="col">NUM. ECONOMICO</th>
                <th scope="col">MARCA</th>
                <th scope="col">MODELO</th>
                <th scope="col">SERIE</th>
                <th scope="col">DESTINO</th>
                <th scope="col">FECHA CALIBRACIÓN</th>
                <th scope="col">ACCIONES</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Equipos as $equipo)
            @php 
            //dd($Equipos)
            @endphp
            <tr class="">
                <td scope="row">{{$equipo->Nombre_E_P_BP}}</td>
                <td scope="row">{{$equipo->No_economico}}</td>
                <td scope="row">{{$equipo->Marca}}</td>
                <td scope="row">{{$equipo->Modelo}}</td>
                <td scope="row">{{$equipo->Serie}}</td>
                <td scope="row">{{$equipo->Destino}}</td>
                <td scope="row">{{$equipo->Fecha_calibracion}}</td>
                <td scope="row">ACCIONES</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop



@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop
