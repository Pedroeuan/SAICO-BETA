<h4>Documentos</h4>

@if($documentos->count())
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documentos as $d)
        <tr>
            <td>{{ ucwords(str_replace('_',' ', $d->documento)) }}</td>
            <td>{{ strtoupper($d->estatus) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No se registraron documentos.</p>
@endif
