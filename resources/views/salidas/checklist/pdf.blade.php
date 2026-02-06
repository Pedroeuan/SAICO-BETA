<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checklist {{ ucfirst($tipo) }}</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Checklist de {{ ucfirst($tipo) }}</h2>

<p>
<strong>Vehículo:</strong> {{ $salida->vehiculo->placa }} <br>
<strong>Chofer:</strong> {{ $salida->chofer->name }} <br>
<strong>Fecha:</strong> {{ $tipo == 'salida' ? $salida->fecha_salida : $salida->fecha_regreso }}
</p>

<table>
<tr>
    <th>Nivel gasolina</th>
    <td>{{ $checklist->nivel_gasolina }}</td>
</tr>
<tr>
    <th>Kilometraje</th>
    <td>{{ $checklist->kilometraje }}</td>
</tr>
<tr>
    <th>Limpio exterior</th>
    <td>{{ $checklist->limpio_exterior ? 'Sí' : 'No' }}</td>
</tr>
<tr>
    <th>Limpio interior</th>
    <td>{{ $checklist->limpio_interior ? 'Sí' : 'No' }}</td>
</tr>
<tr>
    <th>Observaciones</th>
    <td>{{ $checklist->observaciones }}</td>
</tr>
</table>

@if($checklist->herramientas->count())
<h4>Herramientas</h4>
<table>
<tr>
    <th>Herramienta</th>
    <th>Disponible</th>
</tr>
@foreach($checklist->herramientas as $h)
<tr>
    <td>{{ ucfirst(str_replace('_',' ',$h->herramienta)) }}</td>
    <td>{{ $h->disponible ? 'Sí' : 'No' }}</td>
</tr>
@endforeach
</table>
@endif

<p style="margin-top:20px;">
<strong>Generado el:</strong> {{ now() }}
</p>

</body>
</html>
