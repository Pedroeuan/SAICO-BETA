
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
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }

</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@include('Reportes.Reporte17025.Partials.tabs')
@if(request('categoria') === '17025')
    @php
        $servicios17025 = [
            ['F-SGLE-708-02-PT', 'LÍQUIDOS PENETRANTES', 'LIQUIDOS_PENETRANTES.svg'],
            ['F-SGLE-708-04-PAUT', 'ULTRASONIDO CON ARREGLO DE FASES', 'ARREGLO_FASES.svg'],
            ['F-SGLE-708-06-MT', 'PARTÍCULAS MAGNÉTICAS', 'PARTICULAS_MAGNETICAS.svg'],
            ['ULTRASONIDO', 'ULTRASONIDO', 'ULTRASONIDO.svg'],
        ];
    @endphp
    <style>
        .services17025-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px; padding-bottom:32px; }
        .service17025-card { display:block; background:#C04040; color:white; text-decoration:none; transition:transform .3s ease; }
        .service17025-card:hover { transform:scale(1.03); color:white; text-decoration:none; }
        .service17025-card:focus-visible { outline:3px solid #0869ad; outline-offset:4px; }
        @media(max-width:991px) { .services17025-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(max-width:575px) { .services17025-grid { grid-template-columns:1fr; } }
    </style>
    <div class="services17025-grid">
        @foreach($servicios17025 as [$codigo, $nombre, $imagen])
            <a class="service17025-card" href="{{ route('Seleccion.Servicios.Pruebas', ['categoria' => '17025', ($codigo === 'ULTRASONIDO' ? 'servicio17025' : 'formato17025') => $codigo]) }}">
                <svg width="100%" height="200" role="img" aria-label="{{ $nombre }} — {{ $codigo }}" viewBox="0 0 420 200" xmlns="http://www.w3.org/2000/svg">
                    <title>{{ $nombre }} — {{ $codigo }}</title>
                    <rect width="100%" height="100%" fill="#C04040" />
                    <image href="{{ asset('images/Menu Servicios SVG/' . $imagen) }}" x="10%" y="8%" width="80%" height="68%" />
                    <text x="50%" y="88%" fill="white" font-size="18" font-weight="bold" text-anchor="middle">
                        @if(str_starts_with($nombre, 'ULTRASONIDO CON '))
                            <tspan x="50%">ULTRASONIDO</tspan>
                            <tspan x="50%" dy="20">{{ str_replace('ULTRASONIDO ', '', $nombre) }}</tspan>
                        @else
                            {{ $nombre }}
                        @endif
                    </text>
                </svg>
            </a>
        @endforeach
    </div>
@else
<div class="row">
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
<svg class="bd-placeholder-img card-img-top expansive-effect"

                        width="100%" height="200" 
                        role="img" aria-label="TRATAMIENTO TÉRMICO DE PWHT" 
                        data-name="TRATAMIENTO TÉRMICO DE PWHT"
                        onclick="redirectToView(this)"
                        focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                        <title>TRATAMIENTO TÉRMICO DE PWHT</title>
                        <rect width="100%" height="100%" fill="#0070C0"></rect>
                        <image href="{{ asset('images/Menu Servicios SVG/RELEVADO_ESFUERZOS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                        <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">TRATAMIENTO TÉRMICO DE PWHT</text>
                    </svg>
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
<svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="ONDAS GUIADAS" 
                    data-name="ONDAS GUIADAS"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>ONDAS GUIADAS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ONDAS_GUIADAS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ONDAS GUIADAS</text>
                </svg>
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
    @endif
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') === 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
<svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="RADIOGRAFIA" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>RADIOGRAFIA</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/RADIOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">RADIOGRAFIA</text>
                </svg>
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
<svg class="bd-placeholder-img card-img-top expansive-effect"

                    width="100%" height="200" 
                    role="img" aria-label="VISUAL" 
                    data-name="VISUAL"
                    onclick="redirectToView(this)"
                    focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">

                    <title>VISUAL</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/Inspección_Visual.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">VISUAL</text>
                </svg>
        </div>
    @endif
    @if(request('categoria') !== 'im')
        <div class="col-sm-6 col-lg-4 mb-3">
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
    @endif
</div>
@endif
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
        const data = { service: serviceName, categoria: @json(request('categoria') === 'im' ? 'im' : 'general') };
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
