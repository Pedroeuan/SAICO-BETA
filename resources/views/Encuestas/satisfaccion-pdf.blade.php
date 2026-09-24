<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: letter landscape;
            margin: 15px 18px;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, "DejaVu Sans", sans-serif;
            color: #000;
            font-size: 6.9pt;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        /* =========================================================
            TABLA PRINCIPAL
        ========================================================= */

        .tabla-contenido {
            border: 1.25pt solid #000;
            table-layout: fixed;
        }
        .tabla-contenido td {
            border: 0.5pt solid #000;
            padding: 1.5px 3px;
            vertical-align: middle;
            font-size: 6.9pt;
        }
        .sin-borde {
            border: none !important;
            background: transparent !important;
        }
        .negrita {
            font-weight: bold;
        }
        .centro {
            text-align: center;
        }
        .izquierda {
            text-align: left;
        }
        .derecha {
            text-align: right;
        }
        .arriba {
            vertical-align: top;
        }
        .texto-libre {
            border: none !important;
            padding: 2px 4px;
        }
        /* =========================================================
        RELLENOS
        ========================================================= */

        .gris-25 {
            background: #BFBFBF;
        }

        .gris-15 {
            background: #D9D9D9;
        }

        /* =========================================================
        ENCABEZADO PRINCIPAL
        ========================================================= */

        .logo {
            width: 46px;
            height: auto;
        }

        .tabla-encabezado-superior {
            margin-bottom: 0;
        }

        .tabla-encabezado-superior td {
            padding: 5px 3px;      /* antes: 5px 8px */
            white-space: nowrap;   /* evita que "FOR-PVEN-01/03" o "1 de 1" se partan */
        }

        .titulo-formato {
            font-size: 10pt;
            line-height: 1.35;
            white-space: nowrap;
        }

        /* =========================================================
            PREGUNTAS
        ========================================================= */
        .seleccionada {
            font-size: 8pt;
            font-weight: bold;
        }

        /* =========================================================
            FIRMAS
        ========================================================= */
        .tabla-firmas {
            table-layout: fixed;
            margin-top: 4px;
        }
        .tabla-firmas td {
            border: 1.25pt solid #000;
            height: 39pt;
            padding: 3px 8px;
            text-align: center;
            vertical-align: top;   /* antes: bottom */
            font-size: 6.9pt;
            font-weight: bold;
        }

        .firma-imagen {
            max-width: 110px;
            max-height: 30px;
            margin-bottom: 2px;
        }

        .espacio-firma {
            height: 26pt;
        }

        .linea-firma {
            border-top: 0.5pt solid #000;
            height: 3px;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .encabezado-escala {
            font-size: 6.2pt !important;
            line-height: 1.15;
            padding: 1px 2px !important;
        }

        .linea-comentario {
            border: none !important;
            border-bottom: 0.5pt solid #000 !important;
        }
    </style>
</head>
<body>
    <table class="tabla-contenido tabla-encabezado-superior no-break">
        <tr style="height:28pt;">
            <td class="centro" style="width:69%;">FORMATO</td>
            <td class="centro negrita" style="width:8.9%;">Código:</td>
            <td class="centro" style="width:11%;">FOR-PVEN-01/03</td>
            <td class="centro" rowspan="3" style="width:12%;"><img class="logo" src="{{ public_path('images/Logo_AICO_R.jpg') }}" alt="AICO"></td>
        </tr>
        <!-- FILA 4 -->
        <tr style="height:28pt;">
            <td class="centro negrita titulo-formato" rowspan="2">Encuesta de Satisfacción del Cliente</td>
            <td class="centro negrita">Versión:</td>
            <td class="centro">3</td>
        </tr>
        <!-- FILA 5 -->
        <tr style="height:28pt;">
            <td class="centro negrita">Página:</td>
            <td class="centro">1 de 1</td>
        </tr>
    </table>
    <table class="tabla-contenido no-break" style="margin-top:0; border-top:none;">
        <colgroup>
            <col style="width:5.78%;">
            <col style="width:5.22%;">
            <col style="width:7.59%;">
            <col style="width:5.57%;">
            <col style="width:4.67%;">
            <col style="width:5.99%;">
            <col style="width:4.67%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
            <col style="width:3.97%;">
            <col style="width:4.67%;">
            <col style="width:5.01%;">
            <col style="width:2.30%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
            <col style="width:5.57%;">
        </colgroup>
        <!-- =============================================================
            FILA 6 — CLIENTE / PROYECTO / TELÉFONO
        ============================================================= -->
        <tr style="height:20.7pt;">
            <td class="izquierda" colspan="7">
                <span class="negrita">CLIENTE:</span>
                {{ $detalles['Cliente'] ?? '' }}
            </td>

            <td class="centro" colspan="6">
                <span class="negrita">PROYECTO / CONTRATO:</span>
                {{ $detalles['Proyecto'] ?? '' }}
                @if(!empty($detalles['Contrato']))
                    / {{ $detalles['Contrato'] }}
                @endif
            </td>

            <td class="centro" colspan="6">
                <span class="negrita"> TELÉFONO:</span>
                {{ $detalles['Telefono'] ?? '' }}
            </td>
        </tr>
        <!-- =============================================================
            FILAS 8-9 — TEXTO DE AGRADECIMIENTO
        ============================================================= -->
        <tr style="height:20.7pt;">
            <td class="izquierda texto-libre" colspan="19">
                Gracias por realizar la encuesta de satisfacción del cliente,
                la cual será de gran ayuda para mejorar nuestros servicios.
            </td>
        </tr>
        <!-- =============================================================
            FILA 11 — INSTRUCCIÓN
        ============================================================= -->
        <tr style="height:10.35pt;">
            <td class="izquierda texto-libre" colspan="19">
                Clasifique su nivel de satisfacción del inciso (A) de acuerdo
                a los criterios de los incisos (B y C) descritos a continuación.
            </td>
        </tr>

        <!-- FILA 13 -->
        <tr style="height:8.3pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro negrita" colspan="2">A</td>
            <td class="centro negrita" colspan="2">B</td>
            <td class="centro negrita" colspan="2">C</td>
            <td class="sin-borde" colspan="12"></td>
        </tr>

        <!-- FILA 14 — ENCABEZADOS DE ESCALA -->
        <tr style="height:22.8pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro negrita gris-25 encabezado-escala" colspan="2">
                Nivel de<br>satisfacción
            </td>
            <td class="centro negrita gris-25 encabezado-escala" colspan="2">
                Expresión<br>cualitativa
            </td>
            <td class="centro negrita gris-25 encabezado-escala" colspan="2">
                Calificación<br>final en %
            </td>
            <td class="sin-borde" colspan="12"></td>
        </tr>

        <!-- FILA 15 — NIVEL 1 + CUADRO DE DUDAS (baja y queda centrado) -->
        <tr style="height:12.4pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro" colspan="2">1</td>
            <td class="centro" colspan="2">Malo</td>
            <td class="centro" colspan="2">50-59%</td>

            <td class="sin-borde" colspan="4"></td>

            <td class="centro" colspan="8" rowspan="3" style="vertical-align:middle;">
                <strong>DUDAS, QUEJAS, ACLARACIONES O FELICITACIONES</strong><br>
                FAVOR DE ENVIAR AL SIGUIENTE CORREO:
                <br>
                <strong>atencionalcliente@aicosc.com</strong>
            </td>
        </tr>

        <!-- FILA 16 — NIVEL 2 -->
        <tr style="height:12.4pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro" colspan="2">2</td>
            <td class="centro" colspan="2">Regular</td>
            <td class="centro" colspan="2">60-69%</td>
            <td class="sin-borde" colspan="4"></td>
        </tr>

        <!-- FILA 17 — NIVEL 3 -->
        <tr style="height:12.4pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro" colspan="2">3</td>
            <td class="centro" colspan="2">Aceptable</td>
            <td class="centro" colspan="2">70-79%</td>
            <td class="sin-borde" colspan="4"></td>
        </tr>

        <!-- FILA 18 — NIVEL 4 -->
        <tr style="height:12.4pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro" colspan="2">4</td>
            <td class="centro" colspan="2">Bueno</td>
            <td class="centro" colspan="2">80-89%</td>
            <td class="sin-borde" colspan="12"></td>
        </tr>

        <!-- FILA 19 — NIVEL 5 -->
        <tr style="height:12.4pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="centro" colspan="2">5</td>
            <td class="centro" colspan="2">Excelente</td>
            <td class="centro" colspan="2">90-100%</td>
            <td class="sin-borde" colspan="12"></td>
        </tr>

        <!-- FILA 21 — FECHA (sin bordes, centrada bajo la tabla de escala) -->
        <tr style="height:10.35pt;">
            <td class="sin-borde" colspan="1"></td>
            <td class="sin-borde centro" colspan="6" style="padding-top:4px;">
                <span class="negrita">FECHA:</span>
                {{ $detalles['Fecha'] ?? '' }}
            </td>
            <td class="sin-borde" colspan="12"></td>
        </tr>

        <!-- FILA 22 — ENCABEZADO PREGUNTAS -->
        <tr style="height:10.35pt;">
            <td class="sin-borde" colspan="14"></td>
            <td class="centro negrita" colspan="5">Nivel de satisfacción</td>
        </tr>

        <!-- FILA 23 — NIVELES 1 A 5 -->
        <tr style="height:9.8pt;">
            <td class="sin-borde" colspan="14"></td>
            <td class="centro negrita" colspan="1">1</td>
            <td class="centro negrita" colspan="1">2</td>
            <td class="centro negrita" colspan="1">3</td>
            <td class="centro negrita" colspan="1">4</td>
            <td class="centro negrita" colspan="1">5</td>
        </tr>

        <!-- =============================================================
            PREGUNTAS
        ============================================================= -->
        @foreach($textosPreguntas as $indice => $texto)
            @php
                $respuesta = (int) (
                    $preguntas['PREGUNTA_' . ($indice + 1)] ?? 0
                );
            @endphp
            <tr style="height:12.9pt;">
                <td class="izquierda" colspan="14">
                    {{ $indice + 1 }}. {{ $texto }}
                </td>
                @for($nivel = 1; $nivel <= 5; $nivel++)
                    <td class="centro" colspan="1">
                        @if($respuesta === $nivel)
                            <span class="seleccionada">X</span>
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
        <!-- =============================================================
            FILA 34 — NOTA + CALIFICACIÓN FINAL
        ============================================================= -->
        <tr style="height:29pt;">
            <td class="sin-borde" colspan="6"></td>
            <td class="izquierda" colspan="8" style="font-size:6.2pt; border-left:none; border-bottom:none;">
                <strong>NOTA:</strong>
                La Calificación Final inciso (C) demostrada en % será a partir
                de la suma de las 10 preguntas de acuerdo con el nivel de
                satisfacción donde 1=2, 2=4, 3=6, 4=8 y 5=10 puntos.
            </td>
            <!-- la celda CALIFICACIÓN FINAL se queda igual -->
            <td class="centro negrita gris-15" colspan="5">
                CALIFICACIÓN FINAL
                <br>
                {{ $calificacionFinal }}%
                <br>
                {{ $clasificacion }}
            </td>
        </tr>

        <!-- =============================================================
            FILA 35 — COMENTARIO + PROMEDIO
        ============================================================= -->
        <tr style="height:14pt;">
            <td class="sin-borde izquierda negrita" colspan="5" style="white-space:nowrap;">
                Desea realizar algún comentario adicional:
            </td>
            <td class="linea-comentario izquierda" colspan="14">
                {{ $encuesta->Comentario ?: '' }}
            </td>
        </tr>

        <!-- FILAS 36-37 — LÍNEAS DE COMENTARIO -->
        <tr style="height:12pt;">
            <td class="linea-comentario" colspan="19"></td>
        </tr>
        <tr style="height:12pt;">
            <td class="linea-comentario" colspan="19"></td>
        </tr>
    </table>
    <!-- =================================================================
        FIRMAS
    ================================================================= -->
    <table class="tabla-firmas no-break">
        <colgroup>
            <col style="width:28.83%;">
            <col style="width:32.94%;">
            <col style="width:38.23%;">
        </colgroup>
        <tr>
            <!-- FIRMA CLIENTE -->
            <td>
                FIRMA DEL CLIENTE
                <div class="espacio-firma">
                    @if(!empty($firma['Imagen']))
                        <img src="{{ $firma['Imagen'] }}" style="height:60px">
                    @endif
                </div>
                @if(!empty($firma['Nombre']))
                    {{ $firma['Nombre'] }}
                @endif
            </td>
            <!-- JEFE DE VENTAS -->
            <td>
                JEFE DE VENTAS
                <div class="espacio-firma"></div>
            </td>
            <!-- DIRECTOR GENERAL -->
            <td>
                DIRECTOR GENERAL
                <div class="espacio-firma"></div>
                
            </td>
        </tr>
    </table>
</body>
</html>