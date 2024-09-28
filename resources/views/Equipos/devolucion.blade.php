
@extends('adminlte::page')

@section('title', 'Inventario')

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
    .cantidad-input {
            text-align: center; /* Centra el texto dentro del campo de entrada */
        }
</style>
@endsection

@section('content')
<br>  
<br>
<br>
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Nota</h5>
                Una vez devueltos los equipos y darle click a Concluir Manifiesto y el Estatus cambia a "DEVOLVER", <u>NO</u> se podran realizar más cambios y Devoluciones.
            </p>
    </div>
    <h3 align="center">Devoluciones</h3>
    <form id="devolverForm" action="{{ route('Concluir.Manifiesto', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
    @csrf
    <!-- Campo oculto para enviar todos los idSolicitud -->
    <input type="hidden" name="idSolicitudes" value="{{ json_encode($idsSolicitud) }}">

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-form-label" for="inputSuccess">Entrega</label>
                <input type="text" class="form-control inputForm" name="Entrega_Nombre_Devolucion" value="" required>
                @error('Entrega_Nombre')
                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                @enderror
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-form-label" for="inputSuccess">Recibe</label>
                <input type="text" class="form-control inputForm" name="Recibe_Nombre_Devolucion" value="" required>
                @error('Recibe_Nombre')
                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                @enderror
            </div>
        </div>
    </div>

        <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Devolver</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datosManifiesto as $dato)
                @php 
                //dd($datosManifiesto);
                @endphp
                    <tr>
                        <td>
                        <input type="hidden" name="folio[{{ $dato['Folio'] }}]" id="folio_{{ $dato['Folio'] }}" value="{{ $dato['Folio'] }}">
                        {{ $dato['Folio'] }}
                        </td>
                        <td>{{ $dato['Nombre'] }}</td>
                        <td>
                            <!-- Establecer valor máximo con max="{{ $dato['cantidad'] }}" -->
                            @if($dato['cantidad'] == 1)
                                <input type="number" name="cantidad[{{ $dato['idGeneral_EyC'] }}]" value="{{ $dato['cantidad'] }}" min="1" max="{{ $dato['cantidad'] }}" class="form-control cantidad-input" readonly>
                                    @else
                                <input type="number" name="cantidad[{{ $dato['idGeneral_EyC'] }}]" value="{{ $dato['cantidad'] }}" min="1" max="{{ $dato['cantidad'] }}" class="form-control cantidad-input" required>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-info btn-devolver" role="button" data-nombre="{{ $dato['Nombre'] }}" data-folio="{{ $dato['Folio'] }}"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <div class="container d-flex justify-content-center">
            <button type="submit" class="btn btn-info bg-success" id="ConcluirManifiesto">Concluir Manifiesto</button>
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

/*Prevenir el Enter Devoluciones*/
document.getElementById('devolverForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#tablaJs').DataTable();

    // Asignar eventos a los campos de entrada después de cada redibujado de la tabla
    table.on('draw', function() {
        // Seleccionar todos los campos de entrada de cantidad
        const cantidadInputs = document.querySelectorAll('.cantidad-input');

        cantidadInputs.forEach(input => {
            // Agregar un evento 'input' a cada campo de entrada de cantidad
            input.addEventListener('input', function() {
                const max = parseInt(this.getAttribute('max')); // Obtener el valor máximo permitido
                const currentValue = parseInt(this.value); // Obtener el valor actual ingresado

                if (currentValue > max) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad excedida',
                        text: `La cantidad máxima permitida a devolver es ${max}.`, // Mostrar la cantidad máxima permitida
                        confirmButtonText: 'Entendido'
                    });
                    this.value = max; // Ajustar el valor al máximo permitido
                    event.preventDefault(); // Prevenir el envío del formulario
                }
            });
        });
    });

    // Llamar al evento 'draw' por primera vez para la página inicial
    table.draw();
});

/*Devolución*/
$(document).ready(function() {
    var table = $('#tablaJs').DataTable();

    // Reasignar eventos después de cada redibujado de la tabla
    table.on('draw', function() {
        // Manejar el evento click en los botones de devolver
        $('.btn-devolver').off('click').on('click', function(event) {
            event.preventDefault(); // Prevenir comportamiento predeterminado

            const row = $(this).closest('tr'); // Fila de la tabla
            const idGeneral_EyC = row.find('input[name^="cantidad"]').attr('name').match(/\d+/)[0]; // Obtener idGeneral_EyC
            const nombre = this.getAttribute('data-nombre'); // Obtener el nombre del atributo data-nombre
            const cantidad = row.find('input[name^="cantidad"]').val(); // Obtener la cantidad
            const folio = $(this).data('folio'); // Obtener el folio del atributo data-folio
            //const cantidad = row.find('input[name^="cantidad"]').val(); // Obtener la cantidad

            // Confirmación de SweetAlert2
            Swal.fire({
                title: '¿Estás seguro?',
                text: `Se devolverá ${nombre} la cantidad "${cantidad}" al almacén, se cambiará el estado a "DISPONIBLE".`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, devolver',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar la solicitud AJAX
                    $.ajax({
                        url: '{{ route('devolver.item') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Agregar token CSRF
                            idGeneral_EyC: idGeneral_EyC,
                            cantidad: cantidad,
                            folio: folio
                        },
                        success: function(response) {
                            Swal.fire('Devuelto', response.success, 'success');
                            table.row(row).remove().draw(); // Eliminar la fila de la tabla
                        },
                        error: function(xhr) {
                            Swal.fire('Error', xhr.responseJSON.error || 'Ocurrió un error.', 'error');
                        }
                    });
                }
            });
        });
    });

    // Llamar al evento 'draw' por primera vez para la página inicial
    table.draw();
});

</script>

@endsection