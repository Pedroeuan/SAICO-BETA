<!DOCTYPE html>
<html>
<head>
    <title>Manifiesto</title>
    <style>
        @page {
            margin: 45px 25px;
            counter-reset: page; /* Inicializa el contador de páginas */
        }
        .page-num-container {
            position: fixed; /* Fija la posición del contenedor */
            bottom: 20px;   /* Ajusta la posición vertical */
            right: 20px;    /* Ajusta la posición horizontal */
            font-size: 10px;
            text-align: right;
        }
        /* Ajusta la celda al texto en los datos generales */
        .datosGeneralesCortos{
            font-weight: bold;
            width: 100px;
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        .respuestasGenerales{
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        /* borde para tabla-yacziry*/
        /*.tablaManifiesto{
            border: 0px solid black; 
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }*/
        .tablaheader {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaheader td, .tablaheader th {
            width: 70%;
            border: 1px solid black; 
            font-size: 11px;
        }
        .tablaManifiesto {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto td, .tablaManifiesto th {
            border: .6px solid black; 
            font-size: 11px;
        }
        .tablaManifiesto2 {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto2 td, .tablaManifiesto2 th {
            border: .6px solid black; 
            font-size: 11px;
        }

        .tablaManifiesto3 {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .tablaManifiesto3 td, .tablaManifiesto3 th {
            border: .6px solid black; 
            font-size: 8px;
            text-align: center;
            /*border:black 2px double;*/
        }

        .tabla-contenedor {
            display: flex;        /* Distribuir las tablas horizontalmente */
            justify-content: space-around; /* Separación uniforme entre las tablas */
            gap: 10px;            /* Espacio entre tablas */
        }

        .tabla-contenedor table {
            border-collapse: collapse; /* Elimina espacios entre bordes */
            width: 30%;                /* Ajusta el tamaño de cada tabla */
            border: 1px solid black;
        }

        .tabla-contenedor th, .tabla-contenedor td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .notas{
            text-align: left;
            font-size: 11px;
        }
        .letraRoja{
            color: #c02302;
            font-weight: bold;
        }
        .celdaAzul{
            background-color: #2F75B5;
            border:black 2px double;
        }
        .letraBlanca{
            color: #ffffff;
            font-weight: bold;
        }
        .celdaCrema{
            background-color: #F8CBAD;
        }

        .celdaGris{
            background-color: #DBDBDB;
        }

        .celdaAmarillo{
            background-color: #FFF2CC;
        }

        .sinBordetdth td, .sinBordetdth th {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }
        
        .sinBordetd td {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }

        .sinBordeth th {
            border: 0px !important;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 10px;*/
        }
        /*oculta todo el borde de la tabla*/
        .sinBorde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }
        .conBorde{
            border: 10px;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }
        .tablaContainer {
            margin-top: 10px; /* Ajusta el valor según la altura de tu header */
        }
        body {
            padding-top: 60px; /* Ajusta el valor según la altura de tu header */
            /*font-family: "Arial", sans-serif; /* Cambiar a Arial */
            /*margin: 0px 5px 5px 5px; /* Márgenes: arriba, derecha, abajo, izquierda */
        }
        /*muestra solo la linea inferior de la celda*/
        .lineaInferior{
            border-bottom: 1px solid black;
            font-weight: bold;
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        .Contenido{
            /*font-family: Arial;*/
            font-size: 10px !important; /* Forzar el tamaño de letra */
            text-align: center;
        }
        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 0px;
            z-index: 1000; /* Asegura que el header esté por encima */
        }
        footer {
            position: sticky;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 100px; /*400*/
        }
        .altoCelda{
            height: 70px;
        }
        .especial {
            width: 20px; /* Ajusta el ancho de esta celda específica */
        }
        .saltoBlanco{
            height: 15px;
        }
        .logo{
            width: 20px;
            height: 15;
        }

        .tabla-contenedor {
            display: flex;        /* Distribuir las tablas horizontalmente */
            justify-content: space-around; /* Separación uniforme entre las tablas */
            gap: 10px;            /* Espacio entre tablas */
        }
        .datosequipo table, th, td {
            border-collapse: collapse;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th class="" style="width: 500px;">Formato</th>
                <td class="" style="width: 100px;">Código:</td>
                <td class="" style="width: 80px;">FOR-PINS-12</td>
                <th rowspan="3" style="width: 100px;"><img class="logo" src="{{ $Logo }}" alt="Logo" style="width: 50px; height: auto;"></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th rowspan="2" style="width: 500px;"> INFORME DE  INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA </th>
                <td class="" style="width: 100px;">Versión</td>
                <td class="" style="width: 80px;">1</td>
            </tr>
            <tr>
                <td class="" style="width: 100px;">Página</td>
                <td class="page-num-container" style="width: 80px;">
                </td>
            </tr>
        </tbody>
    </table>
</header>

<div class="tablaContainer">
    <table class="sinBorde">
            <tr>
                <th class="celdaAzul letraBlanca" colspan="4">DATOS GENERALES</th>
            </tr>
        <tbody>
            <tr>
                <td class="datosGeneralesCortos">FECHA:</td>
                <td class="lineaInferior"><label for=""></label></td>
                <td class="datosGeneralesCortos">NO. REPORTE:</td>
                <td class="lineaInferior"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">CLIENTE:</td>
                <td class="lineaInferior"></td>
                <td class="datosGeneralesCortos">CONTRATO:</td>
                <td class="lineaInferior"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">PROYECTO: </td>
                <td class="lineaInferior" colspan="3"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">ORDEN DE TRABAJO:</td>
                <td class="lineaInferior" colspan="3"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">FOLIO:</td>
                <td class="lineaInferior" colspan="3"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">PARTIDA:</td>
                <td class="lineaInferior" colspan="3"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">LUGAR:</td>
                <td class="lineaInferior"></td>
                <td class="datosGeneralesCortos">ISOMETRICO/PLANO:</td>
                <td class="lineaInferior"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">PIEZA:</td>
                <td class="lineaInferior"></td>
                <td class="datosGeneralesCortos">MATERIAL:</td>
                <td class="lineaInferior"></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">PROCEDIMIENTO:</td>
                <td class="lineaInferior"></td>
                <td class="datosGeneralesCortos">CRITERIO DE EVALUACIÓN:</td>
                <td class="lineaInferior"></td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-bottom: 4px;"></div>

<div class="tablaContainer">
    <table class="sinBorde">
            <tr>
                <th class="celdaAzul letraBlanca" colspan="9">DATOS DEL EQUIPO</th>
            </tr>
    </table>

    <div style="margin-bottom: 4px;"></div>

    <table class="tablaManifiesto2">
        <tbody>
            <tr class="celdaGris">
                <th colspan="2">EQUIPO</th>
                <th colspan="4">TRANSDUCTOR</th>
                <th colspan="2">BLOCK DE REFERENCIA</th>
                <th colspan="2">ACOPLANTE (MARCA Y TIPO)</th>
            </tr>
            <tr>
                <th class="celdaGris" style="width: 80px;">MARCA:</th>
                <td style="width: 182px;">1</td>
                <th class="celdaGris" style="width: 80px;">MARCA:</th>
                <td colspan="3" style="width: 100px;">2</td>
                <th class="celdaGris" style="width: 80px;">MARCA:</th>
                <td style="width: 182px;">3</td>
                <td style="width: 182px;">4</td>
            </tr>
            <tr>
                <th class="celdaGris">MODELO:</th>
                <td>5</td>
                <th class="celdaGris">MODELO:</th>
                <td colspan="3">6</td>
                <th class="celdaGris">MODELO:</th>
                <td>7</td>
                <th class="celdaGris">LONGITUD DEL CABLE:</th>
            </tr>
            <tr>
                <th class="celdaGris">N.S.:</th>
                <td>8</td>
                <th class="celdaGris">N.S.:</th>
                <td style="width: 94px;">9</td>
                <th class="celdaGris" style="width: 50px;">FRECC:</th>
                <td style="width: 60px;">10</td>
                <th class="celdaGris">N.S.:</th>
                <td>11</td>
                <td>12</td>
            </tr>
        </tbody>
    </table>
</div>

<div style="margin-bottom: 2px;"></div>

<table class="sinBorde">
    <tbody>
        <tr class="">
            <th>GANANCIA:</th>
            <td class="lineaInferior">1</td>
            <th>RANGO</th>
            <td class="lineaInferior">2</td>
            <th>RECHAZO</th>
            <td class="lineaInferior">3</td>
            <th>SUPERFICIE</th>
            <td class="lineaInferior">4</td>
            <th>PINTURA</th>
            <td class="lineaInferior">5</td>
        </tr>
    </tbody>
</table>

<div style="margin-bottom: 5px;"></div>

    <table class="sinBorde">
            <tr>
                <th class="celdaAzul letraBlanca" colspan="9">RESULTADOS</th>
            </tr>
    </table>

<div style="margin-bottom: 2px;"></div>

<table class="tablaManifiesto2">
        <thead>
            <tr class="celdaGris">
                <th colspan="7">DATOS DEL MATERIAL</th>
                <th colspan="8">DATOS DE LA INDICACIÓN</th>
                <th colspan="4">RESULTADOS DE LA INSPECCIÓN</th>
                <th rowspan="2">Observaciones</th>
            </tr>
            <tr class="celdaGris">
                <th>ID:</th>
                <th>Elemento / Tubo</th>
                <th>No. Aceptación</th>
                <th>No. Serie</th>
                <th>No. Colada</th>
                <th>tnominal</th>
                <th>Ø</th>

                <th>No.Ind.</th>
                <th>Tipo de Indicación</th>
                <th>NR (%)</th>
                <th>NI (%)</th>
                <th>H.T.</th>
                <th>Prof</th>
                <th>LA</th>
                <th>LC</th>

                <th>tmáx</th>
                <th>tmin</th>
                <th>Metros Lineales</th>
                <th>Evaluación</th>
            </tr>
        </thead>
        <tbody>
            @for($i = 0; $i < 14; $i++)
            <tr>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
                <td>----</td>
            </tr>
            @endfor
            <tr class="sinBordetd">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th colspan="4">Longitud Inspeccionada:</th>
                <th colspan="2">48.00 m</th>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-bottom: 5px;"></div>

    <table class="tablaManifiesto3">
        <thead>

            <tr class="sinBordetd">
                <td style="width: 80px;"></td>
                <th colspan="4" class="celdaAmarillo">INDICACIONES Y HALLAZGOS</th>
                <td style="width: 20px;"></td>
                <td style="width: 20px;"></td>
                <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA DEL REPORTE</th>
                <td></td>
            </tr>

            <tr class="sinBordeth">
                <th></th>
                <td style="width: 20px;"><strong>NPIR:</strong></td>
                <td style="width: 180px;">NO PRESENTA INDICACIONES RELEVANTES</td>
                <td style="width: 20px;"><strong>CI:</strong></td>
                <td style="width: 100px;">CORROSIÓN INTERNA</td>
                <th></th>
                <th></th>

                <td style="width: 20px;"><strong>tmin</strong></td>
                <td style="width: 110px;">ESPESOR NÓMINAL (in)</td>
                <td style="width: 20px;"><strong>LA</strong></td>
                <td style="width: 150px;">LONGITUD AXIAN (IN)</td>
                <td style="width: 20px;"><strong>tmin</strong></td>
                <td style="width: 180px;">ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                <th></th>
            </tr>

            <tr class="sinBordeth">
                <th></th>
                <td><strong>ZI:</strong></td>
                <td>ZONA DE INCLUSIONES NO METALICAS</td>
                <td><strong>L:</strong></td>
                <td>LAMINACIÓN</td>
                <th></th>
                <th></th>

                <td><strong>G:</strong></td>
                <td>GANANCIA (dB)</td>
                <td><strong>LC</strong></td>
                <td>LONGITUD CIRCUNFERENCIAL (IN)</td>
                <td><strong>tmax</strong></td>
                <td>ESPESOR MÁXIMO REGISTRADO (PULG)</td>
                <th></th>
            </tr>

            <tr class="sinBordeth">
                <th></th>
                <td><strong>LE:</strong></td>
                <td>LAMINACIÓN ESCALONADA</td>
                <td><strong>I:</strong></td>
                <td>INCLUSIÓN NO METÁLICA</td>
                <th></th>
                <th></th>

                <td><strong>NR:</strong></td>
                <td>NIVEL DE REFERENCIA (%)</td>
                <td><strong>NI:</strong></td>
                <td>NIVEL DE INDICACIÓN (%)</td>
                <td><strong>Prof</strong></td>
                <td>PROFUNDIDAD DE LA INDICACIÓN</td>
                <th></th>
            </tr>
        </thead>
    </table>

    <div style="margin-bottom: 20px;"></div>

    <div class="tablaContainer">
        <table class="sinBorde">
            <tr>
                <td class="datosGeneralesCortos">Observaciones:</td>
                <td class="lineaInferior"></td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 20px;"></div>


    <footer>

        <div class="tablaContainer">
            <table class="sinBorde">
                <thead>

                    <tr>
                        <td style="width: 30px;"></td>
                        <th>Realizó</th>
                        <td style="width: 30px;"></td>
                        <th>Vo.Bo.</th>
                        <td style="width: 30px;"></td>
                        <th>Vo.Bo.</th>
                    </tr>

                    <!--<tr class="sinBordetdth">-->
                    <tr>
                        <th></th>
                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                        <td></td>
                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                        <td></td>
                        <td style="width: 200px; height:40px" class="lineaInferior"></td>
                    </tr>

                    <tr>
                        <th></th>
                        <td><strong>NOMBRE DEL TÉCNICO</strong></td>
                        <td></td>
                        <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                        <td></td>
                        <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                    </tr>
                    
                    <tr>
                        <th></th>
                        <td><strong>NIVEL DEL TÉCNICO</strong></td>
                        <td></td>
                        <td><strong>PUESTO DEL ENCARGADO</strong></td>
                        <td></td>
                        <td><strong>PUESTO DEL ENCARGADO</strong></td>
                    </tr>

                    <tr>
                        <th></th>
                        <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                        <td></td>
                        <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                        <td></td>
                        <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                    </tr>
                </thead>
            </table>
        </div>
    </footer>
    </body>
</html>