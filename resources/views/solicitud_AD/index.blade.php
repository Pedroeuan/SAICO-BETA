@extends('adminlte::page')

@section('title', 'Solicitudes AD')

@section('content_header')
<br>  
<br>
<br>
<div class="d-flex justify-content-between align-items-center mb-3">
    <!-- Título a la izquierda -->
    <h1 class="text-primary m-0">Listado de Solicitudes</h1>
    @if($rol == 'Super Administrador' || $rol == 'Administrador')
        <!-- Contenedor derecho con select + botón -->
        <div class="d-flex align-items-center gap-2">
            <select class="form-control select2 @error('estatus') is-invalid @enderror" 
                    style="width: 200px;" 
                    name="estatus">
                <option selected>Selecciona un estatus</option>
                <option value="ALTA" @if($Estatus == 'ALTA') selected="selected" @endif>DISPONIBLE</option>
                <option value="ALTA2" @if($Estatus == 'ALTA2') selected="selected" @endif>NO DISPONIBLE</option>
                <option value="ALTA3" @if($Estatus == 'ALTA3') selected="selected" @endif>EN REUNIÓN</option>
                <option value="ALTA4" @if($Estatus == 'ALTA4') selected="selected" @endif>EN LLAMADA</option>
                <option value="ALTA5" @if($Estatus == 'ALTA5') selected="selected" @endif>ALMUERZO</option>
            </select>

            <button type="button" class="btn btn-info">
                <i class="fas fa-sync-alt"></i> Actualizar estado
            </button>
        </div>
        @endif
</div>

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
                    <th>Editar</th>
                    <th>Eliminar</th>
                    @else
                    <th>Seleccionar</th>
                    <th>Solcitud de:</th>
                    <th>Fecha</th>
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
                                {{ 
                                $solicitud->solicitud_ad->estatus == 'PASAR' ? 'bg-success' : ($solicitud->solicitud_ad->estatus == 'PENDIENTE' ? 'bg-warning' : 'bg-danger') }}"
                                data-id-solicitud="{{ $solicitud->idsolicitud_AD }}">
                                {{ $solicitud->solicitud_ad->estatus }}
                            </span>
                        </td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->Tema }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->comentario }}</td>
                        <td class="text-center">
                            {{-- Botón Editar --}}
                            <a href="{{ route('ADsolicitud.edit', $solicitud->idsolicitud_AD) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                        </td>
                        <td>
                            {{-- Botón Eliminar --}}
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar" data-id="{{ $solicitud->idsolicitud_AD }}"><i class="fas fa-trash-alt"></i> Eliminar</button>
                        </td>
                    @else
                        <td class="text-center"><input type="checkbox" name="usuarios[]" value="{{ $solicitud->users->id }}"{{ in_array($solicitud->users->id, $usuarios) ? 'checked' : '' }}></td>
                        <td>{{ $solicitud->users->name }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->fecha }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->Tema }}</td>
                        <td class="text-center">{{ $solicitud->solicitud_ad->comentario }}</td>
                        <td class="text-center">

                            <select class="form-control select2 @error('estatus') is-invalid @enderror" style="width: 100%;" name="estatus">
                                @if($solicitud->solicitud_ad->estatus == 'PENDIENTE')
                                <option selected>Selecciona un estatus</option>
                                @endif
                                <option value="PASAR" @if($solicitud->solicitud_ad->estatus == 'PASAR') selected="selected" @endif>PASAR</option>
                                <option value="NO PASAR" @if($solicitud->solicitud_ad->estatus == 'NO PASAR') selected="selected" @endif>NO PASAR</option>
                                <option value="SIGUIENTE" @if($solicitud->solicitud_ad->estatus == 'SIGUIENTE') selected="selected" @endif>SIGUIENTE</option>
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
        @if($rol == 'Super Administrador' || $rol == 'Administrador')
        <div class="text-center mt-3">
                <button type="button" id="btn-actualizar-seleccionados" class="btn btn-success">
                        <i class="fas fa-sync-alt"></i> Actualizar solicitudes seleccionadas
                </button>
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
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
    //console.log("🟢 ID capturado:", idUsuario);
    //console.log("🟢 Estatus capturado:", estatus);
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

document.addEventListener('DOMContentLoaded', function () {
    let estatusActuales = {};

    // Guardar los estatus iniciales
    document.querySelectorAll('[data-id-solicitud]').forEach(el => {
        const id = el.getAttribute('data-id-solicitud');
        const estatus = el.textContent.trim();
        estatusActuales[id] = estatus;
    });

    async function verificarEstatus() {
        try {
            const response = await fetch("{{ route('estatus.solicitudes') }}");
            const data = await response.json();

            data.forEach(item => {
                const elemento = document.querySelector(`[data-id-solicitud="${item.id}"]`);
                if (!elemento) return;

                const estatusNuevo = item.estatus.trim().toUpperCase();
                const estatusAnterior = (estatusActuales[item.id] || '').trim().toUpperCase();

                if (estatusNuevo !== estatusAnterior) {
                    //console.log(`Cambio detectado en ID ${item.id}: ${estatusAnterior} → ${estatusNuevo}`);
                    elemento.textContent = estatusNuevo.charAt(0) + estatusNuevo.slice(1).toLowerCase();
                    elemento.className = 'badge ' + obtenerClaseBadge(estatusNuevo);
                    estatusActuales[item.id] = estatusNuevo;

                    mostrarToast(`El estatus de una solicitud cambió a "${estatusNuevo}"`);
                }
            });
        } catch (error) {
            console.error('Error verificando estatus:', error);
        }
    }

    function obtenerClaseBadge(estatus) {
        switch (estatus.toUpperCase()) {
            case 'APROBADO': return 'bg-success';
            case 'PENDIENTE': return 'bg-warning';
            case 'RECHAZADO': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }

    // Función para mostrar un “toast” (mensaje flotante)
    function mostrarToast(mensaje) {
        const toast = document.createElement('div');
        toast.textContent = mensaje;
        toast.className = 'toast-alert';
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    // Estilos CSS del toast
    const style = document.createElement('style');
    style.innerHTML = `
        .toast-alert {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #343a40;
            color: #fff;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease;
            z-index: 9999;
        }
        .toast-alert.show {
            opacity: 1;
            transform: translateY(0);
        }
    `;
    document.head.appendChild(style);

    // Verificar cada 20 segundos
    setInterval(verificarEstatus, 20000);
});

</script>
@stop