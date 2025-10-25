@extends('adminlte::page')

@section('title', 'Editar Solicitud AD')

@section('content_header')
    <h1 class="text-primary">Editar Solicitud de AD</h1>
@stop

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-warning text-white">
        <h3 class="card-title">Modificar Solicitud #{{ $solicitud->idsolicitud_AD }}</h3>
    </div>

    <div class="card-body">
        <form id="form-solicitud-edit" method="POST" action="{{ route('ADsolicitud.update', $solicitud->idsolicitud_AD) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha"
                           value="{{ $solicitud->fecha }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="estatus" class="form-label">Estatus</label>
                    <select class="form-control" id="estatus" name="estatus" required>
                        <option value="Pendiente" {{ $solicitud->estatus == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobado" {{ $solicitud->estatus == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="Rechazado" {{ $solicitud->estatus == 'Rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="comentario" class="form-label">Comentario</label>
                    <input type="text" class="form-control" id="comentario" name="comentario"
                           value="{{ $solicitud->Comentario }}" placeholder="Observaciones...">
                </div>
            </div>

            <div class="mt-4">
                <h5>Usuarios asociados a esta solicitud</h5>
                <table id="tabla-usuarios" class="table table-bordered table-striped mt-3">
                    <thead class="text-center">
                        <tr>
                            <th>Seleccionar</th>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $user)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="usuarios[]" value="{{ $user->id }}"
                                           {{ in_array($user->id, $usuariosAsociados) ? 'checked' : '' }}>
                                </td>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->rol }}</td>
                                <td>
                                    <span class="badge {{ $user->estatus == 'Activo' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $user->estatus }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning btn-lg">
                    <i class="fas fa-save"></i> Actualizar Solicitud
                </button>
                <a href="{{ route('ADsolicitud.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    {{-- Estilos DataTables y SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@stop

@section('js')
    {{-- Librerías necesarias --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function () {
            // Inicializar DataTable
            $('#tabla-usuarios').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Confirmación antes de guardar cambios
            $('#form-solicitud-edit').on('submit', function (e) {
                e.preventDefault();

                // Validar selección de usuario
                if ($('input[name="usuarios[]"]:checked').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Debe seleccionar al menos un usuario asociado.'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Desea actualizar esta solicitud?',
                    text: "Se aplicarán los cambios realizados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function () {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Solicitud actualizada correctamente',
                                    showConfirmButton: false,
                                    timer: 1800
                                }).then(() => {
                                    window.location.href = "{{ route('ADsolicitud.index') }}";
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'No se pudo actualizar la solicitud. Verifique los datos.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
