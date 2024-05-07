
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>  
<br>
<br>
<a href="Equipos/create" class="btn btn-primary">Agregar</a>
    

    <div class="table-responsive">
<br>
<div class="p-3 mb-2 bg-primary text-center text-white">EQUIPOS</div>
    <table class="table table-primary">
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
            @foreach ($generalConCertificados as $general_eyc)
            @php 
            //dd($generalConCertificados)
            @endphp
            <tr class="">
                <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                <td scope="row">{{$general_eyc->No_economico}}</td>
                <td scope="row">{{$general_eyc->Marca}}</td>
                <td scope="row">{{$general_eyc->Modelo}}</td>
                <td scope="row">{{$general_eyc->Serie}}</td>
                <td scope="row">{{$general_eyc->Destino}}</td>
                <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
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
    {{--<link rel="stylesheet" href="vendor/adminlte\dist/css/Equipos.scss">--}}
@stop
