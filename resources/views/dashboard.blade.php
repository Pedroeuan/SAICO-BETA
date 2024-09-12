@extends('adminlte::page')

@section('title', 'AICO')

@section('content_header')
@stop

@section('content')
    @if($user->rol == 'Equipos')
        @include('Equipos.indicadoresEquipos')
            @else
        @include('MenuPrincipal')
    @endif
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="vendor/adminlte\dist/css/MenuP.css">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
