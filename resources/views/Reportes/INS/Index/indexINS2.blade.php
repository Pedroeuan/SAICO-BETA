
@extends('adminlte::page')

@section('title', 'Prueba')

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
        <h3 align="center">Reportes del Contrato: {{$contratoSeleccionado}}  y Proyecto: {{$Proyecto}} </h3>

            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Nombre del Proyecto</th>
                        <th>No. Reporte</th>
                        <th>Fecha</th>
                        <th>PDF GENERADO</th>
                        {{--<th>DESCARGAR PDF</th>--}}
                        <th>PDF FIRMADO</th>
                        <th>Editar</th>
                        <th>Siguiente Reporte</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportesEncontrados as $reporte)
                        @php
                            $detalles = json_decode($reporte->Detalles_Generales, true) ?? [];
                            $Reporte_Firmado = $detalles['Reporte_Firmado'] ?? '';
                            $ProyectoReporte = $detalles['Proyecto'] ?? $detalles['Identificacion'] ?? '';
                            $idSolicitud = $detalles['idSolicitud'] ?? '';
                        @endphp
                        <tr>
                            <td>{{ $detalles['Contrato'] ?? '' }}</td>
                            <td>{{ $ProyectoReporte }}</td>
                            <td>{{ $detalles['No_Reporte'] ?? '' }}</td>
                            <td>{{ $detalles['Fecha'] ?? '' }}</td>
                            <td>
                                @if(($formatosPorReporte[$reporte->idReportes] ?? '') === 'FOR-PIMP-04/03')
                                    {{-- FOR-PIMP-04/03 dispone de dos juegos completos de plantillas. --}}
                                    <button
                                        type="button"
                                        class="btn btn-primary btnPdfIdioma0403"
                                        data-pdf-url="{{ route('Obtener.RutaPDF', ['id' => $reporte->idReportes]) }}"
                                        aria-label="Seleccionar idioma del PDF"
                                    >
                                        <i class="far fa-file-pdf"></i>
                                    </button>
                                @else
                                    <a class="btn btn-primary" href="{{ route('Obtener.RutaPDF', ['id' => $reporte->idReportes]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                @endif
                            </td>
                            <td>
                                @if ($Reporte_Firmado == '')
                                    <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                        <i class="far fa-file-pdf"></i>
                                    </span>
                                    @else
                                    <a href="{{ asset($Reporte_Firmado) }}" 
                                        class="btn btn-primary" target="_blank">
                                            <i class="far fa-file-pdf"></i>
                                    </a>
                                @endif
                            </td> 
                            <td>
                                <a href="{{ route('Editar.Reporte', ['id' => $reporte->idReportes]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                            </td>
                            <td>
                                {{-- <a href=" route('Next.Reporte', ['id' => $reporte->idReportes])  }}"  class="btn btn-success btnSiguienteReporte" role="button"><i class="fas ffas fa-file-export"></i></a> --}}
                                @php
                                    // Recupera la serie asociada para informar el avance del consecutivo.
                                    $serieFila = $seriesPorReporte[$reporte->idReportes] ?? null;
                                @endphp
                                <button type="button"
                                    class="btn btn-success btnSiguienteReporte"
                                    idReporte="{{$reporte->idReportes}}"
                                    idSolicitud="{{ $idSolicitud }}"
                                    data-next-url="{{ route('Next.Reporte', ['id' => $reporte->idReportes]) }}"
                                    data-formato="{{ $formatosPorReporte[$reporte->idReportes] ?? '' }}"
                                    data-serie-orden="{{ $serieFila->numero_orden ?? '' }}"
                                    data-serie-total="{{ $serieFila->cantidad_planificada ?? '' }}">
                                    <i class="fas ffas fa-file-export" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btnEliminarReportes" idReporte="{{$reporte->idReportes}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>
            <p>
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
@php
    $pruebas = \App\Models\Prueba\prueba::with('norma_codigo.formato')->get();
@endphp

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'No fue posible crear el consecutivo',
    text: @json(session('error'))
});
@endif

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

// Solicita el idioma solo para FOR-PIMP-04/03 y conserva la apertura en otra pestana.
$(document).on('click', '.btnPdfIdioma0403', function () {
    const urlBase = $(this).data('pdf-url');

    Swal.fire({
        title: 'Idioma del reporte',
        html: 'Seleccione el idioma en el que desea generar y visualizar el PDF.',
        icon: 'question',
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Español',
        denyButtonText: 'Inglés',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-primary me-2',
            denyButton: 'btn btn-success me-2',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    }).then(function (resultado) {
        if (!resultado.isConfirmed && !resultado.isDenied) {
            return;
        }

        const idioma = resultado.isConfirmed ? 'es' : 'en';
        const urlPdf = new URL(urlBase, window.location.origin);
        urlPdf.searchParams.set('idioma', idioma);

        // Si el navegador bloquea la nueva pestana, se abre en la actual como respaldo.
        const ventanaPdf = window.open(urlPdf.toString(), '_blank');
        if (!ventanaPdf) {
            window.location.href = urlPdf.toString();
        }
    });
});


$(document).on("click", ".btnEliminarReportes", function() {
    var idReporte = $(this).attr("idReporte");
    Swal.fire({
        title: "¿Seguro de eliminar este elemento?",
        text: "¡Se eliminará el reporte!",
        icon: 'error',
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/Eliminar/Reporte/Tabla/' + idReporte,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Eliminado!",
                            text: response.message,
                            icon: "success"
                        }).then(() => {
                            // Recargar la página después de cerrar la alerta
                            location.reload();
                        });
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "No se pudo eliminar el reporte.", "error");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});

$(document).on("click", ".btnSiguienteReporte", function() {
    var idReporte = $(this).attr("idReporte");
    // La URL pertenece al botón consecutivo y se envía por POST para crear una sola vez.
    const nextReporteUrl = $(this).data('next-url');
    const formato = $(this).data('formato');
    const ordenSerie = $(this).data('serie-orden');
    const totalSerie = $(this).data('serie-total');
    let idReporteSeleccionado = idReporte;
        const detalleSerie = formato === 'FOR-PIMP-06_B/01' && ordenSerie && totalSerie
            ? `<br><small class="text-muted">Serie actual: reporte ${ordenSerie} de ${totalSerie}. Los resultados XRF y las fotograf&iacute;as iniciar&aacute;n vac&iacute;os.</small>`
            : '';
        Swal.fire({
            title: 'Siguiente Reporte',
            html: '¿El reporte es <span style="color:#0d6efd; font-size:16px;">CONSECUTIVO</span>? o ¿desea crear un <span style="color:#198754; font-size:16px;"> NUEVO REPORTE?</span>',
            didOpen: () => {
                if (detalleSerie) {
                    const contenido = Swal.getHtmlContainer();
                    contenido.insertAdjacentHTML('beforeend', detalleSerie);
                }
            },
            icon: 'question',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Consecutivo',
            denyButtonText: 'Nuevo Reporte',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-primary me-2',
                denyButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        }).then((result) => {
        if (result.isConfirmed) {
            // POST evita que actualizar o precargar la URL cree otro consecutivo.
            const formulario = document.createElement('form');
            formulario.method = 'POST';
            formulario.action = nextReporteUrl;
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = @json(csrf_token());
            formulario.appendChild(token);
            document.body.appendChild(formulario);
            formulario.submit();
        } else if (result.isDenied) {
            Swal.fire({
                title: 'Nuevo Reporte',
                html: `
                    <div class="text-start">
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-info"></i> Importante</h5>
                            <p>Los datos generales del reporte serán llenados automáticamente.</p>
                            <p class="mb-3">Completa la selección del servicio para crear un nuevo reporte.</p>
                        </div>
                        <form id="formModalServicios" action="{{ route('Nuevo.Reporte.DesdeModal', ['id' => 0]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Prueba Seleccionada</label>
                                    <select class="form-select" name="Prueba" id="PruebaSelectModal" required>
                                        <option value="">Seleccione una Prueba</option>
                                        @foreach($pruebas->sortBy('Nombre') as $prueba)
                                            <option value="{{ $prueba->idPrueba }}" data-text="{{ $prueba->Nombre }}">{{ $prueba->Nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" id="lblNormaCodigo">Norma o Código</label>
                                    <select class="form-select" name="NormaCodigo" id="NormaCodigoSelectModal" required></select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Formato</label>
                                    <select class="form-select" name="Formato" id="FormatoSelectModal" required></select>
                                </div>
                                <div class="col-12 text-center">
                                    <label class="form-label" id="formatoNombreModal">IMAGEN DE LA PRUEBA SELECCIONADA</label>
                                    <svg class="rounded" width="100%" height="180" role="img" aria-label="IMAGEN DE LA PRUEBA" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                                        <title>IMAGEN DE LA PRUEBA</title>
                                        <rect id="pruebaRectModal" width="100%" height="100%" fill="#C04040"></rect>
                                        <image id="pruebaImagenModal" href="{{ asset('images/Menu Servicios SVG/FOCO_BLANCO.svg') }}" x="10%" y="10%" width="80%" height="70%" alt="Imagen de la prueba" />
                                        <text id="pruebaTextoModal" x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">IMAGEN DE LA PRUEBA</text>
                                    </svg>
                                </div>
                                <input type="hidden" name="formatoNombrePersonalizado" id="formatoNombrePersonalizadoModal">
                                <input type="hidden" name="idReporteOriginal" id="idReporteOriginalModal">
                            </div>
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-success">Guardar y Continuar</button>
                            </div>
                        </form>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Cerrar',
                width: '800px',
                didOpen: () => {
                    const pruebaSelect = document.getElementById('PruebaSelectModal');
                    const normaSelect = document.getElementById('NormaCodigoSelectModal');
                    const formatoSelect = document.getElementById('FormatoSelectModal');
                    const pruebaImagen = document.getElementById('pruebaImagenModal');
                    const pruebaTexto = document.getElementById('pruebaTextoModal');
                    const pruebaRect = document.getElementById('pruebaRectModal');
                    const formatoNombreLabel = document.getElementById('formatoNombreModal');
                    const formatoNombrePersonalizadoInput = document.getElementById('formatoNombrePersonalizadoModal');
                    const idReporteOriginalInput = document.getElementById('idReporteOriginalModal');
                    const modalForm = document.getElementById('formModalServicios');
                    const lblNormaCodigo = document.getElementById('lblNormaCodigo');

                    const pruebasAzul = [
                        'CARACTERIZACIÓN DE MATERIALES',
                        'DUREZAS',
                        'PMI',
                        'METALOGRAFÍA',
                        'ANÁLISIS QUÍMICO',
                        'TRATAMIENTO TÉRMICO DE PWHT'
                    ];

                    const imagenesPrueba = {
                        'PARTÍCULAS MAGNÉTICAS': '{{ asset('images/Menu Servicios SVG/PARTICULAS_MAGNETICAS.svg') }}',
                        'LÍQUIDOS PENETRANTES': '{{ asset('images/Menu Servicios SVG/LIQUIDOS_PENETRANTES.svg') }}',
                        'CORRIENTES EDDY': '{{ asset('images/Menu Servicios SVG/CORRIENTES_EDDY.svg') }}',
                        'TERMOGRAFÍA': '{{ asset('images/Menu Servicios SVG/TERMOGRAFIA.svg') }}',
                        'ULTRASONIDO': '{{ asset('images/Menu Servicios SVG/ULTRASONIDO.svg') }}',
                        'RADIOGRAFIA': '{{ asset('images/Menu Servicios SVG/RADIOGRAFIA.svg') }}',
                        'PMI': '{{ asset('images/Menu Servicios SVG/PMI.svg') }}',
                        'PRECALENTAMIENTO': '{{ asset('images/Menu Servicios SVG/PRECALENTAMIENTO.svg') }}',
                        'ARREGLO DE FASES': '{{ asset('images/Menu Servicios SVG/ARREGLO_FASES.svg') }}',
                        'CARACTERIZACIÓN DE MATERIALES': '{{ asset('images/Menu Servicios SVG/CARACTERIZACION_MATERIALES.svg') }}',
                        'DUREZAS': '{{ asset('images/Menu Servicios SVG/DUREZAS.svg') }}',
                        'METALOGRAFÍA': '{{ asset('images/Menu Servicios SVG/METALOGRAFIA.svg') }}',
                        'ANÁLISIS QUÍMICO': '{{ asset('images/Menu Servicios SVG/ANALISIS_QUIMICO.svg') }}',
                        'TOFD': '{{ asset('images/Menu Servicios SVG/TOFD.svg') }}',
                        'ACFM': '{{ asset('images/Menu Servicios SVG/ACFM.svg') }}',
                        'ONDAS GUIADAS': '{{ asset('images/Menu Servicios SVG/ONDAS_GUIADAS.svg') }}',
                        'VISUAL': '{{ asset('images/Menu Servicios SVG/Inspección_Visual.svg') }}',
                        'TRATAMIENTO TÉRMICO DE PWHT': '{{ asset('images/Menu Servicios SVG/RELEVADO_ESFUERZOS.svg') }}'
                    };

                    const nombresPersonalizados = {
                        'FOR-PINS-03-02': 'INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS',
                        'FOR-PINS-04-01': 'INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES',
                        'FOR-PINS-05-01': 'INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES NO TUBULARES',
                        'FOR-PINS-05-02': 'INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES',
                        'FOR-PINS-06-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO DE ACUERDO CON API RP 2X',
                        'FOR-PINS-07-01': 'INFORME DE MEDICIÓN DE ESPESORES DE PARED EN LA TUBERÍA Y ELEMENTOS ESTRUCTURALES',
                        'FOR-PINS-08-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD',
                        'FOR-PINS-09-01': 'INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR',
                        'FOR-PINS-10-01': 'INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON API 1104',
                        'FOR-PINS-11-01': 'INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO PARA METAL BASE',
                        'FOR-PINS-11-02': 'INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA',
                        'FOR-PINS-12-01': 'REGISTRO DE EXAMINACIÓN AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR',
                        'FOR-PINS-13-01': 'INFORME DE INSPECCIÓN CON CORRIENTES EDDY',
                        'FOR-PINS-14-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO AWS D1.1',
                        'FOR-PINS-15-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD',
                        'FOR-PINS-16-01': 'INFORME DE INSPECCIÓN VISUAL A ELEMENTOS DE TUBERÍAS DE PROCESO',
                        'FOR-PINS-17-01': 'INSPECCIÓN CON TERMOGRAFÍA INFRARROJA',
                        'FOR-PINS-17-01_01': 'INSPECCIÓN CON TERMOGRAFÍA INFRARROJA A TABLEROS',
                        'FOR-PINS-18-01': 'INFORME DE DETECCIÓN DE DISCONTINUIDADES CON CORRIENTES DE EDDY',
                        'FOR-PINS-19-01': 'INFORME DE INSPECCIÓN CON ACFM',
                        'FOR-PINS-20-01': 'INFORME DE ANÁLISIS MEDIANTE CORRIENTE EDDY PULSADA (PECT).',
                        'FOR-PINS-21-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO API 1104',
                        'FOR-PINS-22-01': 'INFORME DE  INSPECCIÓN DE TUBERIA POR CORREINTES EDDY.',
                        'FOR-PINS-23-01': 'INFORME DE INSPECCIÓN CON EL MÉTODO DE ONDAS GUIADAS',
                        'FOR-PINS-24-01': 'INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES y TOFD',
                        'FOR-PINS-25-01': 'INSPECCIÓN VISUAL EN RSP'
                    };

                    idReporteOriginalInput.value = idReporteSeleccionado;
                    modalForm.action = '/Nuevo/Reporte/DesdeModal/' + idReporteSeleccionado;

                    pruebaSelect.addEventListener('change', function () {
                        const pruebaId = this.value;
                        const selectedText = this.options[this.selectedIndex]?.text || '';
                        normaSelect.innerHTML = '<option value="">Seleccione una Norma</option>';
                        formatoSelect.innerHTML = '<option value="">Seleccione un Formato</option>';

                        if (pruebaId) {
                            fetch(`/Obtener/normas/${pruebaId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        data.forEach(norma => {
                                            const option = document.createElement('option');
                                            option.value = norma.idNorma_codigo;
                                            option.textContent = norma.Nombre;
                                            normaSelect.appendChild(option);
                                        });
                                    } else {
                                        normaSelect.innerHTML = '<option value="">No hay normas disponibles</option>';
                                    }
                                })
                                .catch(error => console.error('Error al obtener las normas:', error));
                        }

                        const imagenSeleccionada = imagenesPrueba[selectedText] || '{{ asset('images/Menu Servicios SVG/FOCO_BLANCO.svg') }}';
                        pruebaImagen.setAttribute('href', imagenSeleccionada);
                        pruebaTexto.textContent = selectedText || 'IMAGEN DE LA PRUEBA';

                        if (pruebasAzul.includes(selectedText)) {
                            pruebaRect.setAttribute('fill', '#0070C0');
                            lblNormaCodigo.textContent = "Procedimiento";
                        } else {
                            pruebaRect.setAttribute('fill', '#C04040');
                            lblNormaCodigo.textContent = "Norma o Código";
                        }
                    });

                    normaSelect.addEventListener('change', function () {
                        const pruebaId = this.value;
                        formatoSelect.innerHTML = '<option value="">Seleccione un Formato</option>';

                        if (pruebaId) {
                            fetch(`/Obtener/formatos/${pruebaId}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        data.forEach(formato => {
                                            const option = document.createElement('option');
                                            option.value = formato.idFormato;
                                            option.textContent = formato.Nombre;
                                            option.setAttribute('data-nombre-personalizado', nombresPersonalizados[formato.Nombre] || formato.Nombre);
                                            formatoSelect.appendChild(option);
                                        });
                                    } else {
                                        formatoSelect.innerHTML = '<option value="">No hay formatos disponibles</option>';
                                    }
                                })
                                .catch(error => console.error('Error al obtener los formatos:', error));
                        }
                    });

                    formatoSelect.addEventListener('change', function () {
                        const selectedOption = formatoSelect.options[formatoSelect.selectedIndex];
                        if (selectedOption) {
                            const nombrePersonalizado = selectedOption.getAttribute('data-nombre-personalizado');
                            formatoNombreLabel.textContent = nombrePersonalizado || selectedOption.textContent;
                            formatoNombrePersonalizadoInput.value = nombrePersonalizado || selectedOption.textContent;
                        } else {
                            formatoNombreLabel.textContent = 'IMAGEN DE LA PRUEBA SELECCIONADA';
                            formatoNombrePersonalizadoInput.value = '';
                        }
                    });

                    if (pruebaSelect.value) {
                        pruebaSelect.dispatchEvent(new Event('change'));
                    }
                }
            });
        }
    });
});

/*document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btnSiguienteReporte').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Evita la navegación inmediata
            let url = this.href; // Guarda la URL del enlace

            // Deshabilitar el botón inmediatamente
            this.setAttribute('disabled', 'true');
            this.style.pointerEvents = 'none'; // Evita más clics en el botón
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; // Muestra el spinner de carga

            // Redirigir de inmediato
            window.location.href = url;
        });
    });
});*/
</script>

@endsection
