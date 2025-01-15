
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
    </style>
@endsection

@section('content')
<br>
<br>
<br>
<br>
<h3 align="center">Edición del Tipo de Prueba y Norma o codigo Aplicable</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="Prueba_Norma_Codigo" action="{{ route('Pruebas.Norma_Codigo.update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <div class="form-group text-center">
                                                <label class="col-form-label" for="Tipo_Prueba">Tipo de Prueba</label>
                                                <input class="form-control inputForm @error('Tipo_Prueba') is-invalid @enderror" name="Tipo_Prueba" id="Tipo_Prueba" type="text" placeholder="Análisis Químico, Arreglo de fases, Caracterización de materiales, etc." value="{{ $Prueba->Nombre }}" style="text-align: center;">
                                                @error('Tipo_Prueba')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Norma o Codigo Aplicable</button>

                                    <table id="Norma_Codigo" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Norma o Codigo Aplicable</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $count = 0; @endphp
                                        @foreach ($Norma_Codigo as $NC)
                                        @php $count++; @endphp
                                        <tr id="row-{{ $NC->idNorma_codigo }}">
                                                <td>{{ $count }}</td>
                                                <td>{{ $NC->Nombre ?? 'N/A' }}</td>
                                                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                                            </tr>
                                        @endforeach
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
                                        </div>-->
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

            $(document).ready(function() {
                var rowCount = $('#Norma_Codigo tbody tr').length; // Inicializar con el número de filas existentes

                function updateRowNumbers() {
                    $('#Norma_Codigo tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                    rowCount = $('#Norma_Codigo tbody tr').length; // Actualizar rowCount
                }

                $('#addRowBtn').click(function() {
                    rowCount++;
                    var newRow = `<tr>
                        <td>${rowCount}</td>
                        <td><input type="text" class="form-control" name="codigo[]" placeholder="Codigo o Norma Aplicable"></td>
                        <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>`;
                    $('#Norma_Codigo tbody').append(newRow);
                });

                $('#Norma_Codigo').on('click', '.btnEliminar', function() {
                    var row = $(this).closest('tr');
                    var id = row.attr('id').split('-')[1]; // Obtener el ID del registro
                    console.log(id);

                    $.ajax({
                        url: '/Eliminar/NormaCodigo/Tabla/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                        },
                        success: function(response) {
                            row.remove();
                            updateRowNumbers();
                            alert(response.success);
                        },
                        error: function(xhr) {
                            alert('Error al eliminar el registro');
                        }
                    });
                });
            });

    /*Prevenir el Enter*/
    document.getElementById('Prueba_Norma_Codigo').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    </script>
@endsection


