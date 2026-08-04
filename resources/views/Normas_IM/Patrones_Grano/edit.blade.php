@extends('adminlte::page')

@section('title', 'Editar patrón de grano')

@section('content')
<div class="container pt-4">
    <div class="card card-warning">
        <div class="card-header"><h3 class="card-title">Editar patrón comparativo de grano</h3></div>
        <form method="post" action="{{ route('Patrones_Grano_IM.update', $patron) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="alert alert-info">
                    Los reportes guardados conservan su propia copia; reemplazar esta imagen solo afectará selecciones futuras.
                </div>
                @include('Normas_IM.Patrones_Grano._form')
            </div>
        </form>
    </div>
</div>
@stop
