
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
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 0%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS_QUIMICO.png') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

        </div>
        
        <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 33.3333%; top: 0px;">            
                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

            <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>
        </div>
        
            <div class="col-sm-6 col-lg-4 mb-4" style="position: absolute; left: 66.6667%; top: 0px;">

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>

                <div style="margin-bottom: 15px;"></div>

                <svg class="bd-placeholder-img card-img-top expansive-effect" width="100%" height="200" role="img" aria-label="Placeholder: Capa de imagen" focusable="false" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                    <title>Placeholder</title>
                    <image href="{{ asset('images/MenuServicios/ANALISIS QUIMICO.jpg') }}" width="100%" height="100%" />
                </svg>
        </div>


    </div>
@stop

@section('js')
<script>

</script>

@endsection