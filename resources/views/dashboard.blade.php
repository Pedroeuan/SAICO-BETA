@extends('adminlte::page')

@section('title', 'AICO')

@section('content_header')
@stop

@section('content')
<div class="content-wrapper" style="overflow: hidden; height: 100%;">
    @extends('MenuPrincipal')
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="vendor/adminlte\dist/css/MenuP.css">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
