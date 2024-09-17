
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')  
<!-- form start -->
<br><br><br><br>
<head>
    <h2>
        Bienvenido a equipos
    </h2>
</head>
<br>
<h3>Este es tu indicador de stock de consumibles</h3>
<br>
<div class="card">
    <div class="card-body">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Indicadores de consumibles</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="" data-source="" data-source-selector="#card-refresh-content">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <canvas id="grafico"></canvas>
            </div>
        </div>
    </div>
    
</div>

@endsection
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
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Convierte la colección de resultados a JSON para JavaScript
            const consumibles = {!! json_encode($consumibles) !!};

            // Mapea los nombres y stocks de los datos
            const nombres = consumibles.map(item => item.Nombre_E_P_BP);
            const stocks = consumibles.map(item => item.almacen ? item.almacen.Stock : 0); // Usa 0 si 'almacen' es null

            // Verifica los datos en la consola
            //console.log('Nombres:', nombres);
            //console.log('Stocks:', stocks);

            // Obtén el contexto del canvas
            const abc = document.querySelector('#grafico').getContext('2d');

            // Crea el gráfico de barras
            const stackedBar = new Chart(abc, {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Stock en consumibles',
                        data: stocks,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            stacked: true
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });
        });
    </script>
@stop