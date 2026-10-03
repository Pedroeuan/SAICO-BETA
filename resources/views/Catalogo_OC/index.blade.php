@extends('adminlte::page')

@section('title', 'Catalogo')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

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

    /* Centrar texto de la tabla */
    #tablaJs th,
    #tablaJs td {
        text-align: center;
        vertical-align: middle;
    }

</style>

@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
<form role="form">
    <div class="box">
        <h3 align="center">Catalogo de Servicios</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs"
                class="table table-bordered table-striped dt-responsive tablas">

                <thead>

                    <tr>

                        <th>Nombre</th>

                        <th>Descripción</th>

                        <th>Unidad</th>

                        <th>Imagen</th>

                        <th>Editar</th>

                        <th>Eliminar</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($Catalogo_OC as $catalogos)

                        <tr>

                            {{-- Nombre --}}

                            <td>
                                {{ $catalogos->Nombre }}
                            </td>


                            {{-- Descripción --}}

                            <td>
                                {{ $catalogos->Descripcion }}
                            </td>


                            {{-- Unidad --}}

                            <td>
                                {{ $catalogos->Unidad }}
                            </td>
                            
                            {{-- Imagen --}}

                            <td>

                                @if($catalogos->Imagen && $catalogos->Imagen !== 'ESPERA DE DATOS')

                                    <img src="{{ asset('storage/' . $catalogos->Imagen) }}"
                                        alt="Logo {{ $catalogos->Nombre }}"
                                        style="
                                            width: 60px;
                                            height: 60px;
                                            object-fit: contain;
                                            border-radius: 8px;
                                            padding: 3px;
                                            border: 1px solid #ddd;
                                        ">

                                @else

                                    <div style="
                                        width: 60px;
                                        height: 60px;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        background:#f1f1f1;
                                        border-radius:8px;
                                        margin:auto;
                                    ">

                                        <i class="fas fa-building text-muted"></i>

                                    </div>

                                @endif

                            </td>

                            {{-- EDITAR --}}

                            <td>

                                <div class="btn-group">

                                    <a href="{{ route('OC.editCatalogo', ['id' => $catalogos->idCatalogo_OC]) }}"
                                        class="btn btn-warning"
                                        role="button">

                                        <i class="fas fa-pencil-alt"></i>

                                    </a>

                                </div>

                            </td>


                            {{-- ELIMINAR --}}

                            <td>

                                <div class="btn-group">

                                    <button type="button"
                                            class="btn btn-danger btnEliminarSolicitud"
                                            idCatalogo_OC="{{ $catalogos->idCatalogo_OC }}">

                                        <i class="fa fa-times"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>
        </div>
    </div>
</form>
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
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>
    let table = new DataTable('#tablaJs', {
        // options
        language: {
                        "decimal": "",
                        "emptyTable": "No hay datos disponibles en la tabla",
                        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                        "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                        "infoPostFix": "",
                        "thousands": ",",
                        "lengthMenu": "Mostrar _MENU_ entradas",
                        "loadingRecords": "Cargando...",
                        "processing": "Procesando...",
                        "search": "Buscar:",
                        "zeroRecords": "No se encontraron registros coincidentes",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        },
                        "aria": {
                            "sortAscending": ": activar para ordenar la columna ascendente",
                            "sortDescending": ": activar para ordenar la columna descendente"
                        }
                    }
    });

$(document).on("click", ".btnEliminarSolicitud", function() {
    var idCatalogo_OC = $(this).attr("idCatalogo_OC");
    Swal.fire({
        title: "¿Seguro de eliminar este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/Catalogo_OC/eliminar/' + idCatalogo_OC,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Eliminado!",
                            text: response.message,
                            icon: "success",
                            didClose: function() {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});

</script>

@endsection