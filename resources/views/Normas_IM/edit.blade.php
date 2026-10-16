@extends('adminlte::page')
<br>
<br>
<br>
@section('title', 'Normas IM')
@section('content')
@include('Normas_IM.partials.formulario')
@stop
@section('js')
<script src="{{ asset('js/normas-im-composicion.js') }}"></script>
<script src="{{ asset('js/normas-im-tablas.js') }}"></script>
@stop
