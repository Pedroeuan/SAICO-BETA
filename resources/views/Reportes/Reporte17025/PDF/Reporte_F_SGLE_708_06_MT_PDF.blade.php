{{-- Preparacion de datos y funciones del documento --}}
@php
    $datosMedicionHoja2Pt = get_defined_vars();
    /* Referencia: F-SGLE-708-02 (PT) VER. 1, hojas PAG.1 a PAG.4 del Excel original. */
    $g = $Detalles_Generales ?? [];
    $d = $Datos_Equipo ?? [];
    $soloFotosPt = $soloFotosPt ?? false;
    // Compatibilidad con llamadas que renderizan la plantilla directamente.
    if (!$soloFotosPt && !isset($paginasTextoHoja2Pt)) {
        $paginasTextoHoja2Pt = \App\Http\Controllers\Reporte\Reporte17025\F_SGLE_708_02_PTController::paginasTextoHoja2Pt($d);
        if (!$paginasTextoHoja2Pt[0]['conItems']) {
            $totalPaginasPt += count($paginasTextoHoja2Pt);
        }
    }
    $escPt = fn($v) => e(is_scalar($v) ? (string) $v : '');
    $textoPt = fn($v) => nl2br($escPt($v), false);
    $leyendaPt = function ($es, $en = '') use ($escPt) {
        return '<strong>' . $escPt($es) . '</strong>' . ($en !== '' ? '<br><em>' . $escPt($en) . '</em>' : '');
    };
    $valorPt = fn($key) => $g[$key] ?? '';
    $fechaPt = function ($key) use ($g) {
        if (empty($g[$key])) {
            return '';
        }
        try {
            return \Carbon\Carbon::parse($g[$key])->format('d/m/Y');
        } catch (\Throwable $e) {
            return $g[$key];
        }
    };
    $declaracionExcel1Pt = '*Este informe es único y exclusivamente válido en su estado original y completo; contando con las firmas de los responsables.
*Los procedimientos utilizados y resultados obtenidos en esta prueba se basan en el cumplimiento de la norma NMX-EC-17025-IMNC-2018.
*Los resultados emitidos se relacionan solamente con el ITEM descrito en el presente informe, no aplica a items similares.
*Los resultados se expresan de acuerdo con la NOM-008-SCFI-NOM-008-SE-2021.
*La incertidumbre de medidaq se estimó en base a la norma NMX-CH-140-IMNC-2002 "Guía para la evaluación de la incertidumbre de medida en los resultados de las mediciones".
*Se expresara la incertidumbre de medida cuando: 
   -Los resultados se encuentren cercanos a los limites de los criterios de aceptación;o
   -El cliente lo requiera;
*La incertidumbre del laboratorio será informada con un factor de cobertura k= 2 , es decir con un 95.45% de confianza.
*La regla de decisión declarada por el laboratorio es; No binaria con zona de seguridad, tomando como referencia ILAC-G8:09/2019 para su selección.
*La regla de decisión sera aplicada a los resultados de la medicón para determinar si se encuentran fuera o dentro de los criterios de aceptación. 
*Cuando se menciona en el informe la fecha próxima de la siguiente prueba, es a solicitud del cliente,';
    $declaracionExcel2Pt = \App\Http\Controllers\Reporte\Reporte17025\F_SGLE_708_02_PTController::declaracionPredeterminadaPt();
    $piesExcelPt = array (
  1 => 'Asesoría e Inspección en Construcción Costa Fuera S.C.
Calle 60 Núm. Exterior 75 entre Calle 33 A y Calle 60, Col. Fatima, C.P. 24110, Ciudad del Carmen, Campeche.
Teléfono: 938-197-8815     Whatsapp: 938 103 4445 
Correo: Aclientes-Lab@aicosc.com/ jorge.moo@aicosc.com  
Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO, S.C., Documento Confidencial.',
  2 => 'Asesoría e Inspección en Construcción Costa Fuera S.C.
Calle 60 Núm. Exterior 75 entre Calle 33 A y Calle 60, Col. Fatima, C.P. 24110, Ciudad del Carmen, Campeche.
Teléfono: 938-197-8815     Whatsapp: 938 103 4445 
Correo: Aclientes-Lab@aicosc.com/ jorge.moo@aicosc.com 
Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO, S.C., Documento Confidencial.
',
  3 => 'Asesoría e Inspección en Construcción Costa Fuera S.C.
Calle 60 Núm. Exterior 75 entre Calle 33 A y Calle 60, Col. Fatima, C.P. 24110, Ciudad del Carmen, Campeche.
Teléfono: 938-197-8815     Whatsapp: 938 103 4445 
Correo: Aclientes-Lab@aicosc.com/jorge.moo@aicosc.com  
Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO, S.C., Documento Confidencial.',
  4 => 'Asesoría e Inspección en Construcción Costa Fuera S.C.
Calle 60 Núm. Exterior 75 entre Calle 33 A y Calle 60, Col. Fatima, C.P. 24110, Ciudad del Carmen, Campeche.
Teléfono: 938-197-8815     Whatsapp: 938 103 4445 
Correo: Aclientes-Lab@aicosc.com /jorge.moo@aicosc.com  
Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO, S.C., Documento Confidencial.',
);
    $declaracionUsuarioPt = $d['DECLARACION_RESULTADOS'] ?? '';
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
            $html .= '<td><div class="nombre-firma">' . $textoFijoP1($firmaDatosPt[$nombre] ?? '', $anchoFirma, 10, 6, 'Nombre de firma') . '</div><div class="espacio-firma"></div><strong>' . $textoFijoP1($firmaDatosPt[$rol] ?? '', $anchoFirma, 10, 6, 'Rol de firma') . '</strong><span>' . $textoFijoP1($firmaDatosPt[$cargo] ?? '', $anchoFirma, 10, 6, 'Cargo de firma') . '</span></td>';
        }
        return $html . '</tr></table>';
    };
    $cabeceraPt = function ($pagina) use ($escPt, $g, $totalPaginasPt, $recursosFormatoPt) {
        return '<div class="cabecera-pt"><img class="banda-superior" src="' . $recursosFormatoPt['superior'] . '"><img class="logo-aico" src="' . $recursosFormatoPt['aico'] . '"><div class="titulo-informe">Informe de Ensayo<br><em>Testing report</em><br><span>Liquidos Penetrantes</span><br><em>Liquid Penetrant</em></div><img class="logo-ema" src="' . $recursosFormatoPt['ema'] . '"><div class="identificacion"><div class="codigo"><div class="numero-pagina-pt">Pág. ' . $pagina . ' de ' . $totalPaginasPt . '</div>F-SGLE-708-02 (PT) VER. 1</div><div class="numero-box"><div class="numero-label">No. de Informe:<br><em>Number of report:</em></div><div class="numero-informe">' . $escPt($g['No_Reporte'] ?? '') . '</div></div></div></div>';
    };
    $piePt = function ($hojaExcel) use ($textoPt, $piesExcelPt, $recursosFormatoPt) {
        return '<div class="pie-pt">' . $textoPt($piesExcelPt[$hojaExcel]) . '</div><img class="banda-inferior" src="' . $recursosFormatoPt['inferior'] . '">';
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

    // Campos abiertos de PAG.1: etiquetas bilingües y valores subrayados, sin cajas internas.
    $lineaCampoP12 = function ($es, $en, $valor, $izquierda, $arriba, $ancho, $anchoEtiqueta, $alto, $unidad = '', $inicioValor = null, $condicion = false) use ($leyendaPt, $textoPt, $escPt, $textoFijoP1) {
        $anchoUnidad = $unidad === '' ? 0 : ($unidad === 'µW/cm²' ? 29 : 16);
        $inicioValor = $inicioValor ?? $anchoEtiqueta;
        $anchoValor = $ancho - $inicioValor - $anchoUnidad - 3;
        $derechaCliente = in_array($es, ['Contacto del cliente', 'Trazabilidad del cliente'], true);
        $izquierdaCliente = $inicioValor === 48 && in_array($es, ['Nombre', 'Dirección', 'Orden de trabajo', 'Proyecto', 'Partida'], true);
        $datoAico = in_array($es, ['Contrato', 'Orden de trabajo AICO (OT)'], true);
        $datoFecha = in_array($es, ['Fecha de recepción', 'Fecha de Ensayo', 'Fecha de emisión'], true);
        $anchoTextoValor = $derechaCliente ? 81 : $anchoValor - 5;
        $altoValor = $derechaCliente ? $alto - 2 : $alto - 1;
        $textoValor = $textoFijoP1($valor, $anchoTextoValor, $datoAico ? PHP_INT_MAX : $altoValor - 1, 6, $es);
        if ($derechaCliente || $izquierdaCliente || $datoAico || $datoFecha || $condicion) {
            $altoValor = (substr_count($textoValor, '<br>') + 1) * 6.48 + 1.55;
        }
        $posicionEtiqueta = ($derechaCliente || $izquierdaCliente || $datoAico || $datoFecha || $condicion) ? 'top:' . (($alto - 12.96) / 2) . 'pt;' : '';
        $posicionValor = ($derechaCliente || $izquierdaCliente || $datoAico || $datoFecha || $condicion) ? 'top:' . (($alto - $altoValor) / 2) . 'pt;' : '';
        $posicionUnidad = $condicion ? 'bottom:' . (($alto - $altoValor) / 2 + 1) . 'pt;' : '';
        return '<div class="campo-abierto-p12" style="left:' . $izquierda . 'pt;top:' . $arriba . 'pt;width:' . $ancho . 'pt;height:' . $alto . 'pt;">'
            . '<div class="etiqueta-abierta-p12" style="width:' . ($anchoEtiqueta - 3) . 'pt;' . $posicionEtiqueta . '">' . $leyendaPt($es, $en) . '</div>'
            . '<div class="valor-abierto-p12" style="left:' . $inicioValor . 'pt;width:' . $anchoValor . 'pt;height:' . $altoValor . 'pt;' . $posicionValor . '">' . $textoValor . '</div>'
            . ($unidad !== '' ? '<span class="unidad-p12" style="width:' . $anchoUnidad . 'pt;' . $posicionUnidad . '">' . $escPt($unidad) . '</span>' : '')
            . '</div>';
    };

@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>F-SGLE-708-02 (PT) — {{ $g['No_Reporte'] ?? '' }}</title>
    {{-- Bloque de estilos del documento --}}
    <style>
        /* Página, cabecera y estructura general. */
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
        .pagina-uno-pt .cuerpo-pt {
            width:509.9527pt;
        }
        .pagina-tres-pt .cuerpo-pt {
            width:509.9527pt;
        }
        .pagina-dos-pt .cuerpo-pt {
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
        /* Tablas y componentes generales. */
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
        .bloque {
            margin-bottom:4pt;
        }
        .datos td {
            border:0.45pt solid #111;
            height:12pt;
        }
        .datos .etiqueta {
            font-size:6pt;
        }
        .datos .etiqueta em {
            font-weight:normal;
        }
        .valor {
            font-size:6pt;
        }
        .gris {
            background:#d9d9d9;
        }
        .rejilla th, .rejilla td {
            border:0.55pt solid #000;
            text-align:center;
        }
        .rejilla th {
            background:#d9d9d9;
            font-size:6pt;
            padding:2pt;
        }
        .rejilla td {
            font-size:6pt;
            height:11.5pt;
            padding:1.5pt 2pt;
        }
        .mini th, .mini td {
            font-size:6pt;
            height:10pt;
            padding:1pt 2pt;
        }
        .declaracion {
            border:0.5pt solid #111;
            padding:3pt 4pt;
            font-size:6pt;
            line-height:1.12;
            text-align:left;
        }
        .declaracion.pagina-uno {
            font-size:6pt;
            line-height:1.04;
        }
        .adiciones {
            border:0.5pt solid #111;
            min-height:22pt;
            padding:4pt;
            white-space:normal;
        }
        .items th {
            height:27pt;
        }
        .items td {
            height:15pt;
        }
        .resultados th {
            font-size:6pt;
            height:15pt;
        }
        .resultados td {
            height:13.3pt;
            font-size:6pt;
            padding:1pt 2pt;
        }
        .resultados .texto-grupo {
            text-align:left;
            font-weight:bold;
            background:#f1f1f1;
        }
        .notas {
            font-size:6pt;
            padding:3pt;
            white-space:normal;
        }
        .observaciones {
            border:0.5pt solid #111;
            min-height:19pt;
            padding:3pt;
            font-size:6pt;
        }
        /* Firmas y pie de página. */
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
        .pagina-uno-pt .firmas-pt {
            top:642.8583pt;
        }
        .pagina-dos-pt .bloque-p12 {
            margin-bottom:4pt;
        }
        .pagina-dos-pt .items-p12 td {
            height:auto;
            min-height:12pt;
            padding:2pt 2pt;
        }
        .pagina-dos-pt .consumibles-p12 .rejilla td {
            height:auto;
            padding:2pt 2pt;
        }
        .pagina-dos-pt .items-p12 tr, .pagina-dos-pt .consumibles-p12 tr {
            page-break-inside:avoid;
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
        /* Liberar espacio real antes de las firmas sin recortar datos de la última sección. */
        .pagina-uno-pt .cliente-abierto-p12 {
            height:96pt;
        }
        .pagina-uno-pt .fechas-aico-p12 {
            height:66pt;
        }
        .pagina-uno-pt .equipos-p12 .rejilla td {
            height:13pt;
        }
        .pagina-uno-pt .bloque-p12 {
            margin-bottom:3pt;
        }
        /* Fotografías y ajustes compartidos de las primeras hojas. */
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
        .compacto {
            font-size:6pt;
        }
        /* Ajuste visual de PAG.1 y PAG.2 conforme a las capturas del Excel. PAG.3 y PAG.4 no se alteran. */
        .pagina-uno-pt .cuerpo-pt, .pagina-dos-pt .cuerpo-pt {
            font-size:6pt;
            line-height:1.14;
        }
        .pagina-uno-pt .seccion, .pagina-dos-pt .seccion {
            font-size:6pt;
            padding:2.2pt 1pt;
            line-height:1.08;
        }
        .pagina-uno-pt .seccion em, .pagina-dos-pt .seccion em {
            font-size:6pt;
        }
        .bloque-p12 {
            margin-bottom:4.5pt;
        }
        .campo-abierto-p12 {
            position:absolute;
        }
        .etiqueta-abierta-p12 {
            position:absolute;
            left:0;
            top:1pt;
            font-size:6pt;
            line-height:1.08;
        }
        .etiqueta-abierta-p12 em {
            font-size:6pt;
            font-weight:normal;
            line-height:1.05;
        }
        .valor-abierto-p12 {
            position:absolute;
            top:1pt;
            height:12pt;
            border-bottom:0.55pt solid #111;
            padding:0 2pt 1pt;
            font-size:6pt;
            line-height:1.08;
            text-align:center;
            overflow-wrap:break-word;
        }
        .valor-abierto-p12 .texto-ajustado-p1 {
            position:absolute;
            bottom:1pt;
            left:2pt;
            right:2pt;
        }
        .unidad-p12 {
            position:absolute;
            right:0;
            bottom:2pt;
            font-size:6pt;
        }
        .cliente-abierto-p12 {
            position:relative;
            height:111pt;
        }
        /* PAG.1: marco exterior como en el Excel, sin cuadrículas internas. */
        .pagina-uno-pt .cliente-abierto-p12 {
            border:0.6pt solid #111;
            border-top:0;
        }
        .pagina-uno-pt .cliente-abierto-p12 > .campo-abierto-p12 > .etiqueta-abierta-p12 {
            width:45pt !important;
            text-align:right;
        }
        /* Datos del cliente: como PAG.1 del Excel, etiqueta bilingüe centrada
           en la mitad izquierda de cada campo derecho y línea del valor a la derecha. */
        .cliente-derecha-p12 {
            position:absolute;
            left:340pt;
            top:0;
            width:168pt;
            height:96pt;
        }
        .cliente-derecha-p12 .campo-abierto-p12 {
            width:168pt !important;
        }
        .cliente-derecha-p12 .etiqueta-abierta-p12 {
            left:0 !important; width:73pt !important;
            text-align:right !important; line-height:1.08;
        }
        .cliente-derecha-p12 .valor-abierto-p12 {
            left:76pt !important; width:87pt !important;
            padding:0 2pt 1pt !important;
            text-align:center !important;
        }
        /* En DomPDF, anclar por arriba el texto y no depender de bottom absoluto. */
        .cliente-derecha-p12 .valor-abierto-p12 .texto-ajustado-p1 {
            position:static;
        }
        .pagina-uno-pt .cliente-abierto-p12 > .campo-abierto-p12 .texto-ajustado-p1 {
            position:static;
        }

        .fechas-aico-p12 {
            position:relative;
            height:64pt;
        }
        .fechas-p12 {
            position:absolute;
            left:0;
            top:0;
            width:344pt;
        }
        .fechas-p12 .etiqueta-abierta-p12 {
            width:62pt !important;
            text-align:center;
        }
        .fechas-p12 .campo-abierto-p12:nth-child(2) .etiqueta-abierta-p12 {
            width:52pt !important;
        }
        .aico-p12 {
            position:absolute;
            right:0;
            top:0;
            width:154pt;
        }
        .pagina-uno-pt .fechas-p12 {
            width:310pt;
        }
        .pagina-uno-pt .aico-p12 {
            width:196pt;
        }
        .pagina-uno-pt .fechas-p12 .campos-fechas-p12,
        .pagina-uno-pt .aico-p12 .campos-fechas-p12 {
            height:60pt;
        }
        .pagina-uno-pt .fechas-p12 .campo-abierto-p12 {
            margin-top:5pt;
        }
        .pagina-uno-pt .aico-p12 .etiqueta-abierta-p12 {
            text-align:right;
        }
        .pagina-uno-pt .aico-p12 .valor-abierto-p12 {
            overflow:visible;
        }
        .pagina-uno-pt .aico-p12 .texto-ajustado-p1 {
            position:static;
        }
        .borde-p12 {
            border:0.6pt solid #111;
        }
        .borde-p12 .seccion {
            border:0;
            border-bottom:0.6pt solid #111;
        }
        .campos-fechas-p12 {
            position:relative;
            height:50pt;
        }
        .equipos-p12 .rejilla th {
            font-size:6pt;
            height:20pt;
            padding:1pt 2pt;
            vertical-align:middle;
        }
        .equipos-p12 .rejilla td {
            font-size:6pt;
            font-style:normal;
            font-weight:normal;
            height:14pt;
            padding:1pt 2pt;
            vertical-align:middle;
        }
        .condiciones-abiertas-p12 {
            position:relative;
            height:72pt;
            border:0.6pt solid #111;
            border-top:0;
            padding:4pt 6pt;
        }
        .condiciones-abiertas-p12 .etiqueta-abierta-p12 {
            text-align:right;
        }
        .referencias-abiertas-p12 {
            position:relative;
            height:76pt;
            border:0.6pt solid #111;
            border-top:0;
            padding:4pt 6pt;
        }
        .referencias-abiertas-p12 .etiqueta-abierta-p12 {
            text-align:right;
        }
        .declaracion-abierta-p12 {
            border:0.6pt solid #111;
            border-top:0;
            padding:3pt 5pt;
            font-size:6pt;
            line-height:1.13;
            height:auto;
            min-height:0;
        }
        .declaracion-abierta-p12 .texto-ajustado-p1 {
            line-height:7.48pt;
        }
        .declaracion-abierta-p12 p {
            margin:0 0 1pt;
            padding:0;
            text-align:left;
        }
        .declaracion-abierta-p12 p:last-child {
            margin-bottom:0;
        }
        .adiciones-abiertas-p12 {
            border:0.6pt solid #111;
            border-top:0;
            min-height:0;
            padding:2pt 5pt;
            font-size:6pt;
            line-height:1.13;
            white-space:normal;
            overflow-wrap:break-word;
        }
        .items-p12 th {
            height:26pt;
            font-size:6pt;
            padding:2pt 2pt;
        }
        .items-p12 td {
            height:12.1pt;
            font-size:6pt;
            padding:1pt 2pt;
        }
        .consumibles-p12 .rejilla th {
            font-size:6pt;
            padding:1pt 2pt;
        }
        .consumibles-p12 .rejilla thead th {
            height:24pt;
        }
        .consumibles-p12 .rejilla td {
            font-size:6pt;
            height:13pt;
            padding:2pt 2pt;
        }
        .consumibles-p12 .nombre-tiempo-p12 {
            font-size:6pt;
            font-style:normal;
            font-weight:bold;
        }
        .consumibles-p12 .celda-tiempo-titulo-p12 {
            text-align:center;
            line-height:1.08;
        }
        .consumibles-p12 .celda-tiempo-valor-p12 {
            text-align:center;
            white-space:normal;
        }
        .consumibles-p12 .diagonal-p12 {
            position:relative;
            padding:0 !important;
        }
        .diagonal-p12 img {
            display:block;
            width:100%;
            height:21pt;
        }
        /* Evitar divisiones de filas y cortes de celdas en DomPDF. */
        .pagina-uno-pt .rejilla tr, .pagina-dos-pt .rejilla tr {
            page-break-inside:avoid;
        }
        .pagina-uno-pt .campo-abierto-p12, .pagina-dos-pt .campo-abierto-p12 {
            page-break-inside:avoid;
        }
        /* Respetar ancho interior del formato sin forzar una escala global. */
        .pagina-uno-pt .seccion, .pagina-dos-pt .seccion {
            background-color:#255d98;
        }

        /* PAG.1: cada sección tiene una coordenada independiente del contenido. */
        .pagina-uno-pt .cuerpo-pt > .bloque-p12 {
            position:absolute;
            left:0;
            width:509.9527pt;
        }
        .pagina-uno-pt .cuerpo-pt > .bloque-p12:nth-child(1) {
            top:0;
            height:110.9536pt;
        }
        .pagina-uno-pt .cuerpo-pt > .bloque-p12:nth-child(2) {
            top:113.9536pt;
            height:66pt;
        }
        .pagina-uno-pt .cuerpo-pt > .bloque-p12:nth-child(3) {
            top:192.9536pt;
            height:102.67972pt;
        }
        .pagina-uno-pt .cuerpo-pt > .bloque-p12:nth-child(4) {
            top:298.63332pt;
            height:94.9536pt;
        }
        .pagina-uno-pt .cuerpo-pt > .bloque-p12:nth-child(5) {
            top:396.58692pt;
            height:98.9536pt;
        }
        .pagina-uno-pt .valor-abierto-p12 {
            overflow:hidden;
        }
        .texto-ajustado-p1 {
            display:block;
            white-space:pre;
            font-size:6pt;
            line-height:6.48pt;
        }
        .pagina-uno-pt .equipos-p12 .rejilla td {
            height:10pt;
            padding:0 2pt;
        }
        .pagina-uno-pt .equipo-valor-fijo-p1 {
            line-height:1.08;
        }
        /* En PAG.2 los bloques crecen en flujo; las firmas permanecen fijas. */
        .pagina-dos-pt .items-p12 td {
            height:12pt;
            padding:1pt 2pt;
        }
        .pagina-dos-pt .consumibles-p12 .rejilla td {
            height:16pt;
            padding:1pt 2pt;
        }
        .pagina-tres-pt .resultados td {
            height:11pt;
        }
        .pagina-tres-pt .resultados .resumen-resultados-pt th,
        .pagina-tres-pt .resultados .resumen-resultados-pt td {
            height:8pt;
            padding:0 2pt;
        }
        .pagina-tres-pt .resultados .resumen-resultados-pt .espacio-resumen-pt {
            border-color:#fff;
        }
        .pagina-tres-pt .resultados .resumen-resultados-pt th:first-of-type {
            border-left:0.7pt solid #000;
        }
        .pagina-tres-pt .cuerpo-pt > .bloque {
            position:absolute;
            top:0;
            width:100%;
        }
        /* DomPDF suma padding y borde al ancho/alto declarado de estos bloques. */
        .pagina-tres-pt .posterior-resultados-pt {
            position:absolute;
            top:350pt;
            width:100%;
        }
        .pagina-tres-pt .conformidad-resultados-pt {
            margin-top:5pt;
        }
        .pagina-tres-pt .datos td {
            height:auto;
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
        /* Como la cuadrícula general de caracterización 04_03: 8px a 96dpi. */
        .cuerpo-pt .rejilla th, .cuerpo-pt .rejilla td,
        .cuerpo-pt .datos td, .cuerpo-pt .datos .etiqueta,
        .consumibles-p12 .nombre-tiempo-p12 {
            font-size:6pt;
        }
        .cuerpo-pt .rejilla td, .cuerpo-pt .datos td {
            font-weight:normal;
        }
        .cuerpo-pt .rejilla th, .cuerpo-pt .rejilla .texto-grupo,
        .consumibles-p12 .nombre-tiempo-p12 {
            font-weight:bold;
        }
        /* Composición de las hojas de resultados y fotografías del formato Excel. */
        .pagina-dos-pt .declaracion-abierta-p12 {
            padding:3pt 2pt;
        }
        .pagina-dos-pt .adiciones-abiertas-p12 {
            padding:2pt;
        }
        .pagina-dos-pt .items-p12 th, .pagina-dos-pt .consumibles-p12 th {
            background:#d9d9d9;
        }
        .pagina-tres-pt .observaciones {
            min-height:0;
            height:auto;
            text-align:center;
        }
        .pagina-tres-pt .datos .etiqueta {
            background:#d9d9d9;
            text-align:center;
        }
        .pagina-tres-pt .datos td:not(.etiqueta) {
            text-align:center;
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
@if(!$soloFotosPt)
    @php $paginaPt = 0; @endphp
    @foreach($paginasEquiposPt as $equiposPaginaPt)
        @php $paginaPt++; @endphp
        <div class="hoja-pt pagina-uno-pt">
            {!! $marcaPt !!}{!! str_replace('Liquidos Penetrantes', 'Líquidos Penetrantes', $cabeceraPt($paginaPt)) !!}
            <div class="cuerpo-pt">
                {{-- PAG.1: campos abiertos y combinaciones de la hoja de referencia. --}}
                <section class="bloque-p12">
                    <div class="seccion">Datos del cliente <em>(Customer information)</em></div>
                    <div class="cliente-abierto-p12">
                        {!! $lineaCampoP12('Nombre', 'Name', $valorPt('Cliente'), 5, 0, 333, 96, 18, '', 48) !!}
                        {!! $lineaCampoP12('Dirección', 'Address', $valorPt('Direccion'), 5, 19, 333, 96, 19, '', 48) !!}
                        {!! $lineaCampoP12('Orden de trabajo', 'Work Order', $valorPt('Orden_Trabajo'), 5, 38, 333, 96, 19, '', 48) !!}
                        {!! $lineaCampoP12('Proyecto', 'Project', $valorPt('Proyecto'), 5, 57, 333, 96, 19, '', 48) !!}
                        {!! $lineaCampoP12('Partida', 'Entry', $valorPt('Partida'), 5, 76, 333, 96, 19, '', 48) !!}
                        <div class="cliente-derecha-p12">
                            {!! $lineaCampoP12('Contacto del cliente', "Customer's contact", $valorPt('Contacto'), 0, 0, 158, 76, 39) !!}
                            {!! $lineaCampoP12('Trazabilidad del cliente', 'Customer traceability', $valorPt('Trazabilidad'), 0, 48, 158, 76, 44) !!}
                        </div>
                    </div>
                </section>
                <div class="fechas-aico-p12 bloque-p12">
                    <section class="fechas-p12 borde-p12">
                        <div class="seccion">Fechas <em>(Dates)</em></div>
                        <div class="campos-fechas-p12">
                            {!! $lineaCampoP12('Fecha de recepción', 'Reception date', $fechaPt('Fecha_Recepcion'), 4, 2, 158, 89, 18, '', 65) !!}
                            {!! $lineaCampoP12('Fecha de Ensayo', 'Test date', $fechaPt('Fecha_Ensayo'), 166, 2, 140, 72, 18, '', 55) !!}
                            {!! $lineaCampoP12('Fecha de emisión', 'Issue date', $fechaPt('Fecha_Emision'), 4, 24, 158, 89, 19, '', 65) !!}
                        </div>
                    </section>
                    <section class="aico-p12 borde-p12">
                        <div class="seccion">Datos AICO <em>(AICO information)</em></div>
                        <div class="campos-fechas-p12">
                            {!! $lineaCampoP12('Contrato', 'Contract', $valorPt('Contrato'), 4, 1, 187, 74, 18) !!}
                            {!! $lineaCampoP12('Orden de trabajo AICO (OT)', 'Work Order AICO', $valorPt('OT_AICO'), 4, 20, 187, 74, 40) !!}
                        </div>
                    </section>
                </div>
                <section class="bloque-p12 equipos-p12">
                    <div class="seccion">Equipos de medición utilizados <em>(Measurement equipment used)</em></div>
                    {{-- Datos generales e inspeccion --}}
                    <table class="rejilla">
                        @php
                            $controladorEquiposPt = \App\Http\Controllers\Reporte\Reporte17025\F_SGLE_708_02_PTController::class;
                            $titulosEquiposPt = ['DESCRIPCION'=>'Descripción', 'MARCA'=>'Marca', 'MODELO'=>'Modelo', 'NO_SERIE'=>'No. de serie', 'TRAZABILIDA'=>'Trazabilidad metrológica', 'REFERENCIA'=>'Referencia', 'NO_CERTIFICADO'=>'No. de certificado'];
                        @endphp
                        <thead>
                            <tr>
                                @foreach($titulosEquiposPt as $campoEquipoPt => $tituloEquipoPt)
                                    <th style="width:{{ $controladorEquiposPt::ANCHOS_EQUIPOS_PT[$campoEquipoPt] }}%">{{ $tituloEquipoPt }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equiposPaginaPt as $iPt => $equipoPaginaPt)
                                <tr>
                                    @foreach($titulosEquiposPt as $campoEquipoPt => $tituloEquipoPt)
                                        <td><div class="equipo-valor-fijo-p1">{!! $textoFijoP1($equipoPaginaPt[$campoEquipoPt] ?? '', $controladorEquiposPt::anchoTextoEquipoPt($campoEquipoPt), PHP_INT_MAX, 6, 'Equipo: ' . $campoEquipoPt) !!}</div></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
                <section class="bloque-p12">
                    <div class="seccion">Condiciones de la prueba <em>(Test conditions)</em></div>
                    <div class="condiciones-abiertas-p12">
                        {!! $lineaCampoP12('Lugar de ensayo', 'Testing place', $valorPt('Lugar'), 6, 6, 326, 49, 18, '', null, true) !!}
                        {!! $lineaCampoP12('Tipo de material', 'Type of material', $valorPt('Material'), 345, 6, 152, 50, 18, '', null, true) !!}
                        {!! $lineaCampoP12('Tipo de luz', 'Type of light', $valorPt('Tipo_luz'), 6, 31, 140, 38, 23, '', null, true) !!}
                        {!! $lineaCampoP12('Intensidad luminosa', 'Light intensity', $valorPt('Intensidad'), 156, 31, 174, 58, 23, 'Lx', null, true) !!}
                        {!! $lineaCampoP12('Condición de la superficie', 'Surface condition', $valorPt('Condicion_superficie'), 345, 31, 152, 74, 23, '', null, true) !!}
                        {!! $lineaCampoP12('Temperatura ambiental', 'Environment temperature', $valorPt('Temperatura_ambiental'), 6, 52, 162, 76, 26, '°C', null, true) !!}
                        {!! $lineaCampoP12('Irradiancia', 'Irradiance', $valorPt('Irradiancia'), 181, 52, 134, 36, 26, 'µW/cm²', null, true) !!}
                        {!! $lineaCampoP12('Temperatura de la superficie del material', 'Surface material temperature', $valorPt('Temperatura_material'), 320, 50, 177, 111, 30, '°C', null, true) !!}
                    </div>
                </section>
                <section class="bloque-p12">
                    <div class="seccion">Referencias y Método <em>(References and method)</em></div>
                    <div class="referencias-abiertas-p12">
                        {!! $lineaCampoP12('Procedimiento', 'procedure', $valorPt('Procedimiento'), 6, 6, 497, 47, 18, '', null, true) !!}
                        {!! $lineaCampoP12('Ensayo realizado', 'Testing executed', $valorPt('Ensayo_realizado'), 6, 30, 332, 57, 23, '', null, true) !!}
                        {!! $lineaCampoP12('Tipo y Método', 'Type and Method', $valorPt('Tipo_metodo'), 345, 30, 158, 53, 23, '', null, true) !!}
                        {!! $lineaCampoP12('Código y/o estándar utilizado', 'Code and/or standard used', $valorPt('Codigo_estandar'), 6, 54, 332, 92, 24, '', null, true) !!}
                        {!! $lineaCampoP12('Criterio de aceptación', 'Acceptance criteria', $valorPt('Criterio_aceptacion'), 345, 54, 158, 70, 24, '', null, true) !!}
                    </div>
                </section>
            </div>
            {!! $firmasPt(true) !!}{!! $piePt(1) !!}
        </div>
    @endforeach
    @php
        $hojas2Pt = [];
        if (!$paginasTextoHoja2Pt[0]['conItems']) {
            foreach ($paginasTextoHoja2Pt as $textoPaginaPt) {
                $hojas2Pt[] = ['texto' => $textoPaginaPt, 'items' => null];
            }
        }
        foreach ($paginasItemsPt as $itemsPaginaPt) {
            $hojas2Pt[] = ['items' => $itemsPaginaPt, 'texto' => $paginasTextoHoja2Pt[0]['conItems'] ? $paginasTextoHoja2Pt[0] : []];
        }
    @endphp
    @foreach($hojas2Pt as $hoja2Pt)
        @php
            $paginaPt++;
            $textoPaginaPt = $hoja2Pt['texto'];
            $itemsPaginaPt = $hoja2Pt['items'];
        @endphp
        <div class="hoja-pt pagina-dos-pt">
            {!! $marcaPt !!}{!! str_replace('Liquidos Penetrantes', 'Líquidos Penetrantes', $cabeceraPt($paginaPt)) !!}
            <div class="cuerpo-pt">
                @if(array_key_exists('declaracion', $textoPaginaPt))
                <section class="bloque-p12">
                    <div class="seccion">Declaración de los resultados <em>(Statement of results)</em></div>
                    <div class="declaracion-abierta-p12">
                        {!! $textoFijoP1($textoPaginaPt['declaracion'], 498, PHP_INT_MAX, 6) !!}
                    </div>
                </section>
                @endif
                @if(array_key_exists('adiciones', $textoPaginaPt))
                <section class="bloque-p12">
                    <div class="seccion">Adiciones, desviaciones o exclusiones del método <em>(Additions, deviations or exclusions of the method)</em></div>
                    <div class="adiciones-abiertas-p12">{!! $textoFijoP1($textoPaginaPt['adiciones'], 498, PHP_INT_MAX, 6) !!}</div>
                </section>
                @endif
                @if(is_array($itemsPaginaPt))
                <section class="bloque-p12 items-p12">
                    <div class="seccion">Datos del/los Item/s a Inspeccionar <em>(information about of item to testing)</em></div>
                    <table class="rejilla">
                        <thead><tr><th style="width:6%">No.<br><em>Number</em></th><th style="width:19%">ITEM / identificación</th><th style="width:14%">Id. AICO</th><th style="width:17%">Marca / Modelo / No. Serie<br><em>Brand/Model/Serial Number</em></th><th style="width:12%">Colada<br><em>Heat id</em></th><th style="width:18%">Trazabilidad del cliente<br><em>Customer traceability</em></th><th style="width:14%">Isométrico / Plano<br><em>(Isometric / Drawing)</em></th></tr></thead>
                        <tbody>
                            {{-- Mostrar sólo los ítems reales de la página; sin completar con filas ficticias. --}}
                            @foreach($itemsPaginaPt as $iPt => $filaItemPt)
                                @if(is_array($filaItemPt))
                                    <tr>
                                        @foreach(['No','Item','Id_aico','MMN','Colada','Trazabilidad','Isometrico'] as $campoItemPt)
                                            <td>{!! $textoFijoP1($filaItemPt[$campoItemPt] ?? ($campoItemPt === 'No' ? $iPt + 1 : ''), 509.9527 * ['No'=>6,'Item'=>19,'Id_aico'=>14,'MMN'=>17,'Colada'=>12,'Trazabilidad'=>18,'Isometrico'=>14][$campoItemPt] / 100 - 5, 9, 6, 'Ítem ' . ($loop->parent->parent->index * 10 + $iPt + 1) . ': ' . $campoItemPt) !!}</td>
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </section>
                <section class="bloque-p12 consumibles-p12" data-fin-hoja2-pt="1">
                    <div class="seccion">Consumibles utilizados <em>(Consumables used)</em></div>
                    <table class="rejilla">
                        <thead><tr>
                            <th class="diagonal-p12" style="width:15%"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAABaCAYAAAACcWsdAAAACXBIWXMAAA7EAAAOxAGVKw4bAAACcklEQVR4nO3YSW7kMBBFQQro+19Z3rgNuVxSaeCQJCNOwNXDTy7ruiaASJZleRumf7UfAvDOXqRSSmld1yUlwQIa2ovU/0C9EiygmjMr6ohgAUU9jdSWYAHZXT31zhIs4LGcK+qIYAG31IrUlmABp5U69c4SLGBXixV1RLCAX6JFakuwgOan3lmCBROKvKKOCBZMotdIbQkWDGqEQL0SLBjIiJHaEizoXC8f5jkIFnRm9BV1RLCgAzNHakuwIKiZTr2zBAuCsKI+EyxoSKSuESyozKl3n2BBYVZUPoIFBYhUGYIFGQhUHYIFN4lUfYIFF/gwb0uw4IAVFYtgwQuRikuwIDn1eiFYTMmK6pNgMQ2R6p9gMTSn3lgEi6FYUWMTLLonUvMQLLrk1JuTYNEFK4qUBIvARIpXgkUYAsUngkVTIsUVgkV1Psy5S7AozooiF8GiCJGiBMEiG6cepQkWt1lR1CZYXCJStCRYfOTUIwrB4g8riqgEi5SSSNEHwZqUQNEjwZqISNE7wRqcD3NGIliDsaIYmWANQKSYhWB1yqnHjASrE1YUCFZoIgW/CVYwTj3YJ1iNWVFwnmA1IFJwj2BV4tSD5wSrECsK8hOsjEQKyhKsBwQK6hKsi0QK2hGsE3yYQwyC9YYVBTEJ1jeRgvimDpZTD/oyVbCsKOjb8MESKRjHkMFy6sGYhgiWFQVz6DZYIgXz6SZYAgWEDpZIAVvhguXDHNjTPFhWFHBWk2CJFHBHtWA59YCnigXLigJyyxoskQJKehwspx5Qy+VgWVFAK6eCJVJABLvBcuoB0fwEy4oColtSSpYU0IWfhSVQQHRfzQ1obAVHsmMAAAAASUVORK5CYII=" alt=""></th>
                            <th style="width:13%">Tipo/Clase/Forma</th><th style="width:13%">Marca</th><th style="width:12%">Modelo</th><th style="width:10%">Lote</th><th style="width:17%">Método de aplicación</th><th colspan="2" style="width:20%">Tiempo de aplicación</th>
                        </tr></thead>
                        <tbody>
                            @foreach(['PENETRANTES' => ['Penetrante', 'Tiempo de penetración'], 'REMOVEDOR' => ['Removedor', 'Tiempo de secado'], 'REVELADOR' => ['Revelador', 'Tiempo de revelado']] as $claveConsumiblePt => [$nombreConsumiblePt, $tiempoConsumiblePt])
                                <tr>
                                    <th>{{ $nombreConsumiblePt }}</th>
                                    @foreach(['TIPO','MARCA','MODELO','LOTE','METODO'] as $campoConsumiblePt)
                                        <td>{!! $textoFijoP1($d[$campoConsumiblePt . '_' . $claveConsumiblePt] ?? '', 509.9527 * ['TIPO'=>13,'MARCA'=>13,'MODELO'=>12,'LOTE'=>10,'METODO'=>17][$campoConsumiblePt] / 100 - 5, 20, 6, $nombreConsumiblePt . ': ' . $campoConsumiblePt) !!}</td>
                                    @endforeach
                                    <td class="celda-tiempo-titulo-p12" style="width:12%"><span class="nombre-tiempo-p12">{{ $tiempoConsumiblePt }}</span></td>
                                    <td class="celda-tiempo-valor-p12" style="width:8%">{!! $textoFijoP1($d['TIEMPO_' . $claveConsumiblePt] ?? '', 35, 12, 6, $tiempoConsumiblePt) !!}</td>
                                </tr>
                            @endforeach
                            <tr><th colspan="2">Material absorbente</th><td colspan="6">{!! $textoFijoP1($d['Material_absorbente'] ?? '', 360, 12, 6, 'Material absorbente') !!}</td></tr>
                        </tbody>
                    </table>
                </section>
                @endif
            </div>
            {!! $firmasPt(true) !!}{!! $piePt(2) !!}
        </div>
    @endforeach
    @foreach($paginasResultadosPt as $resultadosPaginaPt)
        @php
            $paginaPt++;
            $observacionesResultadosPt = $textoFijoP1($d['Observaciones'] ?? '', 498, PHP_INT_MAX, 6, 'Observaciones de resultados');
            $criterioConformidadPt = $textoFijoP1($d['Criterio_Acpt'] ?? '', 375, PHP_INT_MAX, 6, 'Criterio de aceptación de conformidad');
            $referenciaConformidadPt = $textoFijoP1($d['Valores_referencia'] ?? '', 375, PHP_INT_MAX, 6, 'Valores de referencia de conformidad');
            $lineasResultadosPt = fn($texto) => $texto === '' ? 1 : substr_count($texto, '<br>') + 1;
            // Desde top:350pt hasta las firmas hay 160pt. Incluir títulos, bordes,
            // relleno y una reserva; las etiquetas ocupan como máximo 2 y 4 líneas.
            $altoPosteriorPt = 22 + 7 + $lineasResultadosPt($observacionesResultadosPt) * 6.48 + 5
                + 5 + max(2, $lineasResultadosPt($criterioConformidadPt)) * 6.48
                + 5 + max(4, $lineasResultadosPt($referenciaConformidadPt)) * 6.48 + 5;
            if ($altoPosteriorPt > 160) {
                throw new \RuntimeException('Las observaciones y la declaración de conformidad no caben en la hoja PT sin invadir las firmas; los datos guardados no se han modificado.');
            }
        @endphp
        <div class="hoja-pt pagina-tres-pt">
            {!! $marcaPt !!}{!! $cabeceraPt($paginaPt) !!}
            <div class="cuerpo-pt">
                <div class="bloque"><div class="seccion">Resultados del Ensayo <em>(Testing results)</em></div>
                    {{-- Resultados de inspeccion --}}<table class="rejilla resultados"><colgroup><col style="width:3.8%"><col style="width:14.2%"><col style="width:9.4%"><col style="width:7.5%"><col style="width:10.5%"><col style="width:5.8%"><col style="width:5.8%"><col style="width:5.8%"><col style="width:6.8%"><col style="width:6.8%"><col style="width:11.8%"><col style="width:11.8%"></colgroup>
                        <tr><th rowspan="2" style="width:3.8%">No.</th><th rowspan="2" style="width:14.2%">ITEM</th><th rowspan="2" style="width:9.4%">No. De junta</th><th rowspan="2" style="width:7.5%">No.<br>indicación</th><th rowspan="2" style="width:10.5%">Tipo de<br>indicación</th><th colspan="3" style="width:17.4%">Dimensiones de la indicación (mm)</th><th colspan="2" style="width:13.6%">Localización</th><th rowspan="2" style="width:11.8%">Declaración de conformidad</th><th rowspan="2" style="width:11.8%">Longitud inspeccionada en m ó m²</th></tr>
                        <tr><th>Largo</th><th>Ancho</th><th>Ø</th><th>H.T</th><th>D.R</th></tr>
                        @foreach($resultadosPaginaPt as $iPt => $resultadoFilaPt)
                            @if(($resultadoFilaPt['tipo'] ?? '') === 'titulo')
                                <tr><td colspan="12" class="texto-grupo">{!! $textoFijoP1($resultadoFilaPt['texto'] ?? '', 500, 8, 6, 'Resultados: título ' . $iPt) !!}</td></tr>
                            @elseif(($resultadoFilaPt['tipo'] ?? '') === 'longitud')
                                <tr><td colspan="11" style="text-align:right;">Longitud inspeccionada:</td><td>{!! $textoFijoP1($resultadoFilaPt['valor'] ?? '', 55, 8, 6, 'Resultados: longitud ' . $iPt) !!}</td></tr>
                            @else
                                <tr>
                                    @foreach(['No'=>3.8,'item'=>14.2,'no_junta'=>9.4,'no_indicacion'=>7.5,'tipo_indicacion'=>10.5,'largo'=>5.8,'ancho'=>5.8,'diametro'=>5.8,'ht'=>6.8,'dr'=>6.8,'declaracion'=>11.8,'long_inspeccionada'=>11.8] as $campoResultadoPt => $anchoResultadoPt)
                                        <td>{!! $textoFijoP1($resultadoFilaPt['data'][$campoResultadoPt] ?? ($campoResultadoPt === 'No' ? $iPt+1 : ''), 509.9527 * $anchoResultadoPt / 100 - 5, PHP_INT_MAX, 6, 'Resultado fila ' . ($loop->parent->index * 20 + $iPt + 1) . ': ' . $campoResultadoPt) !!}</td>
                                    @endforeach
                                </tr>
                            @endif
                        @endforeach
                        @php $resumenPt = $resumenesResultadosPt[$loop->index] ?? null; @endphp
                        <tr class="resumen-resultados-pt"><td class="espacio-resumen-pt" colspan="5"></td><th colspan="3">Incertidumbre ( U )</th><td colspan="2">{!! $textoFijoP1($resumenPt !== null ? ($resumenPt['incertidumbre'] ?? '') : $incertidumbrePt, 65, 8, 6, 'Incertidumbre') !!}</td><th>Total</th><td>{!! $textoFijoP1($resumenPt !== null ? ($resumenPt['total'] ?? '') : ($totalesResultadosPt[$loop->index] ?? ''), 55, 8, 6, 'Total de resultados') !!}</td></tr>
                    </table>
                </div>
                <div class="posterior-resultados-pt">
                <div class="seccion">Observaciones <em>(Remarks)</em></div><div class="observaciones">{!! $observacionesResultadosPt !!}</div>
                <div class="seccion conformidad-resultados-pt">Declaración de conformidad <em>(Statementof conformity)</em></div>
                <table class="datos"><tr><td class="etiqueta" style="width:25%">{!! $leyendaPt('Criterio de aceptación', 'Acceptance criteria') !!}</td><td>{!! $criterioConformidadPt !!}</td></tr><tr><td class="etiqueta">{!! $leyendaPt('Valores de refrencia para la evaluación de la conformidad', 'Reference values for conformity assessment') !!}</td><td>{!! $referenciaConformidadPt !!}</td></tr></table>
                </div>
            </div>
            {!! $firmasPt(true) !!}{!! $piePt(3) !!}
        </div>
    @endforeach
@else
    @foreach($paginasFotosPt as $fotosPaginaPt)
        @php
            $paginaPt = $paginaInicialFotosPt + $loop->index;
            $grupoFotoPt = $loop->index;
            $rotulosFotosPt = [];
            $lineasFilasFotosPt = [0, 0];
            for ($iFotoPt = 0; $iFotoPt < 4; $iFotoPt++) {
                $comentarioFotoPt = $fotosPaginaPt[$iFotoPt]['comment'] ?? '';
                $rotulosFotosPt[$iFotoPt] = $comentarioFotoPt !== '' ? $textoFijoP1($comentarioFotoPt, 200, 84, 6, 'Comentario foto ' . ($grupoFotoPt * 4 + $iFotoPt + 1)) : '';
                $lineasFilasFotosPt[intdiv($iFotoPt, 2)] = max($lineasFilasFotosPt[intdiv($iFotoPt, 2)], $rotulosFotosPt[$iFotoPt] === '' ? 0 : substr_count($rotulosFotosPt[$iFotoPt], '<br>') + 1);
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
                    @for($filaFotoPt=0;$filaFotoPt<2;$filaFotoPt++)
                    <tr class="fila-imagen">
                        @for($colFotoPt=0;$colFotoPt<2;$colFotoPt++)
                            @php
                                $indiceFotoPt=$filaFotoPt*2+$colFotoPt;
                                $fotoPt=$fotosPaginaPt[$indiceFotoPt] ?? null;
                                $medidasFotoPt = !empty($fotoPt['path']) && is_file($fotoPt['path']) ? @getimagesize($fotoPt['path']) : false;
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
                        @for($colFotoPt=0;$colFotoPt<2;$colFotoPt++)
                            @php $indiceFotoPt=$filaFotoPt*2+$colFotoPt; @endphp
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
@endif
</body>
</html>
