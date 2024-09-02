
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center"> Formulario Edición de Clientes</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Clientes</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->

                <div class="card-body">
                    <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <form id="ClienteForm" method="post" enctype="multipart/form-data" action="{{ route('editClientes.update', ['id' => $id]) }}">
                                    @csrf
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Cliente</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $clientes->Cliente }}" name="Cliente" placeholder="Ejemplo: PROTEXA" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">RFC</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $clientes->RFC }}" name="RFC" placeholder="Ejemplo: PROP56512458" style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Teléfono</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $clientes->Telefono }}" name="Telefono" placeholder="Ejemplo: 81 8399 2828">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Correo</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $clientes->Correo }}" name="Correo" placeholder="Ejemplo: hola@protexa.mx">
                                                </div>
                                            </div>

                                            <div class="container">
                                                <div class="d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-info bg-success">Guardar</button>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                        </div><!--"class="tab-pane active" id="tab_1"-->
                    </div><!-- /.tab-content -->
                </div><!-- class="card-body" -->
                        <!-- Agrega más paneles de tabs según sea necesario -->
            </div><!-- /.card -->       
        </div><!-- class="col-sm-12" -->
    </div><!-- class="row justify-content-center" -->
</div><!--class="container" -->     
@stop


@section('js')
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>

/*Prevenir el Enter */
document.getElementById('ClienteForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

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
    
</script>
@endsection