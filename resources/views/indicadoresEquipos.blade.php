
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
                <h3 class="card-title">Inidicadores de consumibles</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="" data-source-selector="#card-refresh-content">
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
@php 
//dd($resultado);
@endphp

@section('js')
<script>
    console.log('prueba');
</script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Convierte la colección de resultados a JSON para JavaScript
            const consumibles = {!! json_encode($consumibles) !!};

            // Mapea los nombres y stocks de los datos
            const nombres = consumibles.map(item => item.Nombre_E_P_BP);
            const stocks = consumibles.map(item => item.almacen ? item.almacen.Stock : 0); // Usa 0 si 'almacen' es null

            // Verifica los datos en la consola
            console.log('Nombres:', nombres);
            console.log('Stocks:', stocks);

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