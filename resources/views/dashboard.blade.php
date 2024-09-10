@extends('adminlte::page')

@section('title', 'AICO')

@section('content_header')
@stop

@php 
//dd($user->rol);
@endphp

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
