
@extends('adminlte::page')

@section('title', 'Orden de Trabajo/Servicio/Compra')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
        table {
            width: 100%; /* Opcional: Para que ocupe todo el ancho disponible */
            border-collapse: collapse; /* Elimina los espacios entre bordes */
        }

        table th, table td {
            text-align: center; /* Centra el texto horizontalmente */
            vertical-align: middle; /* Centra el texto verticalmente */
            padding: 8px; /* Espaciado interno para mayor claridad */
        }

        table input {
            text-align: center; /* Centra el texto dentro de los inputs */
            box-sizing: border-box; /* Garantiza que los inputs respeten los bordes */
        }
        #addRowBtn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #addRowBtn:hover {
            background-color: #0056b3;
        }
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
<br>
<h3 align="center">Registro de Orden de Trabajo/Servicio/Compra </h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OT_SForm" action="{{route('OC.storeOC')}}" method="post" enctype="multipart/form-data">
                                @csrf 

                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label">¿Cliente registrado?</label>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cliente_tipo" id="cliente_si" value="si" checked>
                                                <label class="form-check-label" for="cliente_si">Sí</label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="cliente_tipo" id="cliente_no" value="no">
                                                <label class="form-check-label" for="cliente_no">No</label>
                                            </div>

                                            <!-- SELECT -->
                                            <select class="form-control inputForm" name="Cliente" id="cliente_select" required>
                                                <option value="">Seleccione un cliente</option>
                                                @foreach ($Clientes as $cliente)
                                                    <option value="{{ $cliente->Cliente }}">
                                                        {{ $cliente->Cliente }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <!-- INPUT MANUAL -->
                                            <input type="text" 
                                                class="form-control inputForm mt-2 d-none" 
                                                id="cliente_input"
                                                name="Cliente_manual"
                                                placeholder="Escriba el nombre del cliente">

                                            @error('Cliente')
                                                <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror

                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha</label>
                                            <input type="date" class="form-control inputForm" name="Fecha" placeholder="Ejemplo: 640853841" value="{{old('Fecha')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar</label>
                                            <input type="text" class="form-control inputForm @error('Lugar') is-invalid @enderror" name="Lugar"  placeholder="Ejemplo: Patio de fabricación" value="{{old('Lugar')}}">
                                            @error('Lugar')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label">
                                                ¿Contrato existente?
                                            </label>

                                            <div class="ml-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="TieneContrato" id="contrato_si" value="si" checked>
                                                    <label class="form-check-label" for="contrato_si">Sí</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="TieneContrato" id="contrato_no" value="no">
                                                    <label class="form-check-label" for="contrato_no">No</label>
                                                </div>
                                            </div>

                                            <!-- Input visible solo si es "SI" -->
                                            <input type="text"
                                                id="campoContrato"
                                                class="form-control inputForm"
                                                name="Contrato"
                                                placeholder="Ejemplo: 640853841"
                                                value="{{ old('Contrato') }}"
                                                required>
                                        </div>

                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <textarea class="form-control  is-waning" id="inputSuccess" name="Detalles_Generales[Proyecto]" placeholder="Ejemplo: INGENIERÍA, PROCURA, CONSTRUCCIÓN DE DUCTOS MARINOS NUEVOS PARA MANEJO DE PRODUCCIÓN DE PLATAFORMAS GENÉRICAS, A INSTALARSE EN LA SONDA DE CAMPECHE, GOLFO DE MÉXICO ...">{{old('Detalles_Generales.Proyecto')}}</textarea>
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Material</label>
                                            <input type="text" class="form-control  inputForm @error('Material') is-invalid @enderror" name="Detalles_Generales[Material]"  placeholder="Ejemplo:  " value="{{old('Detalles_Generales.Material')}}">
                                            @error('Material')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Plano/Isometrico</label>
                                            <input type="text" class="form-control inputForm @error('Plano_isometrico') is-invalid @enderror" name="Detalles_Generales[Plano_isometrico]" placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . " value="{{old('Detalles_Generales.Plando_isometrico')}}">
                                            @error('Plano_isometrico')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo Original</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OC_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <!--<label class="col-form-label" for="inputSuccess">Tipo</label>-->
                                            <input type="hidden" class="form-control inputForm" placeholder="" name="Estatus" value="OC">
                                        </div>
                                    </div>

                                    <input type="hidden" id="dynamicTableData" name="dynamicTableData">

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Detalles</button>

                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Unidad/Medida</th>
                                                <th>Cantidad</th>
                                                <th>Descripción</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                        </tbody>
                                    </table>
                                    
                                    <p>
                                    <p>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <div class="float-left">
                                            <!--<button type="button" class="btn btn-info bg-success" id="guardarContinuarOC">Guardar y continuar</button>-->
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </section>
@stop


@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script src="{{ asset('js/OT_S.js') }}"></script>
<script>

    /*Prevenir el Enter*/
    document.getElementById('OT_SForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    $(document).ready(function() {
        var rowCount = 0;

        function updateRowNumbers() {
            $('#dynamicTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            rowCount = $('#dynamicTable tbody tr').length;
        }

        $('#addRowBtn').click(function() {
            rowCount++;
            var newRow = `<tr>
                <td>${rowCount}</td>
                <td><input type="text" class="form-control" name="unidad[]" placeholder="Unidad/Medida"></td>
                <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad"></td>
                <td><textarea class="form-control" name="descripcion[]" placeholder="Descripcion"></textarea></td>
                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`;
            $('#dynamicTable tbody').append(newRow);
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });
    });

    /*document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('OT_SForm');

        const radioSi = document.getElementById('cliente_si');
        const radioNo = document.getElementById('cliente_no');
        const selectCliente = document.getElementById('cliente_select');
        const inputCliente = document.getElementById('cliente_input');

        /* ==============================
        MOSTRAR / OCULTAR SELECT O INPUT
        ============================== */
        /*function toggleCliente() {

            if (radioSi.checked) {
                selectCliente.classList.remove('d-none');
                inputCliente.classList.add('d-none');
                selectCliente.setAttribute('required', true);
                inputCliente.removeAttribute('required');
            } else {
                selectCliente.classList.add('d-none');
                inputCliente.classList.remove('d-none');
                inputCliente.setAttribute('required', true);
                selectCliente.removeAttribute('required');
            }
        }

        radioSi.addEventListener('change', toggleCliente);
        radioNo.addEventListener('change', toggleCliente);
        toggleCliente();


        // SELECT
        selectCliente.addEventListener('change', function() {
            if (radioSi.checked) {
                generarFolio(selectCliente.value);
            }
        });

        // INPUT manual
        inputCliente.addEventListener('input', function() {
            if (radioNo.checked) {
                generarFolio(inputCliente.value);
            }
        });


        /* ==============================
        VALIDACIÓN AL ENVIAR
        ============================== */
        /*form.addEventListener('submit', function(event) {

            let clienteFinal = '';

            if (radioSi.checked) {
                clienteFinal = selectCliente.value;
            } else {
                clienteFinal = inputCliente.value.trim();
            }

            if (clienteFinal === '') {
                event.preventDefault();
                alert("Por favor, ingresa o selecciona un cliente.");
                return;
            }
        });

        /* ==============================
        PREVENIR ENTER
        ============================== */
        /*form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });


        /* ==============================
        LOCAL STORAGE
        ============================== */
        /*document.querySelectorAll('#OT_SForm input, #OT_SForm textarea, #OT_SForm select').forEach(function(input) {

            input.addEventListener('input', function() {
                localStorage.setItem('OT_SForm' + input.name, input.value);
            });

            let value = localStorage.getItem('OT_SForm' + input.name);
            if (value !== null && input.type !== 'file') {
                input.value = value;
            }
        });

        form.addEventListener('submit', function() {
            document.querySelectorAll('#OT_SForm input, #OT_SForm textarea, #OT_SForm select').forEach(function(input) {
                localStorage.removeItem('OT_SForm' + input.name);
            });
        });

    });*/


    </script>
@endsection