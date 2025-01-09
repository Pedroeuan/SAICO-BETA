
@extends('adminlte::page')

@section('title', 'Usuarios')

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

@endsection

@section('content')
<br>  
<br>
<br>
    <div class="row" data-masonry="{&quot;percentPosition&quot;: true }" style="position: relative; height: 690px;">

        <!-- 1ra columna -->
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 0%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="ANÁLISIS QUÍMICO" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>ANÁLISIS QUÍMICO</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ANALISIS_QUIMICO.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ANÁLISIS QUÍMICO</text>
                </svg>
                
                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="CORRIENTES EDDY" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>CORRIENTES EDDY</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/CORRIENTES_EDDY.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">CORRIENTES EDDY</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="METALOGRAFIA" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>METALOGRAFIA</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/METALOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">METALOGRAFIA</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="PRECALENTAMIENTO" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>PRECALENTAMIENTO</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/PRECALENTAMIENTO.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">PRECALENTAMIENTO</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="TERMOGRAFIA" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>TERMOGRAFIA</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/TERMOGRAFIA.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">TERMOGRAFIA</text>
                </svg>

        </div>

        <!-- 2da columna -->
        
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 33.3333%; top: 0px;">         
            
                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="ARREGLO DE FASES" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>ARREGLO DE FASES</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ARREGLO_FASES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ARREGLO DE FASES</text>
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="DUREZAS" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>DUREZAS</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/DUREZAS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">DUREZAS</text>
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="PARTÍCULAS MAGNÉTICAS" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
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

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="ULTRASONIDO" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>ULTRASONIDO</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/ULTRASONIDO.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">ULTRASONIDO</text>
                </svg>
        </div>

            <!-- 3ra columna -->
            
            <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 66.6667%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="CARACTERIZACIÓN DE MATERIALES" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>CARACTERIZACIÓN DE MATERIALES</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/CARACTERIZACION_MATERIALES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">CARACTERIZACIÓN DE MATERIALES</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="LÍQUIDOS PENETRANTES" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>LQUIDOS PENETRANTES</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/LIQUIDOS_PENETRANTES.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">LÍQUIDOS PENETRANTES</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="PMI" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>PMI</title>
                    <rect width="100%" height="100%" fill="#0070C0"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/PMI.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">PMI</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="RELEVADO DE ESFUERZOS" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>RELEVADO DE ESFUERZOS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/RELEVADO_ESFUERZOS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">RELEVADO DE ESFUERZOS</text>
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="OTROS" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>OTROS</title>
                    <rect width="100%" height="100%" fill="#C04040"></rect>
                    <image href="{{ asset('images/Menu Servicios SVG/OTROS.svg') }}" x="10%" y="10%" width="80%" height="70%" />
                    <text x="50%" y="95%" fill="white" font-size="20" text-anchor="middle" font-weight="bold">OTROS</text>
                </svg>
        </div>


    </div>
@stop

@section('js')
<script>

</script>

@endsection