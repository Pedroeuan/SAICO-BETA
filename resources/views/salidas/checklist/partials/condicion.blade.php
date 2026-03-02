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
        <th>Liquido limpia parabrisas</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->liquido_limpiaparabrisas ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Aceite</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->aceite ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Anticongelante</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->anticongelante ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Estado general llantas</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->estado_llantas ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Delantera izquierda (calibracion)</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->llanta_delantera_izq_calibracion ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Delantera derecha (calibracion)</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->llanta_delantera_der_calibracion ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Trasera izquierda (calibracion)</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->llanta_trasera_izq_calibracion ?? 'N/A')) }}</td>
    </tr>
    <tr>
        <th>Trasera derecha (calibracion)</th>
        <td>{{ ucfirst(str_replace('_', ' ', $condicion->llanta_trasera_der_calibracion ?? 'N/A')) }}</td>
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
