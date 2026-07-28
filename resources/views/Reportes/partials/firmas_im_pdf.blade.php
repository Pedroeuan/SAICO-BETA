<style>
    .firmas-im { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .firmas-im td { text-align: center; vertical-align: top; padding: 0 12px; font-size: 7px; }
    .firmas-im .firma-titulo { font-weight: bold; line-height: 10px; min-height: 6px; }
    .firmas-im .firma-linea { border-bottom: 1px solid #000; height: 10px; margin-top: 0; line-height: 10px; padding-top: 10px; box-sizing: border-box; font-weight: bold; }
    .firmas-im .firma-dato { margin-top: 2px; line-height: 6px; font-weight: bold; }
    .firmas-im .firma-ficha { margin-top: 2px; line-height: 10px; font-weight: bold; }
    .firmas-im-4 td { padding: 2px 12px 0 12px; }
    .firmas-im .firma-separacion-tres td { padding-top: 0px; }
    .firmas-im .firma-separacion-cuatro td { padding-top: 10px; }
</style>

<table class="firmas-im firmas-im-{{ $numFirmas }}">
    @if($numFirmas == 1)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
        </tr>
    @elseif($numFirmas == 2)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
        </tr>
    @elseif($numFirmas == 3)
        <tr>
            <td></td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
            <td></td>
        </tr>
        <tr class="firma-separacion-tres">
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td></td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-ficha">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
            </td>
        </tr>
    @elseif($numFirmas == 4)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
        </tr>
        <tr class="firma-separacion-cuatro">
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo3'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-ficha">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
            </td>
        </tr>
    @endif
</table>
