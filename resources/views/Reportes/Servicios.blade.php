
@extends('adminlte::page')

@section('title', 'Servicio Seleccionado')

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
<h3 align="center">SELECCIÓN DE PRUEBA, NORMA O CODIGO Y FORMATO</h3>
<br>
                <section class="content">
                    <div class="card">
                        <div class="card-body row">
                            <form id="Seleccion" action="{{ route('Seleccion.indexManifiesto') }}" method="post" enctype="multipart/form-data">
                                @csrf 
                                <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Prueba Seleccionada</label>
                                        <select class="form-select" name="Prueba" id="PruebaSelect" required>
                                            <option value="">Seleccione una Prueba</option>
                                            @foreach ($Pruebas->sortBy('Nombre') as $Prueba)
                                                <option value="{{ $Prueba->idPrueba }}" data-image="{{ asset('images/Menu Servicios SVG/' . 
                                                ($Prueba->Nombre == 'PARTÍCULAS MAGNÉTICAS' ? 'PARTICULAS_MAGNETICAS.svg' :
                                                ($Prueba->Nombre == 'LÍQUIDOS PENETRANTES' ? 'LIQUIDOS_PENETRANTES.svg' :
                                                ($Prueba->Nombre == 'CORRIENTES EDDY' ? 'CORRIENTES_EDDY.svg' :
                                                ($Prueba->Nombre == 'TERMOGRAFÍA' ? 'TERMOGRAFIA.svg' :
                                                ($Prueba->Nombre == 'ULTRASONIDO' ? 'ULTRASONIDO.svg' :
                                                ($Prueba->Nombre == 'RADIOGRAFIA' ? 'RADIOGRAFIA.svg' :
                                                ($Prueba->Nombre == 'PMI' ? 'PMI.svg' :
                                                ($Prueba->Nombre == 'PRECALENTAMIENTO' ? 'PRECALENTAMIENTO.svg' :
                                                ($Prueba->Nombre == 'ARREGLO DE FASES' ? 'ARREGLO_FASES.svg' :
                                                ($Prueba->Nombre == 'CARACTERIZACIÓN DE MATERIALES' ? 'CARACTERIZACION_MATERIALES.svg' :
                                                ($Prueba->Nombre == 'DUREZAS' ? 'DUREZAS.svg' :
                                                ($Prueba->Nombre == 'METALOGRAFÍA' ? 'METALOGRAFIA.svg' :
                                                ($Prueba->Nombre == 'ANÁLISIS QUÍMICO' ? 'ANALISIS_QUIMICO.svg' :
                                                ($Prueba->Nombre == 'TOFD' ? 'TOFD.svg' :
                                                ($Prueba->Nombre == 'ACFM' ? 'ACFM.svg' :
                                                ($Prueba->Nombre == 'ONDAS GUIADAS' ? 'ONDAS_GUIADAS.svg' :
                                                ($Prueba->Nombre == 'VISUAL' ? 'Inspección_Visual.svg' :
                                                ($Prueba->Nombre == 'RELEVADO DE ESFUERZOS' ? 'RELEVADO_ESFUERZOS.svg' : 'FOCO_BLANCO.svg'))))))))))))))))))) }}" data-text="{{ $Prueba->Nombre }}" {{ $Prueba->Nombre == $service ? 'selected' : '' }}>
                                                    {{ $Prueba->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('Prueba')
                                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Norma o Código</label>
                                        <select class="form-select" name="NormaCodigo" id="NormaCodigoSelect" required>
                                        </select>
                                        @error('NormaCodigo')
                                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="col-form-label" for="inputSuccess">Formato</label>
                                        <select class="form-select" name="Formato" id="FormatoSelect" required>
                                        </select>
                                        @error('Formato')
                                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-sm-4">
                                        <div class="form-group text-center">
                                            <label class="col-form-label" for="Tipo_Prueba" id="formatoNombre">IMAGEN DE LA PRUEBA SELECCIONADA</label>
                                            <svg class="rounded"
                                                width="100%" height="200" 
                                                role="img" aria-label="IMAGEN DE LA PRUEBA" 
                                                focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                                                <title>IMAGEN DE LA PRUEBA</title>
                                                <rect width="100%" height="100%" fill="#C04040"></rect>
                                                <image id="pruebaImagen" href="{{ asset('images/Menu Servicios SVG/FOCO_BLANCO.svg') }}" x="10%" y="10%" width="80%" height="70%" alt="Imagen de la prueba" />
                                                <text id="pruebaTexto" x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">IMAGEN DE LA PRUEBA</text>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <!--Campo oculto para pasar el nombre al reporte -->
                                <input type="hidden" name="formatoNombrePersonalizado" id="formatoNombrePersonalizado">

                                    <p>
                                    <p>
                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Guardar y Continuar</button>
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
    document.getElementById('Seleccion').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    document.addEventListener('DOMContentLoaded', function () {
    const pruebaSelect = document.getElementById('PruebaSelect');
    const normaSelect = document.getElementById('NormaCodigoSelect');
    const formatoSelect = document.getElementById('FormatoSelect');
    const pruebaImagen = document.getElementById('pruebaImagen');
    const pruebaTexto = document.getElementById('pruebaTexto');
    const pruebaRect = document.querySelector("rect");
    const formatoNombreLabel = document.getElementById('formatoNombre');
    const formatoNombrePersonalizadoInput = document.getElementById('formatoNombrePersonalizado');

    // Lista de pruebas que necesitan el color azul
    const pruebasAzul = [
        "CARACTERIZACIÓN DE MATERIALES",
        "DUREZAS",
        "PMI",
        "METALOGRAFÍA",
        "ANÁLISIS QUÍMICO",
        "RELEVADO DE ESFUERZOS",
    ];

    // Objeto de mapeo para los nombres personalizados
    const nombresPersonalizados = {
        "FOR-PINS-03-02": "INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS",
        "FOR-PINS-04-01": "INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES",
        "FOR-PINS-05-01": "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES NO TUBULARES",
        "FOR-PINS-05-02": "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES",
        "FOR-PINS-06-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO DE ACUERDO CON API RP 2X",
        "FOR-PINS-07-01": "INFORME DE MEDICIÓN DE ESPESORES DE PARED EN LA TUBERÍA Y ELEMENTOS ESTRUCTURALES",
        "FOR-PINS-08-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD",
        "FOR-PINS-09-01": "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR",
        "FOR-PINS-10-01": "INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON API 1104",
        "FOR-PINS-11-01": "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO PARA METAL BASE",
        "FOR-PINS-11-02": "INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA",
        "FOR-PINS-12-01": "REGISTRO DE EXAMINACIÓN AGUDEZA VISUAL Y DIFERENCIACIÓN DEL CONTRASTE DE COLOR",
        "FOR-PINS-13-01": "INFORME DE INSPECCIÓN CON CORRIENTES EDDY",
        "FOR-PINS-14-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO AWS D1.1",
        "FOR-PINS-15-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD",
        "FOR-PINS-16-01": "INFORME DE INSPECCIÓN VISUAL A ELEMENTOS DE TUBERÍAS DE PROCESO",
        "FOR-PINS-17-01": "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA",
        "FOR-PINS-17-01_01": "INSPECCIÓN CON TERMOGRAFÍA INFRARROJA A TABLEROS",
        "FOR-PINS-18-01": "INFORME DE DETECCIÓN DE DISCONTINUIDADES CON CORRIENTES DE EDDY",
        "FOR-PINS-19-01": "INFORME DE INSPECCIÓN CON ACFM",
        "FOR-PINS-20-01": "INFORME DE ANÁLISIS MEDIANTE CORRIENTE EDDY PULSADA (PECT).",
        "FOR-PINS-21-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO API 1104",
        "FOR-PINS-22-01": "INFORME DE  INSPECCIÓN DE TUBERIA POR CORREINTES EDDY.",
        "FOR-PINS-23-01": "INFORME DE INSPECCIÓN CON EL MÉTODO DE ONDAS GUIADAS",
        "FOR-PINS-24-01": "INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES y TOFD", 
        "FOR-PINS-25-01": "INSPECCIÓN VISUAL EN RSP",
        //"FOR-03-PRO-INS-15": "LISTADO DE COMPONENTES", //Integrado en RSP
        // FORMATOS DE INTEGRIDAD MECÁNICA
        "FOR-PIMP-02_B/03": "INFORME DE ENSAYO DE DUREZAS EN METALES BASE HARDNESS TEST REPORT ON BASE METALS",
        "FOR-PIMP-02_B/04": "Informe de Ensayo de Durezas en Soldaduras Test Report on Welding Hardness",
        "FOR-PIMP-03_B/01": "Informe de Análisis Metalográfico Metallographic Analysis Report",
        "FOR-PIMP-04/02": "Informe de Caracterización de Materiales Mediante la Técnica de Espectrometría de Emisión Óptica (OES)",
        "FOR-PIMP-04/03": "Informe de Caracterización de Materiales Mediante la Técnica de Fluorescencia de Rx (XRF)",
        "FOR-PIMP-05/01": "Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES)",
        "FOR-PIMP-05_B/01": "Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES)/Chemical Analysis Report Using the Optical Emission Spectrometry Technique (OES",
        "FOR-PIMP-06_B/01": "Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)/Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF",
        "FOR-PIMP-07_B/01": "INFORME DE RELEVADO DE ESFUERZOS RELIEVED OF STRESS INFORM"
            };

    pruebaSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const imageUrl = selectedOption.getAttribute('data-image');
        const textContent = selectedOption.getAttribute('data-text');
        const pruebaNombre = selectedOption.dataset.text;

        // Cambia el color según la prueba seleccionada
        if (pruebasAzul.includes(pruebaNombre)) {
            pruebaRect.setAttribute("fill", "#0070C0"); // Azul
        } else {
            pruebaRect.setAttribute("fill", "#C04040"); // Color original
        }

        // Actualiza la imagen dentro del SVG
        if (imageUrl) {
            pruebaImagen.setAttribute('href', imageUrl);
        } else {
            pruebaImagen.setAttribute('href', '{{ asset('images/Menu Servicios SVG/FOCO_BLANCO.svg') }}');
        }

        // Actualiza el texto dentro del SVG
        if (textContent) {
            pruebaTexto.textContent = textContent;
        } else {
            pruebaTexto.textContent = 'IMAGEN DE LA PRUEBA';
        }
    });

    pruebaSelect.addEventListener('change', function () {
        const pruebaId = this.value;
        // Limpia las opciones del segundo select
        normaSelect.innerHTML = '<option value="">Seleccione una Norma</option>';
        formatoSelect.innerHTML = '<option value="">Seleccione un Formato</option>';

        if (pruebaId) {
            fetch(`/Obtener/normas/${pruebaId}`)
                .then(response => response.json()) //Espera la respuesta del servidor y la convierte en un objeto JSON.
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
    });

    normaSelect.addEventListener('change', function () {
        //const pruebaId = pruebaSelect.value; NormaCodigoSelect
        const pruebaId = NormaCodigoSelect.value;
        // Limpia las opciones del tercer select
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
                            option.setAttribute('data-nombre-personalizado', nombresPersonalizados[formato.Nombre] || formato.Nombre); // Agregar nombre personalizado como atributo de datos
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
            formatoNombrePersonalizadoInput.value = nombrePersonalizado || selectedOption.textContent; // Actualiza el campo oculto
        } else {
            formatoNombreLabel.textContent = 'IMAGEN DE LA PRUEBA SELECCIONADA';
            formatoNombrePersonalizadoInput.value = ''; // Limpia el campo oculto
        }
    });

    // Dispara el evento 'change' en el select 'PruebaSelect' al cargar la página
    if (pruebaSelect.value) {
        pruebaSelect.dispatchEvent(new Event('change'));
    }
});
    </script>
@endsection
