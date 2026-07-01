
@extends('adminlte::page')

@section('title', 'Formatos')

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
<h3 align="center">Edición de la Norma o codigo Aplicable y Formato</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="Prueba_Norma_Codigo_Formato" action="{{ route('Pruebas.Norma_Codigo.Formatos.UpdateCreateFormato', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">
                                    <div class="row justify-content-center">
                                        <div class="col-sm-4">
                                            <div class="form-group text-center">
                                                <label class="col-form-label" for="Norma_Codigo">Norma o Codigo</label>
                                                <input class="form-control inputForm @error('Norma_Codigo') is-invalid @enderror" name="Norma_Codigo" id="Norma_Codigo" type="text" value="{{ $Norma_Codigo->Nombre }}" style="text-align: center;">
                                                @error('Norma_Codigo')
                                                    <div class="invalid-feedback"><span>{{ $message }}</span></div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <button id="addRowBtn" type="button" class="btn-redondo">Agregar Formato</button>

                                    <table id="Formato" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Formato</th>
                                                <th>Procedimiento</th>
                                                <th>PDF</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php $count = 0; @endphp
                                        @foreach ($Formatos as $Formato)
                                        @php $count++; @endphp
                                        <tr id="row-{{ $Formato->idFormato }}">
                                                <td>{{ $count }}</td>
                                                <td><input type="text" class="form-control" name="Formato[{{ $Formato->idFormato }}]" value="{{ $Formato->Nombre ?? 'N/A' }}"></td>
                                                <td><input type="text" class="form-control" name="NameProce[{{ $Formato->idFormato }}]" value="{{ $Formato->Procedimiento ?? 'N/A' }}"></td>
                                                <td>
                                                    @php 
                                                     //dd($Formato->PDF);    
                                                    @endphp
                                                    @if (empty($Formato->PDF) && !in_array($Formato->PDF, ['ESPERA DE DATO', 'ESPERA DE DATOS']))
                                                        <div class="d-flex align-items-end gap-2">

                                                            <div class="form-group flex-grow-1 mb-0">
                                                                {{--<label class="col-form-label">SUBIR PROCEDIMIENTO</label>--}}
                                                                <input type="file"
                                                                    class="form-control inputForm @if ($errors->any()) is-invalid @endif"
                                                                    name="Procedimiento">

                                                                @if ($errors->any())
                                                                    <div class="invalid-feedback">
                                                                        Por favor, vuelva a cargar el archivo de ser necesario.
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <span class="btn btn-secondary mb-1"
                                                                style="cursor:not-allowed;">
                                                                <i class="far fa-file-pdf"></i>
                                                            </span>

                                                        </div>
                                                        @else
                                                            <div class="d-flex align-items-end gap-2">

                                                            <div class="form-group flex-grow-1 mb-0">
                                                                {{--<label class="col-form-label">SUBIR PROCEDIMIENTO</label>--}}
                                                                <input type="file"
                                                                    class="form-control inputForm @if ($errors->any()) is-invalid @endif"
                                                                    name="Procedimiento">

                                                                @if ($errors->any())
                                                                    <div class="invalid-feedback">
                                                                        Por favor, vuelva a cargar el archivo de ser necesario.
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <a href="{{ asset('storage/' . $Formato->PDF) }}" 
                                                                class="btn btn-primary" target="_blank">
                                                                    <i class="far fa-file-pdf"></i>
                                                            </a>
                                                    @endif
                                                </td>
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
                var rowCount = $('#Formato tbody tr').length; // Inicializar con el número de filas existentes

                function updateRowNumbers() {
                    $('#Formato tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1);
                    });
                    rowCount = $('#Formato tbody tr').length; // Actualizar rowCount
                }

                $('#addRowBtn').click(function() {
                    rowCount++;
                    var newRow = `<tr>
                        <td>${rowCount}</td>
                        <td><input type="text" class="form-control" name="Formato[new_${rowCount}]" placeholder="Formato" required></td>
                        <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>`;
                    $('#Formato tbody').append(newRow);
                });

                $('#Formato').on('click', '.btnEliminar', function() {
                    var row = $(this).closest('tr');
                    var id = row.attr('id') ? row.attr('id').split('-')[1] : null; // Obtener el ID del registro si existe

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡De Eliminarlo!",
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminarlo!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (id) {
                                // Si la fila tiene un ID, realizar la eliminación en el servidor
                                $.ajax({
                                    url: '/Eliminar/Formato/Tabla/' + id,
                                    type: 'DELETE',
                                    data: {
                                        _token: '{{ csrf_token() }}' // Asegúrate de incluir el token CSRF
                                    },
                                    success: function(response) {
                                        row.remove();
                                        updateRowNumbers();
                                        Swal.fire(
                                            'Eliminado!',
                                            'El registro ha sido eliminado.',
                                            'success'
                                        );
                                    },
                                    error: function(xhr) {
                                        Swal.fire(
                                            'Error!',
                                            'Hubo un problema al eliminar el registro.',
                                            'error'
                                        );
                                    }
                                });
                            } else {
                                // Si la fila no tiene un ID, simplemente eliminarla del DOM
                                row.remove();
                                updateRowNumbers();
                                Swal.fire(
                                    'Eliminado!',
                                    'El registro ha sido eliminado.',
                                    'success'
                                );
                            }
                        }
                    });
                });
            });

            $('#Prueba_Norma_Codigo_Formato').submit(function(event) {
        if ($('#Formato tbody tr').length === 0) {
            event.preventDefault();
            Swal.fire({
                title: 'Advertencia',
                text: 'Debe agregar al menos una norma o código aplicable.',
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });
        }
    });

    /*Prevenir el Enter*/
    document.getElementById('Prueba_Norma_Codigo_Formato').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    </script>
@endsection


