<style>
    .firmas-im {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        font-family: Arial, sans-serif;
        font-size: 10px;
        position: relative;
        top: -18px; /* SUBE TODA LA FIRMA */
    }

    .firmas-im td {
        text-align: center;
        vertical-align: top;
        padding: 0 8px;
        font-size: 10px;
    }

    /* =========================
        TITULO: REALIZÓ / Vo.Bo.
    ========================= */
    .firmas-im .firma-titulo {
        font-weight: bold;
        font-size: 10px;
        line-height: 10px;
        height: 12px;
        text-align: center;

        position: relative;
        top: 15px;
    }

    /* =========================
        CONTENEDOR DE LA LINEA
    ========================= */
    .firmas-im .firma-linea {
        width: 85%;
        height: 40px;

        margin-left: auto;
        margin-right: auto;

        border-bottom: 1px solid #000;

        position: relative;
        top: -5px;

        padding: 0;
        box-sizing: border-box;
    }

    /* =========================
        NOMBRE PEGADO A LA LINEA
    ========================= */
    .firmas-im .firma-nombre {
        position: absolute;

        left: 0;
        right: 0;

        bottom: 0px;

        text-align: center;
        font-weight: bold;
        font-size: 10px;
        line-height: 9px;
    }

    /* =========================
        CARGO / EMPRESA
    ========================= */
    .firmas-im .firma-dato {
        margin-top: 2px;
        line-height: 9px;
        font-size: 10px;
        font-weight: bold;
        text-align: center;
    }

    .firmas-im .firma-ficha {
        margin-top: 2px;
        line-height: 9px;
        font-size: 8px;
        font-weight: bold;
        text-align: center;
    }

    /* =========================
        1 FIRMA
    ========================= */
    .firmas-im-1 td {
        width: 100%;
    }

    .firmas-im-1 .firma-linea {
        width: 5cm;
    }

    /* =========================
        2 FIRMAS
    ========================= */
    .firmas-im-2 td {
        width: 50%;
        padding: 0 20px;
    }

    .firmas-im-2 .firma-linea {
        width: 85%;
    }

    /* =========================
        3 FIRMAS
    ========================= */
    .firmas-im-3 td {
        width: 33.33%;
        padding: 0 12px;
    }

    .firmas-im-3 .firma-linea {
        width: 90%;
    }

    /* =========================
        4 FIRMAS
    ========================= */
    .firmas-im-4 td {
        width: 25%;
        padding: 0 4px;
    }

    .firmas-im-4 .firma-linea {
        width: 95%;
    }
</style>


<table class="firmas-im firmas-im-{{ $numFirmas }}">

    {{-- =========================
        1 FIRMA
    ========================= --}}
    @if($numFirmas == 1)

        <tr>
            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Realizo'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    Asesoría e Inspección en Construcción Costa Fuera, S.C.
                </div>

            </td>
        </tr>


    {{-- =========================
        2 FIRMAS
    ========================= --}}
    @elseif($numFirmas == 2)

        <tr>

            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Realizo'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    Asesoría e Inspección en Construcción Costa Fuera, S.C.
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo1'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}
                </div>

            </td>

        </tr>


    {{-- =========================
        3 FIRMAS
    ========================= --}}
    @elseif($numFirmas == 3)

        <tr>

            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Realizo'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    Asesoría e Inspección en Construcción Costa Fuera, S.C.
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo1'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo2'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-ficha">
                    {{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}
                </div>

            </td>

        </tr>


    {{-- =========================
        4 FIRMAS
    ========================= --}}
    @elseif($numFirmas == 4)

        <tr>

            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Realizo'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    Asesoría e Inspección en Construcción Costa Fuera, S.C.
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo1'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo2'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}
                </div>

            </td>


            <td>

                <div class="firma-titulo">
                    {{ $Firmas_Reportes['Vobo3'] ?? '' }}
                </div>

                <div class="firma-linea">
                    <span class="firma-nombre">
                        {{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}
                    </span>
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-dato">
                    {{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}
                </div>

                <div class="firma-ficha">
                    {{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}
                </div>

            </td>

        </tr>

    @endif

</table>