
@extends('adminlte::page')

@section('title', 'Orden de Trabajo/Servicio/Compra')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
    #tablaJs td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaJs th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
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
<!-- form start -->
<form role="form">
    <div class="box ">
            <br>
        <div class="box-body">
        <h3 align="center">ORDEN DE SERVICIO/TRABAJO</h3>
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Contrato</th>
                        <th>Proyecto/Actividad</th>
                        <th>Material</th>
                        <th>Plano/Isometrico</th>
                        <th>OT Original</th>
                        <th>OT AICO</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($OS as $OSS)
                    <tr>
                        <td>{{ $OSS->cliente->Cliente ?? 'Sin cliente' }}</td>
                        <td>{{ $OSS->formatted_date }}</td>   
                        <td>{{ $OSS->Lugar}}</td>
                        <td>{{ $OSS->Contrato }}</td>
                        <td>{{ $OSS->Proyecto_actividad }}</td>
                        <td>{{ $OSS->Material }}</td>
                        <td>{{ $OSS->Plano_isometrico }}</td>
                        @if($OSS->OT_archivo == 'ESPERA DE DATO' || $OSS->OT_archivo == null)
                                <td>
                                    <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                </td>
                            @else
                                <td><a class="btn btn-primary" href="{{ asset('storage/' . $OSS->OT_archivo) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a></td>
                        @endif
                        <!--PDF GENERADO-->
                        <td>
                            <a class="btn btn-primary" href="{{ route('OT_S.PDF', ['id' => $OSS->idOrden_Servicio]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                        </td>
                        <td>
                            <a href="{{ route('editOT_S.edit', ['id' => $OSS->idOrden_Servicio]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btnEliminarOC" idOC="{{$OSS->idOrden_Serivicio}}"><i class="fa fa-times" aria-hidden="true"></i></button>
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


    $(document).on("click", ".btnEliminarOC", function() {
        var idOC = $(this).attr("idOC");
        Swal.fire({
            title: "¿Seguro de eliminar este elemento?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/OC/eliminar/' + idOC,

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

    
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('manifiestoForm');

        const radioSi = document.getElementById('cliente_si');
        const radioNo = document.getElementById('cliente_no');
        const selectCliente = document.getElementById('cliente_select');
        const inputCliente = document.getElementById('cliente_input');
        const folioInput = document.getElementById('folio');

        /* ==============================
        MOSTRAR / OCULTAR SELECT O INPUT
        ============================== */
        function toggleCliente() {

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

            /* ==============================
        VALIDACIÓN AL ENVIAR
        ============================== */
        form.addEventListener('submit', function(event) {

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
        form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });
    });
</script>

@endsection