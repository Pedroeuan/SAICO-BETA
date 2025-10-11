@extends('adminlte::page')

@section('title', 'Crear Solicitud AD')

@section('content_header')
    <h1 class="text-primary">Nueva Solicitud de AD</h1>
@stop

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Registrar Nueva Solicitud</h3>
    </div>

    <div class="card-body">
        <form id="form-solicitud" method="POST" action="{{ route('solicitudes.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="estatus" class="form-label">Estatus</label>
                    <select class="form-control" id="estatus" name="estatus" required>
                        <option value="">Seleccione...</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="comentario" class="form-label">Comentario</label>
                    <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Observaciones...">
                </div>
            </div>

            <div class="mt-4">
                <h5>Seleccionar Usuario(s) asociados a la solicitud</h5>
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
                                    <input type="checkbox" name="usuarios[]" value="{{ $user->id }}">
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
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Guardar Solicitud
                </button>
                <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    {{-- Estilos DataTables + SweetAlert2 --}}
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
        $(function() {
            // Inicializar DataTable
            $('#tabla-usuarios').DataTable({
                responsive: true,
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Interceptar envío del formulario
            $('#form-solicitud').on('submit', function(e) {
                e.preventDefault();

                // Verificar que al menos un usuario esté seleccionado
                if ($('input[name="usuarios[]"]:checked').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Debe seleccionar al menos un usuario para asociar la solicitud.'
                    });
                    return;
                }

                // Confirmar envío
                Swal.fire({
                    title: '¿Desea guardar esta solicitud?',
                    text: "Se asociará con los usuarios seleccionados.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: $(this).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Solicitud creada correctamente',
                                    showConfirmButton: false,
                                    timer: 1800
                                }).then(() => {
                                    window.location.href = "{{ route('solicitudes.index') }}";
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al guardar',
                                    text: 'Verifique los datos e intente nuevamente.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
