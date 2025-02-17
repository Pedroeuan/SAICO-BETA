
@extends('adminlte::page')

@section('title', 'Orden de Compra')

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
<h3 align="center">Edición de la Orden de Compra</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="OC" action="{{ route('OC.updateOC', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Número de Orden de Compra</label>
                                            <input type="text" class="form-control inputForm @error('Numero_OC') is-invalid @enderror" name="Numero_OC"  placeholder="Ejemplo: 76810" value="{{ $OC->Num_OC }}">
                                            @error('Numero_OC')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Requisición</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Requisicion" placeholder="Ejemplo: 107068-2" value="{{$OC->Requisicion}}">
                                            @error('Requisicion')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Proyecto</label>
                                            <input type="text" class="form-control inputForm @error('Proyecto') is-invalid @enderror" name="Proyecto" placeholder="Ejemplo: PER-04-23 DUCTO ATOYATL-1" value="{{ $OC->Proyecto }}">
                                            @error('Proyecto')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Lugar/Trabajo</label>
                                            <input type="text" class="form-control inputForm" name="Lugar_trabajo" placeholder="Ejemplo: " value="{{ $OC->Lugar_trabajo }}">
                                        </div>
                                    </div>

                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha</label>
                                            @if($OC->Fecha_solicitud == '2001-01-01')
                                                    <input type="date" class="form-control inputForm" name="Fecha_solicitud">
                                                @else
                                                    <input type="date" class="form-control inputForm" name="Fecha_solicitud" value="{{ $OC->Fecha_solicitud }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Tipo de Servicio</label>
                                            <input type="text" class="form-control inputForm" name="Tipo_servicio" placeholder="Ejemplo: PT, R.G., MT, UT, DUREZA " value="{{ $OC->Tipo_servicio }}">
                                            </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Orden de Compra Actual</label>
                                            <input type="file" class="form-control inputForm @if ($errors->any()) is-invalid @endif" name="OC_archivo" placeholder="">
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($OC->OC_archivo != 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit --> 
                                            <label class="col-form-label" for="inputSuccess">Ver Orden de Compra Actual</label>  
                                            <div>                                            
                                                <a href="{{ asset('storage/' . $OC->OC_archivo) }}" target="_blank" class="btn btn-primary long-button" role="button"><i class="fa fa-eye" aria-hidden="true"></i></a>                                                                                     
                                            </div> 
                                        </div>
                                    </div>
                                    @elseif($OC->OC_archivo == 'ESPERA DE DATO')
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->   
                                            <label class="col-form-label" for="inputSuccess">No se encontraron Certificados</label>                                              
                                                <a target="_blank" role="button" class="btn btn-secondary long-button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                                 
                                        </div>
                                    </div>
                                    @endif
                                    
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
                                                <th>Acción</th>
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
<script>
    /*Prevenir el Enter*/
    document.getElementById('OC').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    /*TRAE LOS DATOS DE DETALLES_OC*/
       const detallesOC = @json($detallesOC); // Convertir en un arreglo JSON para JavaScript
    //console.log(detallesOC); // Verifica la estructura aquí

    document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector("#dynamicTable tbody");

    // Iterar sobre cada detalle y agregarlo a la tabla
    detallesOC.forEach((detalle, index) => {
            const newRow = document.createElement("tr");

            // Celda 1: Número de fila
            const cell1 = document.createElement("td");
            cell1.textContent = index + 1; // Índice basado en 1
            newRow.appendChild(cell1);

            const cell2 = document.createElement("td");
            const unidadInput = document.createElement("input");
            unidadInput.type = "text";
            unidadInput.value = detalle.unidad; // Obtiene 'unidad' del JSON
            unidadInput.className = "form-control"; // Añadir clase de Bootstrap
            unidadInput.name = "unidad[]"; // Añadir nombre para el input
            unidadInput.placeholder = "Unidad/Medida"; // Añadir placeholder
            cell2.appendChild(unidadInput);
            newRow.appendChild(cell2);

            const cell3 = document.createElement("td");
            const cantidadInput = document.createElement("input");
            cantidadInput.type = "number";
            cantidadInput.value = detalle.cantidad; // Obtiene 'cantidad' del JSON
            cantidadInput.className = "form-control"; // Añadir clase de Bootstrap
            cantidadInput.name = "cantidad[]"; // Añadir nombre para el input
            cantidadInput.placeholder = "Cantidad"; // Añadir placeholder
            cell3.appendChild(cantidadInput);
            newRow.appendChild(cell3);


            const cell4 = document.createElement("td");
            const descripcionInput = document.createElement("textarea");
            descripcionInput.value = detalle.descripcion; // Obtiene 'descripcion' del JSON
            descripcionInput.className = "form-control"; // Añadir clase de Bootstrap
            descripcionInput.name = "descripcion[]"; // Añadir nombre para el textarea
            descripcionInput.placeholder = "Descripcion"; // Añadir placeholder
            cell4.appendChild(descripcionInput);
            newRow.appendChild(cell4);


            const cell5 = document.createElement("td");
            const deleteBtn = document.createElement("button");
            deleteBtn.type = "button";
            deleteBtn.className = "btn btn-danger btnEliminar";
            deleteBtn.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i>';

            deleteBtn.addEventListener("click", function () {
                tableBody.removeChild(newRow);
            });

            cell5.appendChild(deleteBtn);
            newRow.appendChild(cell5);

            // Agregar la fila al cuerpo de la tabla
            tableBody.appendChild(newRow);
        });
        
        let rowCount = tableBody.rows.length; // Inicializar con el número de filas existentes
        //console.log(rowCount);

        $(document).ready(function() {
            var rowCount = $('#dynamicTable tbody tr').length; // Inicializar con el número de filas existentes
            //console.log(rowCount);

            function updateRowNumbers() {
                $('#dynamicTable tbody tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
                rowCount = $('#dynamicTable tbody tr').length; // Actualizar rowCount
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
    });

    


    document.getElementById('OC').addEventListener('submit', function(e) {
            const tableBody = document.querySelector("#dynamicTable tbody");
            const rows = tableBody.querySelectorAll("tr");
            const tableData = [];

            rows.forEach(row => {
                const unidadElement = row.querySelector('td:nth-child(2) input');
                const cantidadElement = row.querySelector('td:nth-child(3) input');
                const descripcionElement = row.querySelector('td:nth-child(4) textarea');


                const unidad = unidadElement ? unidadElement.value : 'ESPERA DE DATO';
                const cantidad = cantidadElement ? cantidadElement.value : 'ESPERA DE DATO';
                const descripcion = descripcionElement ? descripcionElement.value : 'ESPERA DE DATO';

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


