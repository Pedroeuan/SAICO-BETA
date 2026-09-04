@extends('adminlte::page')

@section('title', 'AICO')

@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection

@section('content_header')
@stop

@section('content')
    @if($user->rol == 'Equipos')
        @include('Equipos.indicadoresEquipos')
    @elseif($user->rol == 'Cliente')
        @include('Reportes_publicos.index')
    @else
        @include('Welcome.Welcome')
    @endif
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="vendor/adminlte\dist/css/MenuP.css">
@stop

@section('js')
    {{-- <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script> --}}
@stop
