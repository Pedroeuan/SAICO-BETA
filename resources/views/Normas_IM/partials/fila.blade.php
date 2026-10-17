<tr>
    <td class="numero-fila">{{ $indice + 1 }}</td>
    @foreach($columnas as $campo => $titulo)
        @php($esContexto = $esQuimica && array_key_exists($campo, \App\Services\TablasNormasIM::aplicabilidad()))
        <td><input class="form-control form-control-sm" data-campo="{{ $campo }}" @if($esContexto) data-contexto="1" @endif name="{{ $esQuimica ? ($esContexto ? 'Contexto['.$indice.']['.$campo.']' : $campo.'[]') : 'Tablas_Adicionales['.$clave.']['.$indice.']['.$campo.']' }}" value="{{ $esContexto ? ($fila['Aplicabilidad'][$campo] ?? '') : ($fila[$campo] ?? '') }}" aria-label="{{ $titulo }}" maxlength="{{ $esContexto || !$esQuimica ? 2000 : ($campo === 'Elemento' ? 100 : 255) }}"></td>
    @endforeach
    @if($clave === 'mecanica')
        <td style="min-width: 200px">
            <details>
                <summary class="text-primary">Elongación y reducción de área</summary>
                @foreach(['elongacion' => 'Elongación mínima (%) y longitud de referencia', 'reduccion' => 'Reducción de área mínima (%)'] as $campoAdicional => $tituloAdicional)
                    <label class="small mt-2 mb-1 d-block">{{ $tituloAdicional }}
                        <input class="form-control form-control-sm" data-campo="{{ $campoAdicional }}" name="Tablas_Adicionales[mecanica][{{ $indice }}][{{ $campoAdicional }}]" value="{{ $fila[$campoAdicional] ?? '' }}" aria-label="{{ $tituloAdicional }}" maxlength="2000">
                    </label>
                @endforeach
            </details>
        </td>
    @endif
    <td><button class="btn btn-danger btn-sm quitar-fila-norma" type="button" aria-label="Eliminar fila">×</button></td>
</tr>
