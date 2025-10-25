
@extends('adminlte::page')

@section('title', 'Equipos')

@section('css')
<style>
    /*#my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    /*overflow-y: auto;
    }*/

    <style>
    #my-notification .navbar-badge {
        position: absolute;
        top: 2px;
        right: 2px;
    }
</style>
</style>
@endsection

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
                plugins: {
                    // Complemento para mostrar etiquetas encima de las barras
                    tooltip: {
                        enabled: true // Habilita los tooltips (opcional)
                    },
                    datalabels: {
                        display: true,
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => value
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true
                    }
                }
            },
            plugins: [{
                id: 'customLabels',
                afterDatasetsDraw(chart) {
                    const { ctx, data } = chart;
                    chart.data.datasets.forEach((dataset, i) => {
                        const meta = chart.getDatasetMeta(i);
                        meta.data.forEach((bar, index) => {
                            const value = dataset.data[index];
                            ctx.fillStyle = 'black'; // Color del texto
                            ctx.font = '12px Arial'; // Estilo de la fuente
                            ctx.textAlign = 'center';
                            //ctx.fillText(value, bar.x, bar.y - 5); // Posición del texto Arriba de la barra
                            ctx.fillText(value, bar.x, bar.y + 545); // Posición del texto Debajo de la barra
                        });
                    });
                }
            }]
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Asegura que el badge exista siempre
        let link = document.querySelector('#my-notification a.nav-link');
        if (link && !link.querySelector('.navbar-badge')) {
            let badge = document.createElement('span');
            badge.classList.add('badge', 'navbar-badge');
            badge.style.display = 'none'; // oculto inicialmente
            link.appendChild(badge);
        }
    });
    </script>
@stop
