

@extends('adminlte::page')

@section('title', 'Procedimientos')

@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center"> Formulario Alta de Procedimientos</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Procedimientos</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->

                <div class="card-body">
                    <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <form id="ProcedimientoForm" method="post" enctype="multipart/form-data" action="{{route('Procedimientos.store')}}">
                                    @csrf
                                        <div class="row">

                                            <!-- Nombre del procedimiento -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Nombre Procedimiento</label>
                                                    <input type="text"
                                                        class="form-control inputForm @error('Nombre') is-invalid @enderror"
                                                        value="{{ old('Nombre') }}"
                                                        name="Nombre"
                                                        placeholder="Ejemplo: PRO-PINS-03"
                                                        style="text-transform: uppercase;"
                                                        oninput="this.value = this.value.toUpperCase()">

                                                    @error('Nombre')
                                                        <div class="invalid-feedback">
                                                            <span>{{ $message }}</span>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Subir procedimiento -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Subir Procedimiento</label>
                                                    <input type="file"
                                                        class="form-control inputForm @if ($errors->any()) is-invalid @endif"
                                                        name="Procedimiento">

                                                    @if ($errors->any())
                                                        <div class="invalid-feedback">
                                                            Por favor, vuelva a cargar el archivo de ser necesario.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>

                                            <div class="container">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                                </div>

                                                {{--<div class="float-left">
                                                    <button type="button" class="btn btn-info bg-success" id="guardarContinuarClientes">Guardar y continuar</button>
                                                </div>--}}
                                            </div>

                                        </div>
                                </form>
                        </div><!--"class="tab-pane active" id="tab_1"-->
                    </div><!-- /.tab-content -->
                </div><!-- class="card-body" -->
                        <!-- Agrega más paneles de tabs según sea necesario -->
            </div><!-- /.card -->       
        </div><!-- class="col-sm-12" -->
    </div><!-- class="row justify-content-center" -->
</div><!--class="container" -->     
@stop


@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
/*Prevenir el Enter */
document.getElementById('ProcedimientoForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $(document).ready(function() {
    $('#guardarContinuarProcedimientos').on('click', function(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        // Validar que el campo "Procedimiento" no esté vacío
        let procedimiento = $('input[name="Procedimiento"]').val().trim();
        if (procedimiento === '') {
            Swal.fire('Error', 'El campo Procedimiento es obligatorio.', 'error');
            return;
        }

        // Obtener los datos del formulario
        let formData = $('#ProcedimientoForm').serialize();

        // Realizar la solicitud AJAX para guardar los datos
        $.ajax({
            url: $('#ProcedimientoForm').attr('action'), // Usar la URL definida en el formulario
            method: 'POST',
            data: formData,
            success: function(response) {
                // Verificar si la respuesta indica éxito
                if (response.success) {
                    Swal.fire('Error', response.message, 'error');
                } else {
                    // Mostrar mensaje de éxito
                    Swal.fire('Éxito', 'Los datos han sido guardados correctamente.', 'success');
                    // Limpiar los campos del formulario
                    $('#ProcedimientoForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                Swal.fire('Error', 'Ocurrió un error al guardar los datos.', 'error');
            }
        });
    });
});

// Guardar datos en localStorage al escribir
document.querySelectorAll('#ProcedimientoForm input, #ProcedimientoForm textarea, #ProcedimientoForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('ProcedimientoForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#ProcedimientoForm input, #ProcedimientoForm textarea, #ProcedimientoForm select').forEach(function(input) {
        let value = localStorage.getItem('ProcedimientoForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('ProcedimientoForm').addEventListener('submit', function() {
    document.querySelectorAll('#ProcedimientoForm input, #ProcedimientoForm textarea, #ProcedimientoForm select').forEach(function(input) {
        localStorage.removeItem('ProcedimientoForm_' + input.name);
    });
});
</script>
@endsection