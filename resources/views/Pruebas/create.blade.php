
@extends('adminlte::page')

@section('title', 'Orden de Compra')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
    </style>
@endsection

@section('content')
<br>
<br>
<br>
<br>
<h3 align="center">Registro de Pruebas</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="Prueba_Norma_Codigo" action="{{route('Prueba_Norma_Codigo.store')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="Tipo_Prueba">Tipo de Prueba</label>
                                            <input class="form-control inputForm @error('Tipo_Prueba') is-invalid @enderror" name="Tipo_Prueba" id="Tipo_Prueba" type="text" placeholder="Análisis Químico, Arreglo de fases, Caracterización de materiales, etc." value="{{ old('Tipo_Prueba') }}">
                                            @error('Tipo_Prueba')
                                                <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="normas_codigos" id="normas_codigos">

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Norma o Codigo Aplicable</button>
                                    <table id="dynamicTable" style="margin: 0 auto; width: 80%;" class="center-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Norma o Codigo Aplicable</th>
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

                                        <!--<div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarEquipos">Guardar y continuar</button>
                                        </di>-->
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
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    document.getElementById('Prueba_Norma_Codigo').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

        document.getElementById("addRowBtn").addEventListener("click", function() {
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
            const No_CoInput = document.createElement("input");
            No_CoInput.type = "text";
            No_CoInput.placeholder = "Norma o Codigo Aplicable";
            No_CoInput.style.width = "100%";
            cell2.appendChild(No_CoInput);
            newRow.appendChild(cell2);

            // Celda 4: Botón de eliminar
            const cell5 = document.createElement("td");
            const deleteBtn = document.createElement("button");
            deleteBtn.textContent = "Eliminar";
            deleteBtn.style.color = "white";
            deleteBtn.style.backgroundColor = "red";
            deleteBtn.style.border = "none";
            deleteBtn.style.padding = "5px 10px";
            deleteBtn.style.cursor = "pointer";
            deleteBtn.addEventListener("click", function() {
            tableBody.removeChild(newRow);
            updateHiddenField();
            });
            cell5.appendChild(deleteBtn);
            newRow.appendChild(cell5);

            // Agregar la fila a la tabla
            tableBody.appendChild(newRow);
            updateHiddenField();
        });

        document.getElementById("Prueba_Norma_Codigo").addEventListener("submit", function() {
            updateHiddenField();
        });

        function updateHiddenField() {
        const tableBody = document.querySelector("#dynamicTable tbody");
        const rows = tableBody.querySelectorAll("tr");
        const data = [];

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const rowData = {
                numero: cells[0].textContent,
                norma_codigo: cells[1].querySelector("input").value
            };
            data.push(rowData);
        });

        document.getElementById("normas_codigos").value = JSON.stringify(data);
    }

    </script>
@endsection


