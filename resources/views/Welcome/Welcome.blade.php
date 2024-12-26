
@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
    .welcome-container {
        text-align: center;
        margin-top: 50px;
    }
    .welcome-title {
        font-size: 2.5em;
        margin-bottom: 20px;
    }
    .welcome-message {
        font-size: 1.2em;
        margin-bottom: 30px;
    }
    .welcome-button {
        padding: 10px 20px;
        font-size: 1em;
    }
</style>

@endsection

@section('content')
<br>  
<br>
<br>
<div class="welcome-container">
    <h1 class="welcome-title">Bienvenido a S'AICO</h1>
    <p class="welcome-message">Estamos encantados de tenerte aquí. Explora nuestras funcionalidades y disfruta de la experiencia.</p>
    <div style="text-align: center; padding: 50px;">
        <img src="{{ asset('images/Logo_AICO_R1.jpg') }}" alt="Página en construcción" style="width: 400px; margin-bottom: 20px;">
    </div>
</div>
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