@extends('adminlte::page')
@section('title', 'Pagos Vehículo')
<br>
<br>
<br>
@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice-dollar"></i>
                Pagos - {{ $vehiculo->placa }}
            </h5>

            <div>
                <a href="{{ route('vehiculos.edit', $vehiculo->id) }}"
                   class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

                <a href="{{ route('vehiculos.pagos.create', $vehiculo->id) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Pago
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="tablaPagos"
                       class="table table-bordered table-hover table-striped align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Año</th>
                            <th>Tipo</th>
                            <th>Fecha pago</th>
                            <th>Monto</th>
                            <th class="text-center">Comprobante</th>
                            <th class="text-center" style="width:100px;">Editar</th>
                            <th class="text-center" style="width:110px;">Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($pagos as $p)
                        <tr>
                            <td>{{ $p->anio }}</td>

                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($p->tipo_pago) }}
                                </span>
                            </td>

                            <td>
                                {{ optional($p->fecha_pago)->format('d/m/Y') ?? 'N/A' }}
                            </td>

                            <td>
                                ${{ number_format($p->monto ?? 0, 2) }}
                            </td>

                            <td class="text-center">
                                @if($p->comprobante_url)
                                    @php
                                        $extension = strtolower(pathinfo($p->comprobante_url, PATHINFO_EXTENSION));
                                    @endphp

                                    <a href="{{ asset('storage/'.$p->comprobante_url) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary btn-sm"
                                       title="Ver comprobante">

                                        @if($extension === 'pdf')
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        @elseif(in_array($extension, ['jpg','jpeg','png']))
                                            <i class="fas fa-file-image text-info"></i>
                                        @else
                                            <i class="fas fa-file"></i>
                                        @endif

                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('vehiculos.pagos.edit', [$vehiculo->id, $p->id]) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <form method="POST"
                                      action="{{ route('vehiculos.pagos.destroy', [$vehiculo->id, $p->id]) }}"
                                      onsubmit="return confirm('¿Eliminar pago?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-info-circle text-muted"></i>
                                No hay pagos registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

@endsection


@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tablaPagos').DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            zeroRecords: "No se encontraron resultados",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        responsive: true,
        autoWidth: false,
        pageLength: 10
    });
});
</script>
@endsection