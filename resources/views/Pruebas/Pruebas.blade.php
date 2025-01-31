
@extends('adminlte::page')

@section('title', 'Servicios/Pruebas')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
    .expansive-effect {
        transition: transform 0.3s ease;
    }

    .expansive-effect:hover {
        transform: scale(1.1);
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<br>  
<br>
<br>
    <div class="row" data-masonry="{&quot;percentPosition&quot;: true }" style="position: relative; height: 690px;">

        <!-- 1ra columna -->
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 0%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ANÁLISIS QUÍMICO" 
                    data-name="ANÁLISIS QUÍMICO"
                    data-img="{{ asset('images/MenuServicios/ANALISIS_QUIMICO.png') }}"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    
                    <title>ANÁLISIS QUÍMICO</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ANALISIS_QUIMICO.svg') }}" 
                        x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ANÁLISIS QUÍMICO</text>
                </svg>
                
                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="CORRIENTES EDDY"
                    data-name="CORRIENTES EDDY"
                    data-img="{{ asset('images/MenuServicios/CORRIENTES_EDDY.png') }}"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>CORRIENTES EDDY</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/CORRIENTES_EDDY.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">CORRIENTES EDDY</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="METALOGRAFÍA"
                    data-name="METALOGRAFÍA"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>METALOGRAFÍA</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/METALOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">METALOGRAFÍA</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ULTRASONIDO" 
                    data-name="ULTRASONIDO"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>ULTRASONIDO</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ULTRASONIDO.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ULTRASONIDO</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="TERMOGRAFÍA" 
                    data-name="TERMOGRAFÍA"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>TERMOGRAFÍA</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/TERMOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">TERMOGRAFÍA</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ACFM" 
                    data-name="ACFM"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>ACFM</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ACFM.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ACFM</text>
                </svg>

        </div>

        <!-- 2da columna -->
        
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 33.3333%; top: 0px;">         
            
                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ARREGLO DE FASES" 
                    data-name="ARREGLO DE FASES"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>ARREGLO DE FASES</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ARREGLO_FASES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ARREGLO DE FASES</text>
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="DUREZAS" 
                    data-name="DUREZAS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">


                    <title>DUREZAS</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/DUREZAS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">DUREZAS</text>
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="PARTÍCULAS MAGNÉTICAS" 
                    data-name="PARTÍCULAS MAGNÉTICAS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>PARTÍCULAS MAGNÉTICAS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/PARTICULAS_MAGNETICAS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">PARTÍCULAS MAGNÉTICAS</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="RADIOGRAFIA" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>RADIOGRAFIA</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/RADIOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">RADIOGRAFIA</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ONDAS GUIADAS" 
                    data-name="ONDAS GUIADAS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>ONDAS GUIADAS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ONDAS GUIADAS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ONDAS GUIADAS</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="OTROS" 
                    data-name="OTROS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>OTROS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/OTROS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">OTROS</text>
                </svg>
        </div>

            <!-- 3ra columna -->
            
            <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 66.6667%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="CARACTERIZACIÓN DE MATERIALES" 
                    data-name="CARACTERIZACIÓN DE MATERIALES"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>CARACTERIZACIÓN DE MATERIALES</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/CARACTERIZACION_MATERIALES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">CARACTERIZACIÓN DE MATERIALES</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="LÍQUIDOS PENETRANTES" 
                    data-name="LÍQUIDOS PENETRANTES"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>LÍQUIDOS PENETRANTES</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/LIQUIDOS_PENETRANTES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">LÍQUIDOS PENETRANTES</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="PMI" 
                    data-name="PMI"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    
                    <title>PMI</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/PMI.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">PMI</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="RELEVADO DE ESFUERZOS" 
                    data-name="RELEVADO DE ESFUERZOS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>RELEVADO DE ESFUERZOS</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/RELEVADO_ESFUERZOS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">RELEVADO DE ESFUERZOS</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="TOFD" 
                    data-name="TOFD"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>TOFD</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/TOFD.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">TOFD</text>
                </svg>

        </div>


    </div>
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

    function redirectToView(element) {
        // Capturamos el valor del atributo data-name del SVG
        const serviceName = element.getAttribute('data-name');

        // Preparamos los datos que enviaremos en la solicitud POST
        const data = { service: serviceName };
        //console.log('Datos a enviar:', data);

        // Enviamos la solicitud POST con Fetch API
        fetch('/Servicios-Pruebas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(data), // Convierte los datos a JSON
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .then(data => {
            if (data) {
                console.log('Respuesta del servidor:', data);
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
        });
    }

</script>

@endsection