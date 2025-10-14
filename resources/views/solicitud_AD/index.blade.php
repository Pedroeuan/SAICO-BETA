@extends('adminlte::page')

@section('title', 'Gestión de Solicitudes')

@section('content_header')
    <h1 class="text-primary">Gestión de Solicitudes de AD</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Listado de Solicitudes</h3>
        <div class="card-tools">
            <button class="btn btn-light btn-sm" id="btn-refresh">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
    </div>

    <div class="card-body">
        <table id="tabla-solicitudes" class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>ID Solicitud</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Comentario</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($solicitudes as $solicitud)
                    @foreach ($solicitud->usuarios as $usuario)
                        <tr>
                            <td>{{ $solicitud->idsolicitud_AD }}</td>
                            <td>{{ $solicitud->fecha }}</td>
                            <td>
                                <span class="badge 
                                    @if($solicitud->estatus == 'Pendiente') bg-warning 
                                    @elseif($solicitud->estatus == 'Aprobado') bg-success 
                                    @else bg-danger @endif">
                                    {{ $solicitud->estatus }}
                                </span>
                            </td>
                            <td>{{ $solicitud->comentario ?? 'N/A' }}</td>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->rol }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm ver-detalle" 
                                        data-id="{{ $solicitud->idsolicitud_AD }}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="btn btn-danger btn-sm eliminar-solicitud" 
                                        data-id="{{ $solicitud->idsolicitud_AD }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    {{-- DataTables y SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@stop

@section('js')
    {{-- jQuery, DataTables y SweetAlert2 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function () {
            // Inicializar DataTable
            $('#tabla-solicitudes').DataTable({
                responsive: true,
                autoWidth: false,
                order: [[0, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Botón de refrescar
            $('#btn-refresh').click(() => location.reload());

            // Ver detalle
            $(document).on('click', '.ver-detalle', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Detalle de Solicitud',
                    html: 'Cargando información...',
                    showConfirmButton: false,
                    didOpen: () => {
                        // Aquí puedes hacer un fetch a tu ruta Laravel si tienes endpoint de detalle
                        Swal.update({
                            html: `<p>ID Solicitud: <b>${id}</b></p>
                                <p>Más detalles disponibles en el backend...</p>`
                        });
                        Swal.showConfirmButton = true;
                    }
                });
            });

            // Eliminar solicitud
            $(document).on('click', '.eliminar-solicitud', function () {
                let id = $(this).data('id');
                Swal.fire({
                    title: '¿Eliminar solicitud?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/solicitudes/${id}`,
                            type: 'DELETE',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function () {
                                Swal.fire('Eliminado', 'La solicitud ha sido eliminada.', 'success')
                                    .then(() => location.reload());
                            },
                            error: function () {
                                Swal.fire('Error', 'No se pudo eliminar la solicitud.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
