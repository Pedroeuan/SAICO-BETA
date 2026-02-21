@if($condicion)
<table>
    <tr>
        <th>Nivel de gasolina</th>
        <td>{{ $condicion->nivel_gasolina }}</td>
    </tr>
    <tr>
        <th>Kilometraje</th>
        <td>{{ $condicion->kilometraje }}</td>
    </tr>
    <tr>
        <th>Limpio exterior</th>
        <td>{{ $condicion->limpio_exterior ? 'Sí' : 'No' }}</td>
    </tr>
    <tr>
        <th>Limpio interior</th>
        <td>{{ $condicion->limpio_interior ? 'Sí' : 'No' }}</td>
    </tr>
    <tr>
        <th>Observaciones</th>
        <td>{{ $condicion->observaciones ?? 'N/A' }}</td>
    </tr>
</table>
@else
<p>No hay información de condición.</p>
@endif
