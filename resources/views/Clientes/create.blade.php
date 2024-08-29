
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center"> Formulario Alta de Clientes</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Clientes</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->

                <div class="card-body">
                    <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <form id="ClienteForm" method="post" enctype="multipart/form-data" action="{{route('registro.storeClientes')}}">
                                    @csrf
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Cliente</label>
                                                    <input type="text" class="form-control inputForm @error('Cliente') is-invalid @enderror" value="{{old('Cliente')}}" name="Cliente" placeholder="Ejemplo: PROTEXA">
                                                    @error('Cliente')
                                                            <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">RFC</label>
                                                    <input type="text" class="form-control inputForm" value="{{old('RFC')}}" name="RFC" placeholder="Ejemplo: PROP56512458">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Telefono</label>
                                                    <input type="text" class="form-control inputForm" value="{{old('Telefono')}}" name="Telefono" placeholder="Ejemplo: 81 8399 2828">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Correo</label>
                                                    <input type="text" class="form-control inputForm" value="{{old('Correo')}}" name="Correo" placeholder="Ejemplo: hola@protexa.mx">
                                                </div>
                                            </div>
                                            <div class="container">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                                </div>
                                                <div class="float-left">
                                                    <button type="button" class="btn btn-info bg-success" id="guardarContinuarClientes">Guardar y continuar</button>
                                                </div>
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
/*Prevenir el Enter */
document.getElementById('ClienteForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $(document).ready(function() {
    $('#guardarContinuarClientes').on('click', function(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        // Validar que el campo "Cliente" no esté vacío
        let cliente = $('input[name="Cliente"]').val().trim();
        if (cliente === '') {
            Swal.fire('Error', 'El campo Cliente es obligatorio.', 'error');
            return;
        }

        // Obtener los datos del formulario
        let formData = $('#ClienteForm').serialize();

        // Realizar la solicitud AJAX para guardar los datos
        $.ajax({
            url: $('#ClienteForm').attr('action'), // Usar la URL definida en el formulario
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
                    $('#ClienteForm')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                Swal.fire('Error', 'Ocurrió un error al guardar los datos.', 'error');
            }
        });
    });
});

</script>
@endsection