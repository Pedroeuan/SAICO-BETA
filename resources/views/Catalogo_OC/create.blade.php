@extends('adminlte::page')

@section('title', 'Catalogo OC')

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

<h3 align="center">Registro de Catalogo de OC</h3>

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
                                Catalogo de OC
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_1">

                            <form id="CatalogoForm"
                                method="POST"
                                enctype="multipart/form-data"
                                action="{{ route('OC.storeCatalogo') }}">

                                @csrf
                                    {{-- NOMBRE --}}
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Nombre
                                            </label>

                                            <input type="text"
                                                class="form-control inputForm @error('Nombre') is-invalid @enderror"
                                                value="{{ old('Nombre') }}"
                                                name="Nombre"
                                                placeholder="Ejemplo: PROTEXA"
                                                style="text-transform: uppercase;"
                                                oninput="this.value = this.value.toUpperCase()"
                                                required>

                                            @error('Nombre')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>


                                    {{-- DESCRIPCIÓN --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Descripción
                                            </label>
                                        <textarea class="form-control inputForm @error('Descripcion') is-invalid @enderror"
                                                name="Descripcion"
                                                placeholder="Ejemplo: SERVICIO DE LIMPIEZA"
                                                style="text-transform: uppercase;"
                                                oninput="this.value = this.value.toUpperCase()"
                                                required>{{ old('Descripcion') }}</textarea>

                                            @error('Descripcion')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Unidad --}}
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Unidad
                                            </label>

                                            <input type="text"
                                                class="form-control inputForm"
                                                value="{{ old('Unidad') }}"
                                                name="Unidad"
                                                placeholder="Ejemplo: Pzs, Jornada"
                                                style="text-transform: uppercase;"
                                                oninput="this.value = this.value.toUpperCase()">

                                        </div>
                                    </div>

                                    {{-- Imagen --}}
                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label class="col-form-label">
                                                Imagen del producto
                                            </label>

                                            <input type="file"
                                                class="form-control"
                                                name="Imagen"
                                                id="Imagen"
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

                                    {{-- BOTONES --}}
                                    <div class="container mt-3">

                                        <div class="float-right">

                                            <button type="submit"
                                                    class="btn btn-info bg-primary">

                                                <i class="fas fa-save"></i>
                                                Finalizar

                                            </button>

                                        </div>

                                        {{-- <div class="float-left">

                                            <button type="button"
                                                    class="btn btn-info bg-success"
                                                    id="guardarContinuarClientes">

                                                <i class="fas fa-plus"></i>4
                                                Guardar y continuar

                                            </button>

                                        </div>--}}

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

        let form = document.getElementById('CatalogoForm');

        let formData = new FormData(form);


        $.ajax({

            url: $('#CatalogoForm').attr('action'),

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

                    $('#CatalogoForm')[0].reset();

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

    document.getElementById('CatalogoForm').addEventListener('keydown', function(event) {

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
        '#CatalogoForm input:not([type="file"]), #CatalogoForm textarea, #CatalogoForm select'
    ).forEach(function(input) {

        input.addEventListener('input', function() {

            localStorage.setItem(
                'CatalogoForm_' + input.name,
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
            '#CatalogoForm input:not([type="file"]), #CatalogoForm textarea, #CatalogoForm select'
        ).forEach(function(input) {

            let value = localStorage.getItem(
                'CatalogoForm_' + input.name
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

    document.getElementById('CatalogoForm').addEventListener('submit', function() {

        document.querySelectorAll(
            '#CatalogoForm input:not([type="file"]), #CatalogoForm textarea, #CatalogoForm select'
        ).forEach(function(input) {

            localStorage.removeItem(
                'CatalogoForm_' + input.name
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