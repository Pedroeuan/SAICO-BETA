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
        /*oculta todo el borde de la tabla*/
        .sinBorde{
            border: 0px;
            text-align: center;
            border-collapse: collapse;
            text-align: center;
            width: 100%;
        }
        .tablaContainer {
            margin-top: 10px; /* Ajusta el valor según la altura de tu header */
        }
        body {
            padding-top: 60px; /* Ajusta el valor según la altura de tu header */
            /*font-family: "Arial", sans-serif; /* Cambiar a Arial */
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

        p {
        border: red 5px double;
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
<br>
    
<div class="">
    <table class="tablaManifiesto2">
        <thead>
            <tr>
                <th class="celdaAzul letraBlanca" colspan="9">DATOS DEL EQUIPO</th>
            </tr>
        </thead>
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
                <th class="celdaGris">MARCA:</th>
                <td>Smith</td>
                <th class="celdaGris">MARCA:</th>
                <td colspan="3">50</td>
                <th class="celdaGris">MARCA:</th>
                <td>Smith</td>
                <td>Smith</td>
            </tr>
            <tr>
                <th class="celdaGris">MODELO:</th>
                <td>Jackson</td>
                <th class="celdaGris">MODELO:</th>
                <td colspan="3">94</td>
                <th class="celdaGris">MODELO:</th>
                <td>Jackson</td>
                <th class="celdaGris">LONGITUD DEL CABLE:</th>
            </tr>
            <tr>
                <th class="celdaGris">N.S.:</th>
                <td>Jackson</td>
                <th class="celdaGris">N.S.:</th>
                <td>94</td>
                <th class="celdaGris">FRECC:</th>
                <td>Smith2</td>
                <th class="celdaGris">N.S.:</th>
                <td>Jackson</td>
                <td>Smith3</td>
            </tr>
        </tbody>
    </table>
</div>


    <footer>


    </footer>
    </body>
</html>