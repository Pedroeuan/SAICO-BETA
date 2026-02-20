@extends('adminlte::page')

@section('title', ' Salida Vehiculos')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">

    <h4>Salidas de Vehículos</h4>

    <a href="{{ route('salidas.create') }}" class="btn btn-primary mb-3">
        + Nueva salida
    </a>

    <div class="table-responsive">
      <table id="tablaJs" class="table table-sm table-hover table-bordered align-middle text-center">
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
                           class="btn btn-sm btn-primary px-3">
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
                           class="btn btn-sm btn-warning px-3">
                            Registrar
                        </a>
                    @else
                        <span class="text-muted">Pendiente salida</span>
                    @endif
                </td>

                {{-- ACCIONES --}}

                <td>
                    @if($salida->checklistSalida && $salida->checklistEntrada)
                        <a href="{{ route('salidas.checklist.pdf',$salida->id) }}" target="_blank" class="btn btn-sm btn-danger px-3" title="Descargar PDF completo">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary px-3" disabled title="Requiere checklist de salida y entrada">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>
                    @endif
                </td>

                <td>
                    {{-- SALIDA --}}
                    @if($salida->checklistSalida)
                        <a href="{{ route('salidas.checklist.show',[$salida->id,'salida']) }}" class="btn btn-sm btn-info">Ver salida</a>
                    @endif
                </td> 

                <td>
                    {{-- ENTRADA --}}
                    @if($salida->checklistEntrada)
                        <a href="{{ route('salidas.checklist.show',[$salida->id,'entrada']) }}"
                           class="btn btn-sm btn-secondary px-3">
                            Ver entrada
                        </a>

                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
@stop
</div>
@section('js')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts personalizados -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let table = new DataTable('#tablaJs', {
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            decimal: "",
            emptyTable: "No hay datos disponibles en la tabla",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 entradas",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    // Resetear scroll horizontal al cambiar página
    table.on('draw', function () {
        document.querySelector('.table-responsive').scrollLeft = 0;
    });

});
</script>

@endsection
