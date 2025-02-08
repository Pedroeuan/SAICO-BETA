@extends('adminlte::page')

@section('title', 'Crear Reporte')

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
<h3 align="center">REPORTE DE: {{ $Prueba->Nombre }}</h3>
<h4 align="center">{{$formatoNombrePersonalizado}}"</h4>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OC" action="{{route('OC.storeOC')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <button id="addRowBtn" type="button" class="btn-redondo">Datos Generales</button>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha:</label>
                                            <input type="text" class="form-control inputForm @error('Fecha') is-invalid @enderror" name="Fecha"  placeholder="Ejemplo: 29/01/2025" value="{{old('Fecha')}}">
                                            @error('Fecha')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control inputForm @error('No_Reporte') is-invalid @enderror" name="No_Reporte"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('No_Reporte')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control inputForm @error('Cliente') is-invalid @enderror" name="Cliente"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('No_Reporte')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control inputForm @error('Contrato') is-invalid @enderror" name="Contrato"  placeholder="Ejemplo: 640853841" value="{{old('Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto"  placeholder="Ejemplo: 640853841" value="{{old('Proyecto')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <input type="text" class="form-control inputForm @error('Orden_Trabajo') is-invalid @enderror" name="Orden_Trabajo"  placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . . " value="{{old('Orden_Trabajo')}}">
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">No. Reporte</label>
                                            <input type="text" class="form-control inputForm @error('No_Reporte') is-invalid @enderror" name="No_Reporte"  placeholder="Ejemplo: 077-8DUCTOS-24" value="{{old('No_Reporte')}}">
                                            @error('No_Reporte')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control inputForm @error('Cliente') is-invalid @enderror" name="Cliente"  placeholder="Ejemplo: PERMADUCTO S.A DE C.V." value="{{old('No_Reporte')}}">
                                            @error('Cliente')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Contrato</label>
                                            <input type="text" class="form-control inputForm @error('Contrato') is-invalid @enderror" name="Contrato"  placeholder="Ejemplo: 640853841" value="{{old('Contrato')}}">
                                            @error('Contrato')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto"  placeholder="Ejemplo: 640853841" value="{{old('Proyecto')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Trabajo</label>
                                            <input type="text" class="form-control inputForm @error('Orden_Trabajo') is-invalid @enderror" name="Orden_Trabajo"  placeholder="Ejemplo: OT-03 INGENIERÍA, PROCURA, CONSTRUCCIÓN DE UN OLEOGASODUCTO . . . . " value="{{old('Orden_Trabajo')}}">
                                            @error('Orden_Trabajo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">FECHA</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <button id="addRowBtn" type="button" class="btn-redondo">Datos de la Inspección de Particulas</button>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR</label>
                                            <input type="text" class="form-control inputForm" name="Fecha_solicitud" >
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN</label>
                                            <input type="text" class="form-control inputForm" name="Tipo_servicio" placeholder="" value="{{old('Tipo_servicio')}}">
                                            </div>
                                    </div>

                                    <button id="addRowBtn" type="button" class="btn-redondo">Datos de la Inspección de Contraste</button>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">LOTE</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>

                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">COLOR</label>
                                            <input type="text" class="form-control inputForm" name="Fecha_solicitud" >
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">APLICACIÓN</label>
                                            <input type="text" class="form-control inputForm" name="Tipo_servicio" placeholder="" value="{{old('Tipo_servicio')}}">
                                            </div>
                                    </div>

                                    <button id="addRowBtn" type="button" class="btn-redondo">Datos de la Inspección de Equipo</button>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MARCA</label>
                                            <input type="text" class="form-control inputForm @error('Numero_OC') is-invalid @enderror" name="Numero_OC"  placeholder="" value="{{old('Numero_OC')}}">
                                            @error('Numero_OC')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">MODELO</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Requisicion" placeholder="" value="{{old('Requisicion')}}">
                                            @error('Requisicion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">N/S</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto" placeholder="" value="{{old('Proyecto')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CORRIENTE</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">DISTANCIA ENTRE PATAS</label>
                                            <input type="text" class="form-control inputForm" name="Fecha_solicitud" >
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <hr style="border: 1px solid #000000; margin: 0;">
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TIPO DE LUZ</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Requisicion" placeholder="" value="{{old('Requisicion')}}">
                                            @error('Requisicion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">CONDICIÓN SUPERFICIAL</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto" placeholder="" value="{{old('Proyecto')}}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">INTENCIDAD</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="" value="{{old('Lugar_trabajo')}}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">TEMPERATURA DE PRUEBA</label>
                                            <input type="text" class="form-control inputForm" name="Fecha_solicitud" >
                                        </div>
                                    </div>

                                    <input type="hidden" id="dynamicTableData" name="dynamicTableData">

                                    <button id="addRowBtn" type="button" class="btn-redondo">Datos de la Inspección de Particulas</button>

                                    <table id="dynamicTable" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NO°</th>
                                                <th>NO° JUNTA</th>
                                                <th>NO° INDICACIÓN</th>
                                                <th>TIPO DE INDICACIÓN</th>
                                                <th>DIM. DE INDICACIÓN</th>
                                                <th>DIM. DE INDICACIÓN</th>
                                                <th>DIM. DE INDICACIÓN</th>
                                                <th>LOCALIZACIÓN</th>
                                                <th>EVALUACIÓN</th>
                                                <th>LONGITUD INSPECCIONADA</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas dinámicas aparecerán aquí -->
                                        </tbody>
                                    </table>
                                    
                                    <p>
    
                                        <div>
                                            <button id="addRowBtn" type="button" class="btn-redondo">SIMBOLOGÍA</button>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>NOMBRE</th>
                                                            <th>ABREVIATURA</th>
                                                            <th>NOMBRE</th>
                                                            <th>ABREVIATURA</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>NO REPRESENTA INDICACIÓN RELEVANTE</td>
                                                            <td>NPIR</td>
                                                            <td>DAÑO MECÁNICO</td>
                                                            <td>DM</td>
                                                            
                                                        </tr>
                                                        <tr>
                                                            <td>POROSIDAD TUBULAR</td>
                                                            <td>PT</td>
                                                            <td>GRIETA</td>
                                                            <td>G</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SOCAVADO</td>
                                                            <td>S</td>
                                                            <td>CRATER</td>
                                                            <td>C</td>
                                                        </tr>
                                                        <tr>
                                                            <td>ZONA DE GRIETAS</td>
                                                            <td>ZG</td>
                                                            <td>POROSIDAD</td>
                                                            <td>P</td>
                                                        </tr>
                                                        <tr>
                                                            <td>INDICACIÓN LINEAL</td>
                                                            <td>IL</td>
                                                            <td>FALTA DE FUSIÓN</td>
                                                            <td>FF</td>
                                                        </tr>
                                                        <tr>
                                                            <td>ZONA DE POROS</td>
                                                            <td>ZP</td>
                                                            <td>INDICACIÓN REDONDEADA</td>
                                                            <td>IR</td>
                                                        </tr>
                                                        
                                                        <!-- Agrega más filas según sea necesario -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label" for="inputSuccess">Comentario</label>
                                                <textarea class="form-control is-waning" id="inputSuccess" name="Comentario" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{old('Comentario')}}</textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4 text-center">
                                                <hr style="border: 1px solid #000; width: 80%;">
                                                <p>Nombre de la Persona 1</p>
                                                <p>Puesto de la Persona 1</p>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <hr style="border: 1px solid #000; width: 80%;">
                                                <p>Nombre de la Persona 2</p>
                                                <p>Puesto de la Persona 2</p>
                                            </div>
                                            <div class="col-sm-4 text-center">
                                                <hr style="border: 1px solid #000; width: 80%;">
                                                <p>Nombre de la Persona 3</p>
                                                <p>Puesto de la Persona 3</p>
                                            </div>
                                        </div>
                                        
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
<script>

    /*Prevenir el Enter*/
    document.getElementById('OC').addEventListener('keydown', function(event) {
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
                <td><input type="text" class="form-control" name="marca[]" placeholder="NO°"></td>
                <td><input type="text" class="form-control" name="modelo[]" placeholder="NO° JUNTA"></td>
                <td><input type="text" class="form-control" name="lote[]" placeholder="NO° INDICACIÓN"></td>
                <td><input type="text" class="form-control" name="tipo[]" placeholder="TIPO DE INDICACIÓN"></td>
                <td><input type="text" class="form-control" name="color[]" placeholder="LARGO"></td>
                <td><input type="text" class="form-control" name="aplicacion[]" placeholder="ANCHO"></td>
                <td><input type="text" class="form-control" name="lote[]" placeholder="0"></td>
                <td><input type="text" class="form-control" name="tipo[]" placeholder="H.T."></td>
                <td><input type="text" class="form-control" name="color[]" placeholder="EVALUACIÓN"></td>
                <td><input type="text" class="form-control" name="aplicacion[]" placeholder="LONGITUD INSPECCIONADA"></td>

                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`;
            $('#dynamicTable tbody').append(newRow);
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });
    });
        /*document.getElementById("addRowBtn").addEventListener("click", function() {
            const tableBody = document.querySelector("#dynamicTable tbody");
            const rowCount = tableBody.rows.length + 1;

            // Crear una nueva fila
            const newRow = document.createElement("tr");

            // Celda 1: Número de fila
            const cell1 = document.createElement("td");
            cell1.textContent = rowCount;
            newRow.appendChild(cell1);

            // Celda 2: Input para Unidad
            const cell2 = document.createElement("td");
            const unidadInput = document.createElement("input");
            unidadInput.type = "text";
            unidadInput.placeholder = "Unidad/Medida";
            unidadInput.style.width = "100%";
            cell2.appendChild(unidadInput);
            newRow.appendChild(cell2);

            // Celda 3: Input para Cantidad
            const cell3 = document.createElement("td");
            const cantidadInput = document.createElement("input");
            cantidadInput.type = "number";
            cantidadInput.placeholder = "Cantidad";
            cantidadInput.style.width = "100%";
            cell3.appendChild(cantidadInput);
            newRow.appendChild(cell3);

            // Celda 4: Input para Descripcion
            const cell4 = document.createElement("td");
            const DescripcionInput = document.createElement("textarea");
            //DescripcionInput.type = "text";
            DescripcionInput.placeholder = "Descripcion";
            DescripcionInput.style.width = "100%";
            cell4.appendChild(DescripcionInput);
            newRow.appendChild(cell4);


            // Celda 4: Botón de eliminar
            const cell5 = document.createElement("td");
            const deleteBtn = document.createElement("button");
            deleteBtn.type = "button";
            deleteBtn.className = "btn btn-danger btnEliminar";
            deleteBtn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';
            deleteBtn.addEventListener("click", function() {
                tableBody.removeChild(newRow);
            });
            cell5.appendChild(deleteBtn);
            newRow.appendChild(cell5);

            // Agregar la fila a la tabla
            tableBody.appendChild(newRow);
        });*/

        document.getElementById('OC').addEventListener('submit', function(e) {
            const tableBody = document.querySelector("#dynamicTable tbody");
            const rows = tableBody.querySelectorAll("tr");
            const tableData = [];

            rows.forEach(row => {
                const unidad = row.querySelector('td:nth-child(2) input').value;
                const cantidad = row.querySelector('td:nth-child(3) input').value;
                const descripcion = row.querySelector("textarea[placeholder='Descripcion']").value; // Capturar el valor del textarea

                // Añadir los datos de la fila al array
                tableData.push({
                    unidad: unidad,
                    cantidad: cantidad,
                    descripcion: descripcion
                });
            });

            // Convertir el array a JSON y asignarlo al campo oculto
            document.getElementById('dynamicTableData').value = JSON.stringify(tableData);
        });

    </script>
@endsection


