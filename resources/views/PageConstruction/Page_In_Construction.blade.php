
@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

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
        <div style="text-align: center;">
            <img src="{{ asset('images/Logo_AICO_R1.jpg') }}" alt="logo-aico" width="440" height="350">
        </div>

        <div style="text-align: center; padding: 50px;">
            <img src="{{ asset('images/under-construction.png') }}" alt="Página en construcción" style="width: 200px; margin-bottom: 20px;">
            <h1 style="font-size: 2.5em; color: #333;">¡Estamos trabajando en ello!</h1>
            <p style="font-size: 1.2em; color: #666;">Esta página está actualmente en construcción. Pronto estará disponible para que disfrutes de todas sus funcionalidades.</p>
            <p style="margin-top: 20px;">Gracias por tu paciencia. Mientras tanto, puedes <a href="/dashboard" style="color: #007BFF; text-decoration: none;">volver al inicio</a> 
            o explorar nuestras <a href="/dashboard" style="color: #007BFF; text-decoration: none;">páginas disponibles</a>.</p>
        </div>

        <!--<div style="text-align: center; padding: 50px;">
            <img src="{{ asset('images/under-construction.png') }}" alt="Módulo en construcción" style="width: 200px; margin-bottom: 20px;">
            <h1 style="font-size: 2.5em; color: #333;">¡Muy pronto!</h1>
            <p style="font-size: 1.2em; color: #666;">Estamos trabajando en este módulo para ofrecerte una experiencia mejorada.</p>
            <p style="font-size: 1em; color: #888; margin-top: 20px;">Tiempo estimado para lanzamiento:</p>
            <p style="font-size: 2em; color: #555;"><span id="countdown"></span></p>
        </div>-->

        </div>
    </div>
</form>
@stop

@section('js')
<script>
    // Temporizador de cuenta regresiva
    /*const countdownElement = document.getElementById('countdown');
    const endDate = new Date('2024-12-31T23:59:59').getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const timeLeft = endDate - now;

        if (timeLeft > 0) {
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        } else {
            countdownElement.textContent = '¡Ya está disponible!';
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);*/
</script>

@endsection