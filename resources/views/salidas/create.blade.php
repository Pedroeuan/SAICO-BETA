@extends('adminlte::page')

@section('content')
<br><br><br>

<div class="container">
    <h4>Nueva salida de vehículo</h4>

    <form method="POST" action="{{ route('salidas.store') }}">
        @csrf

        <div class="mb-2">
            <label>Vehículo</label>
            <select name="vehiculo_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->id }}">
                        {{ $vehiculo->placa }} - {{ $vehiculo->marca }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label>Chofer</label>
            <select name="chofer_id" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label>Solicitado por</label>
            <select name="solicitado_por" class="form-control" required>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">
                        {{ $usuario->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label>Fecha salida</label>
            <input type="datetime-local" name="fecha_salida" class="form-control" required>
        </div>

        <div class="mb-2">
            <label>Motivo</label>
            <textarea name="motivo" class="form-control"></textarea>
        </div>

        <button class="btn btn-success mt-3">Guardar salida</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
