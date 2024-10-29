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
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th class="">Formato</th>
                <th class="respuestasGenerales">Código:</th>
                <th class="respuestasGenerales">FOR-PINS-12</th>
                <th rowspan="3"><img class="logo" src="{{ $Logo }}" alt="Logo" style="width: 50px; height: auto;"></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td rowspan="2"> INFORME DE  INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA </td>
                <td class="respuestasGenerales">Versión</td>
                <td class="respuestasGenerales">1</td>
            </tr>
            <tr>
                <td class="respuestasGenerales">Página</td>
                <td class="page-num-container">
                </td>
            </tr>
        </tbody>
    </table>
</header>

<div class="tablaContainer">
    <table class="sinBorde">
            <tr>
                <th class="celdaAzul letraBlanca" colspan="9">DATOS GENERALES</th>
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
    <table class="tablaManifiesto">
        <thead>
            <tr>
                    <th class="celdaAzul letraBlanca" colspan="9">DATOS DEL EQUIPO</th>
            </tr>

            <table class="">
                <tbody>
                    <tr>
                        <th class="celdaGris" colspan="2">EQUIPO</th>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">MARCA:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">MODELO:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">N.S:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                </tbody>
            </table>

            <table class="">
                <tbody>
                    <tr>
                        <th class="celdaGris" colspan="2">EQUIPO</th>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">MARCA:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">MODELO:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                    <tr>
                        <td class="respuestasGenerales">N.S:</td>
                        <td class="respuestasGenerales"></td>
                    </tr>
                </tbody>
            </table>

        </thead>
    <tbody>
                <tr>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>

                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>

                </tr>

        </tbody>
    </table>
<br>
    <table class="tablaManifiesto2">
        <thead>
            <tr>

                    <th class="celdaAzul letraBlanca" colspan="7">ADICIONAL (accesorio, consumible, y/o herramientas)</th>

                    <th class="celdaAzul letraBlanca" colspan="5">ADICIONAL (accesorio, consumible, y/o herramientas)</th>

            </tr>

                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="">SAT</th>
                        <th class="">BMPRO</th>
                        <th class="" colspan="7">Descripción</th>
                    </tr>

                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="" colspan="5">Descripción</th>
                    </tr>

        </thead>
        <tbody>
            @php
                $contador = 1; // Inicializa el contador
                $minFilas = 5; // Define el número mínimo de filas
            @endphp

                            <tr>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido" colspan="7"></td>
                            </tr>
                            <tr>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido"></td>
                                <td class="Contenido" colspan="4"></td>
                            </tr>

                        @php
                            $contador++; // Incrementa el contador
                        @endphp

                    <tr>
                        
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido" colspan="7">----</td>

                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido" colspan="4">----</td>

                    </tr>

                    <tr>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido" colspan="7">----</td>
                            
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido" colspan="4">----</td>
                        
                    </tr>
        </tbody>

    </table>
</div>
    <table class="tablaManifiesto">
        <tr>
            <td class="notas" colspan="4"><strong class="letraRoja">Nota a):</strong> Los Equipos se entregan en las siguientes condiciones: limpios,  operables para su uso y quedan al resguardo del firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio. <br>
                <strong class="letraRoja">Nota b):</strong> El responsable y/o la persona que recibe el equipo y adicionales de este manifiesto se compromete con el cuidado del mismo. <br>
                <strong class="letraRoja">Nota c):</strong> Si se requiere adjuntar más información en el campo de obsevaciones se puede agregar otra página adicional o escribir en la parte de atrás del formato.
            </td>
        </tr>
    </table>
        <footer>
            <div class="">
                <table class="tablaManifiesto">
                    <thead>
                        <tr>
                            <th class="celdaAzul letraBlanca" colspan="3">SALIDA DE EQUIPOS Y ADICIONALES</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="saltoBlanco"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" class="Contenido" class="altoCelda"></td>
                            <td rowspan="2" class="Contenido" class="altoCelda"></td>
                            <td class="celdaAzul letraBlanca">Obsevaciones</td>
                        </tr>
                        <tr>
                            <td class="altoCelda"></td>
                        </tr>
                        <tr>
                            <td>Entrega: Nombre/cargo/firma</td>
                            <td>Recibe: Nombre/cargo/firma </td>
                            <td rowspan="2"></td>
                        </tr>
                        
                        <tr>
                            <td class="celdaAzul letraBlanca" colspan="3">RETORNO DE EQUIPOS Y ADICIONALES</td>
                        </tr>
                        <tr>
                        <td colspan="2">Fecha de devolución a las instalaciones de AICO S.C. :

                        </td>

                            <td></td>
                        </tr>
                        <tr>
                            <td class="celdaAzul letraBlanca">¿Los equipos retornan en optimas condiciones?</td>
                                <td>
                                    SI <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                    NO <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; 
                                    N/A <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                </td>
                            <td class="celdaAzul letraBlanca">Observaciones</td>
                        </tr>
                        <tr>
                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda" rowspan="2"></td>

                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda" rowspan="2"></td>
                        </tr>
                        <tr>
                            <td>Entrega: Nombre/cargo/firma</td>
                            <td>Recibe: Nombre/cargo/firma </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </footer>
    </body>
</html>