{{-- Preparacion de datos y funciones del documento --}}
@php
    /* PAG.4: plantilla autónoma del reporte fotográfico PT. */
    $g = $Detalles_Generales ?? [];
    $d = $Datos_Equipo ?? [];
    $escPt = fn($v) => e(is_scalar($v) ? (string) $v : '');
    $textoPt = fn($v) => nl2br($escPt($v), false);
    $piesExcelPt = [4 => 'Asesoría e Inspección en Construcción Costa Fuera S.C.
Calle 60 Núm. Exterior 75 entre Calle 33 A y Calle 60, Col. Fatima, C.P. 24110, Ciudad del Carmen, Campeche.
Teléfono: 938-197-8815     Whatsapp: 938 103 4445 
Correo: Aclientes-Lab@aicosc.com /jorge.moo@aicosc.com  
Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO, S.C., Documento Confidencial.'];
    $firmaDatosPt = $Firmas_Reportes ?? [];
    $numeroFirmasPt = max(1, min(4, (int) ($firmaDatosPt['numFirmas'] ?? $numFirmas ?? 1)));
    $firmasPt = function ($cuatroSeparadas = false) use ($numeroFirmasPt, $firmaDatosPt, &$textoFijoP1) {
        $mostrarCuatro = $numeroFirmasPt === 4;
        $campos = [
            ['NOMBRE_TECNICO', 'CARGO_TECNICO', 'Realizo'],
            ['NOMBRE_ENCARGADO', 'PUESTO_ENCARGADO', 'Vobo1'],
            ['NOMBRE_2DO_ENCARGADO', 'PUESTO_2DO_ENCARGADO', 'Vobo2'],
            ['NOMBRE_3RO_ENCARGADO', 'PUESTO_3RO_ENCARGADO', 'Vobo3'],
        ];
        $html = '<table class="firmas-pt' . ($mostrarCuatro ? ' cuatro-firmas-pt' : '') . '"><tr>';
        foreach (array_slice($campos, 0, $numeroFirmasPt) as [$nombre, $cargo, $rol]) {
            $anchoFirma = $mostrarCuatro ? 118 : 149;
            $html .= '<td><div class="nombre-firma">'
                . $textoFijoP1($firmaDatosPt[$nombre] ?? '', $anchoFirma, 10, 6, 'Nombre de firma')
                . '</div><div class="espacio-firma"></div><strong>'
                . $textoFijoP1($firmaDatosPt[$rol] ?? '', $anchoFirma, 10, 6, 'Rol de firma')
                . '</strong><span>'
                . $textoFijoP1($firmaDatosPt[$cargo] ?? '', $anchoFirma, 10, 6, 'Cargo de firma')
                . '</span></td>';
        }
        return $html . '</tr></table>';
    };
    $cabeceraPt = function ($pagina) use ($escPt, $g, $totalPaginasPt, $recursosFormatoPt) {
        return '<div class="cabecera-pt">'
            . '<img class="banda-superior" src="' . $recursosFormatoPt['superior'] . '">'
            . '<img class="logo-aico" src="' . $recursosFormatoPt['aico'] . '">'
            . '<div class="titulo-informe">Informe de Ensayo<br><em>Testing report</em><br>'
            . '<span>Liquidos Penetrantes</span><br><em>Liquid Penetrant</em></div>'
            . '<img class="logo-ema" src="' . $recursosFormatoPt['ema'] . '">'
            . '<div class="identificacion"><div class="codigo"><div class="numero-pagina-pt">Pág. '
            . $pagina . ' de ' . $totalPaginasPt
            . '</div>F-SGLE-708-02 (PT) VER. 1</div><div class="numero-box">'
            . '<div class="numero-label">No. de Informe:<br><em>Number of report:</em></div>'
            . '<div class="numero-informe">' . $escPt($g['No_Reporte'] ?? '')
            . '</div></div></div></div>';
    };
    $piePt = function ($hojaExcel) use ($textoPt, $piesExcelPt, $recursosFormatoPt) {
        return '<div class="pie-pt">' . $textoPt($piesExcelPt[$hojaExcel])
            . '</div><img class="banda-inferior" src="' . $recursosFormatoPt['inferior'] . '">';
    };
    $marcaPt = '<img class="marca-agua" src="' . $recursosFormatoPt['marca'] . '">';
    $metricasPt = app('dompdf')->getFontMetrics();
    $fuentePt = $metricasPt->getFont('Times-Roman', 'normal') ?: $metricasPt->getFont('serif', 'normal');
    $fuenteUnicodePt = $metricasPt->getFont('DejaVu Serif', 'normal');
    $fragmentosPt = fn($texto) => preg_split('/([^\x{0000}-\x{00FF}]+)/u', $texto, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $anchoTextoPt = function ($texto, $tamano) use ($fragmentosPt, $metricasPt, $fuentePt, $fuenteUnicodePt) {
        $ancho = 0;
        foreach ($fragmentosPt($texto) as $fragmento) {
            $fuente = preg_match('/[^\x{0000}-\x{00FF}]/u', $fragmento) ? $fuenteUnicodePt : $fuentePt;
            $ancho += $metricasPt->getTextWidth($fragmento, $fuente, $tamano);
        }
        return $ancho;
    };
    $textoFijoP1 = function ($valor, $ancho, $alto, $tamano = 6, $campo = 'Campo técnico') use ($fragmentosPt, $anchoTextoPt) {
        $texto = is_scalar($valor) ? (string) $valor : '';
        if ($texto === '') {
            return '';
        }
        $lineas = [];
        foreach (explode("\n", str_replace(["\r\n", "\r", "\t"], ["\n", "\n", ' '], $texto)) as $parrafo) {
            $linea = '';
            foreach (preg_split('/\s+/u', trim($parrafo)) as $palabra) {
                if ($palabra === '') {
                    continue;
                }
                $propuesta = $linea === '' ? $palabra : $linea . ' ' . $palabra;
                if ($anchoTextoPt($propuesta, $tamano) <= $ancho) {
                    $linea = $propuesta;
                    continue;
                }
                if ($linea !== '') {
                    $lineas[] = $linea;
                }
                $linea = '';
                // Sólo dividir identificadores o palabras que no caben por sí solos.
                foreach (preg_split('//u', $palabra, -1, PREG_SPLIT_NO_EMPTY) as $caracter) {
                    if ($linea !== '' && $anchoTextoPt($linea . $caracter, $tamano) > $ancho) {
                        $lineas[] = $linea;
                        $linea = '';
                    }
                    $linea .= $caracter;
                }
            }
            $lineas[] = $linea;
        }
        if (count($lineas) * 6.48 > $alto + 0.01) {
            throw new \RuntimeException("El campo «{$campo}» no cabe en el espacio disponible del informe PT a 6 pt.");
        }
        $renderizarLinea = function ($linea) use ($fragmentosPt) {
            $html = '';
            foreach ($fragmentosPt($linea) as $fragmento) {
                // Times-Roman no incluye estos caracteres; conservar sólo sus glifos en una fuente serif Unicode.
                $html .= preg_match('/[^\x{0000}-\x{00FF}]/u', $fragmento)
                    ? '<span style="font-family:DejaVu Serif, serif">' . e($fragmento) . '</span>'
                    : e($fragmento);
            }
            return $html;
        };
        return '<span class="texto-ajustado-p1">' . implode('<br>', array_map($renderizarLinea, $lineas)) . '</span>';
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>F-SGLE-708-02 (PT) — {{ $g['No_Reporte'] ?? '' }}</title>
    {{-- Bloque de estilos del documento --}}
    <style>
        @page {
            size: letter portrait;
            margin:0;
        }
        * {
            box-sizing:border-box;
        }
        html, body {
            margin:0;
            padding:0;
            font-family:"Times-Roman", "Times New Roman", serif;
            color:#000;
            font-size:6pt;
            line-height:1.08;
        }
        .hoja-pt {
            width:612pt;
            height:791.8pt;
            position:relative;
            page-break-after:always;
        }
        .hoja-pt:last-child {
            page-break-after:auto;
        }
        .marca-agua {
            position:absolute;
            left:95.1969pt;
            top:173.8583pt;
            width:445pt;
            height:495pt;
            opacity:.05;
            z-index:-1;
        }
        .cabecera-pt {
            position:absolute;
            top:53.8583pt;
            left:65.1969pt;
            width:509.9527pt;
            height:74pt;
        }
        .banda-superior {
            position:absolute;
            left:0;
            top:0;
            width:100%;
            height:13pt;
        }
        .logo-aico {
            position:absolute;
            left:5pt;
            top:17pt;
            width:91pt;
            height:39pt;
            object-fit:contain;
        }
        .logo-ema {
            position:absolute;
            right:5pt;
            top:22pt;
            width:86pt;
            height:29pt;
            object-fit:contain;
        }
        .titulo-informe {
            position:absolute;
            top:15pt;
            left:111pt;
            width:280pt;
            text-align:center;
            color:#173b60;
            font-weight:bold;
            font-size:9pt;
            line-height:1.03;
        }
        .titulo-informe em {
            font-size:9pt;
            font-weight:normal;
        }
        .titulo-informe span {
            font-size:9pt;
        }
        .identificacion {
            position:absolute;
            left:0;
            top:57pt;
            width:100%;
            height:20pt;
        }
        .codigo {
            position:absolute;
            left:0;
            top:0;
            width:61%;
            font-size:6pt;
            line-height:1.15;
        }
        .numero-pagina-pt {
            width:71.33pt;
            text-align:center;
        }
        .numero-box {
            position:absolute;
            right:0;
            top:0;
            width:39%;
            height:20pt;
            background:#fff;
        }
        .numero-label {
            position:absolute;
            left:0;
            top:0;
            width:60%;
            height:100%;
            padding:2pt 0;
            font-size:6pt;
            font-weight:bold;
            text-align:right;
            line-height:1.05;
            background:#fff;
        }
        .numero-label em {
            font-weight:normal;
        }
        .numero-informe {
            position:absolute;
            right:0;
            top:0;
            width:38%;
            height:100%;
            padding:5pt 0 2pt;
            font-size:6pt;
            font-weight:normal;
            text-align:left;
            background:#fff;
        }
        .cuerpo-pt {
            position:absolute;
            left:65.1969pt;
            top:132.8583pt;
            width:509.9527pt;
        }
        table {
            width:100%;
            border-collapse:collapse;
            table-layout:fixed;
        }
        td, th {
            padding:2pt 3pt;
            vertical-align:middle;
            overflow-wrap:break-word;
            word-wrap:break-word;
        }
        th {
            font-weight:bold;
        }
        em {
            font-weight:normal;
        }
        .seccion {
            background:#215d9a;
            color:#fff;
            border:0.7pt solid #111;
            padding:2pt;
            text-align:center;
            font-size:6pt;
            font-weight:bold;
            line-height:1.04;
        }
        .seccion em {
            font-size:6pt;
            font-weight:bold;
        }
        .observaciones {
            border:0.5pt solid #111;
            min-height:19pt;
            padding:3pt;
            font-size:6pt;
        }
        .firmas-pt {
            position:absolute;
            top:642.8583pt;
            left:65.1969pt;
            width:509.9527pt;
            table-layout:fixed;
            font-size:6pt;
        }
        .firmas-pt td {
            width:33.333%;
            text-align:center;
            padding:0 10pt;
            vertical-align:top;
        }
        .nombre-firma {
            min-height:12pt;
            font-size:6pt;
            margin-bottom:1pt;
        }
        .espacio-firma {
            height:12pt;
            border-bottom:0.65pt solid #111;
            margin-bottom:3pt;
        }
        .pie-pt {
            position:absolute;
            top:687.8583pt;
            left:65.1969pt;
            width:509.9527pt;
            text-align:center;
            font-size:6pt;
            line-height:1.15;
        }
        .banda-inferior {
            position:absolute;
            bottom:53.8583pt;
            left:65.1969pt;
            width:509.9527pt;
            height:17pt;
        }
        /* Los nombres quedan inmediatamente encima de su línea; sin desplazamientos individuales. */
        .nombre-firma {
            position:relative;
            top:6pt;
            height:11pt;
            min-height:11pt;
            margin-bottom:0;
            line-height:1.1;
        }
        .espacio-firma {
            height:2pt;
            margin-bottom:3pt;
        }
        .cuatro-firmas-pt td {
            width:25%;
            padding:0 4pt;
            font-size:6pt;
        }
        .cuatro-firmas-pt .nombre-firma {
            font-size:6pt;
        }
        .fotografias {
            table-layout:fixed;
            margin:4pt 0 7pt;
        }
        .fotografias td {
            width:50%;
            border:0.55pt solid #111;
            padding:4pt;
            text-align:center;
            vertical-align:top;
        }
        .foto-marco {
            height:182pt;
        }
        .foto-marco img {
            max-width:239pt;
            max-height:180pt;
            object-fit:contain;
        }
        .foto-rotulo {
            min-height:23pt;
            padding-top:4pt;
            font-size:6pt;
        }
        .foto-comentario {
            font-size:6pt;
            line-height:1.08;
        }
        .texto-ajustado-p1 {
            display:block;
            white-space:pre;
            font-size:6pt;
            line-height:6.48pt;
        }
        .pagina-fotos-pt .foto-marco {
            height:150pt;
        }
        .pagina-fotos-pt .foto-marco img {
            max-height:148pt;
        }
        .pagina-fotos-pt .fotografias {
            margin:4pt 0;
        }
        .pagina-fotos-pt .observaciones {
            height:70pt;
        }
        .pagina-fotos-pt .fotografias {
            border-collapse:separate;
            border-spacing:0;
        }
        .pagina-fotos-pt .fotografias td {
            border:0;
            padding:0 24pt 4pt 0;
        }
        .pagina-fotos-pt .fotografias td + td {
            padding:0 0 4pt 24pt;
        }
        .pagina-fotos-pt .foto-marco {
            border:0.55pt solid #111;
        }
        .pagina-fotos-pt .foto-marco img {
            max-width:228pt;
        }
        .pagina-fotos-pt .foto-marco img.foto-contenida {
            display:block;
            margin:0 auto;
            position:relative;
        }
        .pagina-fotos-pt .foto-marco img.foto-vacia {
            display:block;
            width:100%;
            height:150pt;
            max-width:100%;
            max-height:150pt;
        }
        .pagina-fotos-pt .foto-rotulo {
            border:0.55pt solid #111;
            border-top:0;
            padding:3pt 2pt;
        }
        .pagina-fotos-pt .fotografias .fila-imagen td {
            padding-bottom:0;
        }
        .pagina-fotos-pt .fotografias .fila-rotulo td {
            vertical-align:top;
        }
        .pagina-fotos-pt .fotografias .fila-rotulo .foto-rotulo {
            border:0;
            padding:0;
            min-height:0;
        }
        .pagina-fotos-pt .fotografias .fila-rotulo .contenido-rotulo {
            border:0.55pt solid #111;
            border-top:0;
            padding:3pt 2pt;
        }
        .pagina-fotos-pt .observaciones {
            text-align:center;
        }
    </style>
</head>
{{-- Bloque de contenido del documento --}}
<body>
    @foreach($paginasFotosPt as $fotosPaginaPt)
        @php
            $paginaPt = $paginaInicialFotosPt + $loop->index;
            $grupoFotoPt = $loop->index;
            $rotulosFotosPt = [];
            $lineasFilasFotosPt = [0, 0];
            for ($iFotoPt = 0; $iFotoPt < 4; $iFotoPt++) {
                $comentarioFotoPt = $fotosPaginaPt[$iFotoPt]['comment'] ?? '';
                $rotulosFotosPt[$iFotoPt] = $comentarioFotoPt !== ''
                    ? $textoFijoP1($comentarioFotoPt, 200, 84, 6, 'Comentario foto ' . ($grupoFotoPt * 4 + $iFotoPt + 1))
                    : '';
                $lineasFilasFotosPt[intdiv($iFotoPt, 2)] = max(
                    $lineasFilasFotosPt[intdiv($iFotoPt, 2)],
                    $rotulosFotosPt[$iFotoPt] === '' ? 0 : substr_count($rotulosFotosPt[$iFotoPt], '<br>') + 1
                );
            }
            if (array_sum($lineasFilasFotosPt) > 13) {
                throw new \RuntimeException('Los comentarios fotográficos de esta página PT no caben sin invadir las observaciones o firmas.');
            }
        @endphp
        <div class="hoja-pt pagina-fotos-pt">
            {!! $marcaPt !!}{!! $cabeceraPt($paginaPt) !!}
            <div class="cuerpo-pt">
                <div class="seccion">Reporte fotográfico <em>(Photographic report)</em></div>
                {{-- Registro fotografico --}}
                <table class="fotografias">
                    @for($filaFotoPt = 0; $filaFotoPt < 2; $filaFotoPt++)
                        <tr class="fila-imagen">
                            @for($colFotoPt = 0; $colFotoPt < 2; $colFotoPt++)
                                @php
                                    $indiceFotoPt = $filaFotoPt * 2 + $colFotoPt;
                                    $fotoPt = $fotosPaginaPt[$indiceFotoPt] ?? null;
                                    $medidasFotoPt = !empty($fotoPt['path']) && is_file($fotoPt['path'])
                                        ? @getimagesize($fotoPt['path'])
                                        : false;
                                    if ($medidasFotoPt && ($medidasFotoPt[0] <= 0 || $medidasFotoPt[1] <= 0)) {
                                        $medidasFotoPt = false;
                                    }
                                    if ($medidasFotoPt) {
                                        $escalaFotoPt = min(228 / $medidasFotoPt[0], 148 / $medidasFotoPt[1]);
                                        $anchoFotoPt = $medidasFotoPt[0] * $escalaFotoPt;
                                        $altoFotoPt = $medidasFotoPt[1] * $escalaFotoPt;
                                    }
                                @endphp
                                <td>
                                    <div class="foto-marco">
                                        @if(!empty($fotoPt['path']) && is_file($fotoPt['path']))
                                            <img src="{{ $fotoPt['path'] }}" @if($medidasFotoPt) class="foto-contenida" style="width:{{ $anchoFotoPt }}pt; height:{{ $altoFotoPt }}pt; top:{{ (150 - $altoFotoPt) / 2 }}pt;" @endif alt="">
                                        @else
                                            <img class="foto-vacia" alt="" src="{{ public_path('images/Reportes17025/PT/foto-vacia.png') }}">
                                        @endif
                                    </div>
                                </td>
                            @endfor
                        </tr>
                        <tr class="fila-rotulo">
                            @for($colFotoPt = 0; $colFotoPt < 2; $colFotoPt++)
                                @php
                                    $indiceFotoPt = $filaFotoPt * 2 + $colFotoPt;
                                @endphp
                                <td>
                                    <div class="contenido-rotulo" style="height:{{ 7 + max(1, $lineasFilasFotosPt[$filaFotoPt]) * 6.48 }}pt">
                                        <div class="foto-rotulo">
                                            @if($rotulosFotosPt[$indiceFotoPt] !== '')
                                                <div class="foto-comentario">{!! $rotulosFotosPt[$indiceFotoPt] !!}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </table>
                <div class="seccion">Observaciones <em>(Remarks)</em></div>
                <div class="observaciones">{!! $textoFijoP1($d['Observaciones_Fotos'] ?? $d['Observaciones'] ?? '', 502, 63, 6, 'Observaciones fotográficas') !!}</div>
            </div>
            {!! $firmasPt(true) !!}{!! $piePt(4) !!}
        </div>
    @endforeach
</body>
</html>
