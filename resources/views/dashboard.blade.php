@extends('adminlte::page')

@section('title', 'AICO')

@section('content_header')
@stop

@php 
/*
@section('content')
@include('MenuPrincipal')
@stop
*/
@endphp

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="vendor/adminlte\dist/css/MenuP.css">
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
