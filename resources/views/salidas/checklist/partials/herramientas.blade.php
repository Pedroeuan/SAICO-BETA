<h4>Herramientas</h4>

@if($herramientas->count())
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Herramienta</th>
            <th>Disponible</th>
        </tr>
    </thead>
    <tbody>
        @foreach($herramientas as $h)
        <tr>
            <td>{{ ucwords(str_replace('_',' ', $h->herramienta)) }}</td>
            <td>{{ $h->disponible ? 'Sí' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No se registraron herramientas.</p>
@endif
