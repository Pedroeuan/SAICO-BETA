@extends('adminlte::page')

@section('title', 'Registrar patrón de grano')

@section('content')
<br>
<br>
<br>
<div class="container pt-4">
    <div class="card card-primary">
        <div class="card-header"><h3 class="card-title">Registrar patrón comparativo de grano</h3></div>
        <form method="post" action="{{ route('Patrones_Grano_IM.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @include('Normas_IM.Patrones_Grano._form')
            </div>
        </form>
    </div>
</div>
@stop
