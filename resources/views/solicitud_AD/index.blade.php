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
                    <th>Nombre</th>
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
                    <td>{{ $solicitud->users->name }}</td>
                    <td>{{ $solicitud->solicitud_ad->fecha }}</td>
                    <td>
                        <span class="badge 
                            {{ $solicitud->solicitud_ad->estatus == 'Aprobado' ? 'bg-success' : 
                            ($solicitud->solicitud_ad->estatus == 'Pendiente' ? 'bg-warning' : 'bg-danger') }}">
                            {{ $solicitud->solicitud_ad->estatus }}
                        </span>
                    </td>
                    <td>{{ $solicitud->solicitud_ad->Tema }}</td>
                    <td>{{ $solicitud->solicitud_ad->comentario }}</td>
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
    $(document).on("click", ".btn-eliminar", function() {
        var idUsuario = $(this).attr("idUsuario");
        Swal.fire({
            title: "¿Se eliminara?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/Usuarios/eliminar/' + data-id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Eliminado!",
                                text: response.message,
                                icon: "success",
                                didClose: function() {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelado", "", "error");
            }
        });
    });
});
</script>
@stop