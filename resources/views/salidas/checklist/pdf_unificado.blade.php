

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checklist Vehicular</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {text-align: center;}
        .logos img{height: 70px;}
        .section{margin-top: 15px;}
        table{width: 100%; border-collapse: collapse;}
        th, td{border: 1px solid #000; padding: 5px;}
    </style>
</head>
<body>
<div class="logos">
    <img src="{{public_path('logo.png')}}">
</div>
<h2 style="text-align:center">Checklist de Vehículo</h2>

<hr>

<h3>Datos Generales</h3>
<p><strong>Vehículo:</strong> {{ $salida->vehiculo->placa }}</p>
<p><strong>Chofer:</strong> {{ $salida->chofer->name }}</p>
<p><strong>Fecha salida:</strong> {{ $salida->fecha_salida }}</p>

<hr>

{{-- SALIDA --}}
<h3>Checklist de SALIDA</h3>

@include('salidas.checklist.partials.condicion', [
    'condicion' => $checklistSalida->condicion
])

@include('salidas.checklist.partials.herramientas', [
    'herramientas' => $checklistSalida->herramientas
])

@include('salidas.checklist.partials.documentos', [
    'documentos' => $checklistSalida->documentos
])

<h4>Evidencias de SALIDA</h4>
@foreach ($checklistSalida->evidencias as $evidencia)
    <img src="{{ public_path('storage/'.$evidencia->foto) }}"
         style="width:150px; margin:5px;">
@endforeach


{{-- ENTRADA --}}
@if($checklistEntrada)

<hr>
<h3>Checklist de ENTRADA</h3>

@include('salidas.checklist.partials.condicion', [
    'condicion' => $checklistEntrada->condicion
])

<h4>Evidencias de ENTRADA</h4>
@foreach ($checklistEntrada->evidencias as $evidencia)
    <img src="{{ public_path('storage/'.$evidencia->foto) }}"
         style="width:150px; margin:5px;">
@endforeach

@else

<p><strong>Entrada:</strong> Aún no registrada</p>

@endif

</body>
</html>
