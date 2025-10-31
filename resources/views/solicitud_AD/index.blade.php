@extends('adminlte::page')

@section('title', 'Solicitudes AD')

@section('content_header')
<h1 class="text-primary">Listado de Solicitudes AD</h1>
@stop

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title">Solicitudes Registradas</h3>
        @if($rol != 'Super Administrador' && $rol != 'Administrador')
        <a href="{{ route('ADsolicitud.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nueva Solicitud
        </a>
        @endif
    </div>
    <div class="card-body">
        <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
            <thead class="text-center">
                <tr>
                    @if($rol != 'Super Administrador' && $rol != 'Administrador')
                    <th>Tu Solcitud</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Tema</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                    @else
                    <th>Seleccionar</th>
                    <th>Solcitud de:</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Tema</th>
                    <th>Comentario</th>
                    <th>Estatus</th>
                    <th>Actualizar</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                <tr>
                    @if($rol != 'Super Administrador' && $rol != 'Administrador')
                        <td class="text-center">{{ $solicitud->users->name }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->fecha }}</td>
                        <td class="text-center">
                            <span class="badge 
                                {{ $solicitud->solicitud_ad->estatus == 'Aprobado' ? 'bg-success' : 
                                ($solicitud->solicitud_ad->estatus == 'Pendiente' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $solicitud->solicitud_ad->estatus }}
                            </span>
                        </td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->Tema }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->comentario }}</td>
                        <td class="text-center">
                            {{-- Botón Editar --}}
                            <a href="{{ route('ADsolicitud.edit', $solicitud->idsolicitud_AD) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                            {{-- Botón Eliminar --}}
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar" data-id="{{ $solicitud->idsolicitud_AD }}"><i class="fas fa-trash-alt"></i> Eliminar</button>
                        </td>
                    @else
                        <td class="text-center"><input type="checkbox" name="usuarios[]" value="{{ $solicitud->users->id }}"{{ in_array($solicitud->users->id, $usuarios) ? 'checked' : '' }}></td>
                        <td>{{ $solicitud->users->name }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->fecha }}</td>
                        <td class="text-center">
                            <span class="badge 
                                {{ $solicitud->solicitud_ad->estatus == 'Aprobado' ? 'bg-success' : 
                                ($solicitud->solicitud_ad->estatus == 'Pendiente' ? 'bg-warning' : 'bg-danger') }}">
                                {{ $solicitud->solicitud_ad->estatus }}
                            </span>
                        </td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->Tema }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->comentario }}</td>
                        <td class="text-center">

                            <select class="form-control select2 @error('estatus') is-invalid @enderror" style="width: 100%;" name="estatus">
                                <option disabled>Selecciona un estatus</option>
                                <option   option value="PASAR" {{ $solicitud->solicitud_ad->estatus == 'PASAR' ? 'selected' : '' }}>PASAR</option>
                                <option value="SIGUIENTE" {{ $solicitud->solicitud_ad->estatus == 'SIGUIENTE' ? 'selected' : '' }}>SIGUIENTE</option>
                                <option value="NO PASAR" {{ $solicitud->solicitud_ad->estatus == 'NO PASAR' ? 'selected' : '' }}>NO PASAR</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <!--<a href="#" class="btn btn-info btn-actualizar" role="button" actualizar-id="{{ $solicitud->idsolicitud_AD }}"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>-->
                            <button type="button" class="btn btn-info btn-actualizar" actualizar-id="{{ $solicitud->idsolicitud_AD }}"><i class="fas fa-undo-alt" aria-hidden="true"></i></button>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
         <div class="text-center mt-3">
                <button type="button" id="btn-actualizar-seleccionados" class="btn btn-success">
                        <i class="fas fa-sync-alt"></i> Actualizar seleccionados
                </button>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /*Boton Eliminar*/
        $(document).on("click", ".btn-eliminar", function() {
        var idUsuario = $(this).attr("data-id");
        //console.log("🟢 ID capturado:", idUsuario);
        Swal.fire({
            title: "¿Deseas eliminar este usuario?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ADsolicitud/destroy/' + idUsuario,
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

    /*Boton Actualizar*/
$(document).on("click", ".btn-actualizar", function() {
    var idUsuario = $(this).attr("actualizar-id");
    //var idUsuario = $(this).data("id"); // obtiene el data-id
    //var estatus = $(this).closest('tr').find('select.estatus').val(); // obtiene el select de la fila
    var estatus = $(this).closest('tr').find('select[name="estatus"]').val(); // obtiene el valor del select
    console.log("🟢 ID capturado:", idUsuario);
    console.log("🟢 Estatus capturado:", estatus);
    if(!estatus){
        Swal.fire("Error", "Debes seleccionar un estatus.", "warning");
        return;
    }

    Swal.fire({
        title: "¿Deseas actualizar esta solicitud?",
        showDenyButton: true,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if(result.isConfirmed){
            $.ajax({
                url: '/ADsolicitud/actualizar/' + idUsuario,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { estatus: estatus },
                success: function(response){
                    if(response.success){
                        Swal.fire("Actualizado!", response.message, "success").then(() => location.reload());
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function(xhr){
                    Swal.fire("Error!", "No se pudo actualizar. (" + xhr.status + ")", "error");
                }
            });
        } else if(result.isDenied){
            Swal.fire("Cancelado", "", "info");
        }
    });
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
@stop