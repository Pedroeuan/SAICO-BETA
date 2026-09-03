@extends('adminlte::page')

@section('title', 'Clientes')

@section('css')
<style>
    #my-notification .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }

    .logo-preview-container {
        margin-top: 10px;
        text-align: center;
    }

    .logo-preview {
        width: 150px;
        height: 150px;
        object-fit: contain;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 5px;
        background: #f8f9fa;
        display: none;
    }
</style>
@endsection

@section('content')

<br>
<br>
<br>

<h3 align="center">Formulario Alta de Clientes</h3>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">

            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="#tab_1"
                                data-toggle="tab">
                                Clientes
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_1">

                            <form id="ClienteForm"
                                method="POST"
                                enctype="multipart/form-data"
                                action="{{ route('registro.storeClientes') }}">

                                @csrf

                                <div class="row">

                                    {{-- CLIENTE --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Cliente
                                            </label>

                                            <input type="text"
                                                class="form-control inputForm @error('Cliente') is-invalid @enderror"
                                                value="{{ old('Cliente') }}"
                                                name="Cliente"
                                                placeholder="Ejemplo: PROTEXA"
                                                style="text-transform: uppercase;"
                                                oninput="this.value = this.value.toUpperCase()"
                                                required>

                                            @error('Cliente')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>


                                    {{-- RFC --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                RFC
                                            </label>

                                            <input type="text"
                                                class="form-control inputForm"
                                                value="{{ old('RFC') }}"
                                                name="RFC"
                                                placeholder="Ejemplo: PROP56512458"
                                                style="text-transform: uppercase;"
                                                oninput="this.value = this.value.toUpperCase()">

                                        </div>
                                    </div>


                                    {{-- TELEFONO --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Teléfono
                                            </label>

                                            <input type="text"
                                                class="form-control inputForm"
                                                value="{{ old('Telefono') }}"
                                                name="Telefono"
                                                placeholder="Ejemplo: 81 8399 2828">

                                        </div>
                                    </div>


                                    {{-- CORREO --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Correo
                                            </label>

                                            <input type="email"
                                                class="form-control inputForm @error('Correo') is-invalid @enderror"
                                                value="{{ old('Correo') }}"
                                                name="Correo"
                                                placeholder="Ejemplo: hola@protexa.mx">

                                            @error('Correo')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>


                                    {{-- LOGO --}}
                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Logo del cliente
                                            </label>

                                            <input type="file"
                                                class="form-control"
                                                name="logo"
                                                id="logo"
                                                accept="image/png,image/jpeg,image/jpg,image/webp">

                                            <small class="form-text text-muted">
                                                Formatos permitidos: JPG, PNG, WEBP.
                                                Máximo 2 MB.
                                            </small>

                                            <div class="logo-preview-container">

                                                <img id="logoPreview"
                                                    class="logo-preview"
                                                    alt="Vista previa del logo">

                                            </div>

                                        </div>

                                    </div>


                                    {{-- INFORMACION DEL PORTAL --}}
                                    <div class="col-sm-4">

                                        <div class="alert alert-info">

                                            <strong>
                                                <i class="fas fa-globe"></i>
                                                Portal del cliente
                                            </strong>

                                            <br>

                                            <small>
                                                Al guardar el cliente se generará
                                                automáticamente un enlace único
                                                para su portal.
                                            </small>

                                        </div>

                                    </div>

                                    {{-- REGISTRO AL SISTEMA --}}

                                    <div class="col-sm-12">
                                        <div class="row align-items-start">

                                            {{-- OPCIÓN DE REGISTRO --}}
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-form-label">
                                                        ¿Registrar una cuenta al Cliente?
                                                    </label>

                                                    <div>
                                                        <label class="mr-3">
                                                            <input type="radio" name="CuentaCliente" value="no" checked>
                                                            No
                                                        </label>

                                                        <label>
                                                            <input type="radio" name="CuentaCliente" value="si">
                                                            Sí
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- CONTRASEÑAS --}}
                                            <div id="camposContrasena" class="col-md-8" style="display: none;">
                                                <div class="form-group">
                                                    <label class="col-form-label">
                                                        Contraseña del portal del cliente
                                                    </label>

                                                    <div class="row">

                                                    {{-- CONTRASEÑA --}}
                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label class="col-form-label">
                                                                Contraseña:
                                                            </label>

                                                            <input type="password"
                                                                id="ContrasenaUsuario"
                                                                class="form-control @error('ContrasenaUsuario') is-invalid @enderror"
                                                                placeholder="Contraseña"
                                                                name="ContrasenaUsuario">

                                                            @error('ContrasenaUsuario')
                                                                <div class="invalid-feedback">
                                                                    <span>{{ $message }}</span>
                                                                </div>
                                                            @enderror

                                                        </div>

                                                    </div>


                                                    {{-- REPETIR CONTRASEÑA --}}
                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label class="col-form-label">
                                                                Repetir contraseña:
                                                            </label>

                                                            <input type="password"
                                                                id="RepetirContrasena"
                                                                class="form-control @error('RepetirContrasena') is-invalid @enderror"
                                                                placeholder="Repetir contraseña"
                                                                name="RepetirContrasena">

                                                            @error('RepetirContrasena')
                                                                <div class="invalid-feedback">
                                                                    <span>{{ $message }}</span>
                                                                </div>
                                                            @enderror

                                                        </div>

                                                    </div>

                                                </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- BOTONES --}}
                                    <div class="container mt-3">

                                        <div class="float-right">

                                            <button type="submit"
                                                    class="btn btn-info bg-primary">

                                                <i class="fas fa-save"></i>
                                                Finalizar

                                            </button>

                                        </div>

                                        <div class="float-left">

                                            <button type="button"
                                                    class="btn btn-info bg-success"
                                                    id="guardarContinuarClientes">

                                                <i class="fas fa-plus"></i>
                                                Guardar y continuar

                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

@stop


@section('js')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/session-handler.js') }}"></script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>

<script src="{{ asset('js/notificaciones.js') }}"></script>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Previsualizar logo
    |--------------------------------------------------------------------------
    */

    $('#logo').on('change', function (event) {

        const archivo = event.target.files[0];

        if (!archivo) {

            $('#logoPreview').hide();

            return;
        }

        // Validar tamaño
        if (archivo.size > 2 * 1024 * 1024) {

            Swal.fire(
                'Archivo demasiado grande',
                'El logo no debe superar los 2 MB.',
                'warning'
            );

            $('#logo').val('');
            $('#logoPreview').hide();

            return;
        }

        // Validar imagen
        if (!archivo.type.startsWith('image/')) {

            Swal.fire(
                'Archivo inválido',
                'Seleccione una imagen válida.',
                'warning'
            );

            $('#logo').val('');
            $('#logoPreview').hide();

            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {

            $('#logoPreview')
                .attr('src', e.target.result)
                .show();

        };

        reader.readAsDataURL(archivo);

    });


    /*
    |--------------------------------------------------------------------------
    | Guardar y continuar
    |--------------------------------------------------------------------------
    */

    $('#guardarContinuarClientes').on('click', function (event) {

        event.preventDefault();

        let cliente = $('input[name="Cliente"]').val().trim();

        if (cliente === '') {

            Swal.fire(
                'Error',
                'El campo Cliente es obligatorio.',
                'error'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | FormData
        |--------------------------------------------------------------------------
        | Importante:
        | serialize() NO envía archivos.
        */

        let form = document.getElementById('ClienteForm');

        let formData = new FormData(form);


        $.ajax({

            url: $('#ClienteForm').attr('action'),

            method: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function (response) {

                if (response.success) {

                    Swal.fire(
                        'Éxito',
                        response.message ?? 'Los datos han sido guardados correctamente.',
                        'success'
                    );

                    $('#ClienteForm')[0].reset();

                    $('#logoPreview').hide();

                } else {

                    Swal.fire(
                        'Error',
                        response.message ?? 'No se pudo guardar el cliente.',
                        'error'
                    );

                }

            },

            error: function (xhr) {

                let mensaje = 'Ocurrió un error al guardar los datos.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    mensaje = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    mensaje = xhr.responseJSON.message;
                }

                Swal.fire(
                    'Error',
                    mensaje,
                    'error'
                );

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Evitar Enter
    |--------------------------------------------------------------------------
    */

    document.getElementById('ClienteForm').addEventListener('keydown', function(event) {

        if (event.key === 'Enter') {

            event.preventDefault();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | LocalStorage
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '#ClienteForm input:not([type="file"]), #ClienteForm textarea, #ClienteForm select'
    ).forEach(function(input) {

        input.addEventListener('input', function() {

            localStorage.setItem(
                'ClienteForm_' + input.name,
                input.value
            );

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Restaurar LocalStorage
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll(
            '#ClienteForm input:not([type="file"]), #ClienteForm textarea, #ClienteForm select'
        ).forEach(function(input) {

            let value = localStorage.getItem(
                'ClienteForm_' + input.name
            );

            if (value !== null) {

                input.value = value;

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Limpiar LocalStorage
    |--------------------------------------------------------------------------
    */

    document.getElementById('ClienteForm').addEventListener('submit', function() {

        document.querySelectorAll(
            '#ClienteForm input:not([type="file"]), #ClienteForm textarea, #ClienteForm select'
        ).forEach(function(input) {

            localStorage.removeItem(
                'ClienteForm_' + input.name
            );

        });

    });

});

document.addEventListener('DOMContentLoaded', function () {

    const radios = document.querySelectorAll(
        'input[name="CuentaCliente"]'
    );

    const camposContrasena = document.getElementById(
        'camposContrasena'
    );

    const contrasena = document.getElementById(
        'ContrasenaUsuario'
    );

    const repetirContrasena = document.getElementById(
        'RepetirContrasena'
    );


    function toggleCliente() {

        const radioSeleccionado = document.querySelector(
            'input[name="CuentaCliente"]:checked'
        );

        if (!radioSeleccionado) {
            return;
        }

        if (radioSeleccionado.value === 'si') {

            // Mostrar campos
            camposContrasena.style.display = 'block';

            // Hacer obligatorios los campos
            contrasena.required = true;
            repetirContrasena.required = true;

        } else {

            // Ocultar campos
            camposContrasena.style.display = 'none';

            // Quitar obligatoriedad
            contrasena.required = false;
            repetirContrasena.required = false;

            // Limpiar contraseñas
            contrasena.value = '';
            repetirContrasena.value = '';
        }
    }


    // Detectar cambio entre Sí / No
    radios.forEach(function (radio) {

        radio.addEventListener('change', toggleCliente);

    });


    // Ejecutar al cargar la página
    toggleCliente();

});
</script>

@endsection