@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">

    <h4>Salidas de Vehículos</h4>

    <a href="{{ route('salidas.create') }}" class="btn btn-primary mb-3">
        + Nueva salida
    </a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Vehículo</th>
                <th>Chofer</th>
                <th>Fecha salida</th>
                <th>Checklist salida</th>
                <th>Checklist entrada</th>
                <th>PDF</th>
                <th>Ver Salida</th>
                <th>Ver Entrada</th>
            </tr>
        </thead>

        <tbody>
        @foreach($salidas as $salida)
            <tr>
                <td>{{ $salida->vehiculo->placa }}</td>
                <td>{{ $salida->chofer->name }}</td>
                <td>{{ $salida->fecha_salida }}</td>
                

                {{-- CHECKLIST SALIDA --}}
                <td class="text-center">
                    @if($salida->checklistSalida)
                        <span class="badge bg-success">Registrado</span>
                    @else
                        <a href="{{ route('salidas.checklist.salida.create',$salida->id) }}"
                           class="btn btn-sm btn-primary">
                            Registrar
                        </a>
                    @endif
                </td>

                {{-- CHECKLIST ENTRADA --}}
                <td class="text-center">
                    @if($salida->checklistEntrada)
                        <span class="badge bg-success">Registrado</span>
                    @elseif($salida->checklistSalida)
                        <a href="{{ route('salidas.checklist.entrada.create',$salida->id) }}"
                           class="btn btn-sm btn-warning">
                            Registrar
                        </a>
                    @else
                        <span class="text-muted">Pendiente salida</span>
                    @endif
                </td>

                {{-- ACCIONES --}}

                        <td>
                            <a href="{{ route('salidas.salidas.checklist.pdf',$salida->id) }}" target="_blank"class="btn btn-sm btn-danger">PDF </a>
                        </td>
                    <td>
                    {{-- SALIDA --}}
                    @if($salida->checklistSalida)
                        <a href="{{ route('salidas.checklist.show',[$salida->id,'salida']) }}"class="btn btn-sm btn-info">Ver salida</a>
                    </td>
                    @endif
                </td> 

                <td>
                    {{-- ENTRADA --}}
                    @if($salida->checklistEntrada)
                        <a href="{{ route('salidas.checklist.show',[$salida->id,'entrada']) }}"
                           class="btn btn-sm btn-secondary">
                            Ver entrada
                        </a>

                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection
