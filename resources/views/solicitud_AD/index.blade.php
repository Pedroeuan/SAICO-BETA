@extends('adminlte::page')

@section('title', 'Solicitudes AD')

@section('content_header')
<h1 class="text-primary">Listado de Solicitudes AD</h1>
@stop

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title">Solicitudes Registradas</h3>
        <a href="{{ route('ADsolicitud.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nueva Solicitud
        </a>
    </div>

    <div class="card-body">
        <table id="tabla-solicitudes" class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Tema</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->idsolicitud_AD }}</td>
                    <td>{{ $solicitud->fecha }}</td>
                    <td>
                        <span class="badge 
                            {{ $solicitud->estatus == 'Aprobado' ? 'bg-success' : 
                            ($solicitud->estatus == 'Pendiente' ? 'bg-warning' : 'bg-danger') }}">
                            {{ $solicitud->estatus }}
                        </span>
                    </td>
                    <td>{{ $solicitud->Tema }}</td>
                    <td>{{ $solicitud->Comentario }}</td>
                    <td class="text-center">
                        {{-- Botón Editar --}}
                        <a href="{{ route('ADsolicitud.edit', $solicitud->idsolicitud_AD) }}" 
                        class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        {{-- Botón Eliminar --}}
                        <button type="button"
                            class="btn btn-danger btn-sm btn-eliminar"
                            data-id="{{ $solicitud->idsolicitud_AD }}">
                            <i class="fas fa-trash-alt"></i> Eliminar
                </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {
    $('.btn-eliminar').click(function() {
        let id = $(this).data('id');

        // URL de la ruta destroy (RESTful)
        let url = "{{ route('ADsolicitud.destroy', ':id') }}".replace(':id', id);

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta solicitud se eliminará permanentemente.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE', // método correcto
                    data: { _token: '{{ csrf_token() }}' }, // token CSRF
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar la solicitud.'
                        });
                    }
                });
            }
        });
    });
});
</script>
@stop