<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FOR-05-PRO-EQ-01</title>
            <style>
                @page {
                    margin: 90px 30px; /* Margen superior para header y margen inferior para footer */
                }
                header {
                    position: fixed;
                    top: -80px; /* Ajustar para que quede dentro del margen superior */
                    left: 0;
                    right: 0;
                    height: 100px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-bottom: 1px solid #ffffff;
                }
                footer {
                    position: relative;
                    bottom: 0px; /* Ajustar para que quede dentro del margen inferior */
                    left: 0;
                    right: 0;
                    height: 0px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 70px; /* Ajusta según el tamaño de tu encabezado */
                    font-family: 'arial', sans-serif;
                }
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    margin-bottom: 0px; /* Evita superposición con el footer */
                }

                .table-container {
                    margin: 100px 0;
                }

                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 11px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                }
                    
                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 8px;
                }

                .simbologia td, .simbologia th {
                    border: .6px solid black; 
                }
                .celdaAmarillo{
                    background-color: #FFF2CC;
                }

                .tablaheader {
                    border-collapse: collapse; 
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 10px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                .tablaheader th {
                    /*width: 70%;*/
                    border: 1px solid black; 
                }

        .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 8px;
            background-color: #305496;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosresultados{
            border-collapse: collapse;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .datosresultados td, .datosresultados th {
            border: .6px solid black; 
        }
        .celdaGris{
            background-color: #DBDBDB;
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
        .rotar-texto-dividido {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: normal;
        }

        .rotar-texto-sin-dividir {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: nowrap; /* Evita que el texto se divida en varias líneas */
            max-width: 20px; /* Ajusta al ancho máximo deseado */
        }

        .titulo th {
            font-size: 14px;
        }
        .Firmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .DentroFirmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .DentroFirmas td, .Firmas th {
            border: .6px solid black; 
            /*font-size: 9px;*/
        }
        .celdaAzul{
            background-color: #9BC2E6;
            /*color: #000000;*/
            /*font-weight: bold;*/
        }
        .letraNegra{
            color: #000000;
            font-weight: bold;
        }

        .celdaCrema{
            background-color: #FFF2CC;
        }
        .Comentarios {
            border-collapse: separate;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: justify;
            font-size: 11px;
        }
        /* Aplica el borde a las celdas de la tabla */
        .Comentarios td, .Comentarios th {
            border: .6px solid black; 
        }
        .tablaManifiesto {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            /*font-size: 11px;*/
        }
        .notas{
            text-align: left;
            font-size: 12px;
            border: 1px solid black; 
        }
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                        <tr>
                            <th rowspan="3" style="width: 500%; font-size: 18pt;">MANIFIESTO / RESGUARDO DE EQUIPAMIENTO</th>
                            <th style="width: 90%;">Código:</th>
                            <th style="width: 100%;">FOR-05-PRO-EQ-01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>

                        <tr>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>

                        <tr>
                            <th>Fecha de emisión:</th>
                            <th>29-nov-24</th>
                        </tr>
                </table>

                <div style="margin-bottom: 10px;"></div>   
                
                <table class="datosgenerales">
                    <tbody>
                        <tr>
                            <td style="width: 10%;">Cliente:</td>
                            <td class="lineaInferior"><label>{{ $Manifiesto->Cliente }}</label></td>
                            <td style="width: 20%;"></td>
                            <td style="width: 10%;">Folio:</td>
                            <td class="lineaInferior">{{ $Manifiesto->Folio }}</td>
                        </tr>
                        <tr>
                            <td>Sitio de Trabajo:</td>
                            <td class="lineaInferior">{{ $Manifiesto->Destino }}</td>
                            <td style="width: 20%;"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Servicio: </td>
                            <td class="lineaInferior">{{ $Manifiesto->Trabajo }}</td>
                            <td style="width: 20%;"></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </header>

            <div class="content">
                <div style="margin-bottom: 0px;"></div>
                <table class="datosresultados">
                    <thead>
                            @if($Manifiesto->SATBMPRO == 'SI')
                                <tr class="celdaAzul">
                                    <th class="" style="width: 4%;">No.</th>
                                    <th class="" style="width: 4%;">Cantidad</th>
                                    <th class="" style="width: 4%;">Unidad</th>
                                    <th class="" style="width: 25%;">Descripción</th>
                                    <th class="" style="width: 10%;">No. ECO</th>
                                    <th class="">No. De Serie</th>
                                    <th class="">Marca</th>
                                    <th class="">Modelo</th>
                                    <th class="">SAT</th>
                                    <th class="">BMPRO</th>
                                </tr>
                            @else
                                <tr class="celdaAzul">
                                    <th class="" style="width: 4%;">No.</th>
                                    <th class="" style="width: 4%;">Cantidad</th>
                                    <th class="" style="width: 4%;">Unidad</th>
                                    <th class="" style="width: 25%;">Descripción</th>
                                    <th class="" style="width: 10%;">No. ECO</th>
                                    <th class="">No. De Serie</th>
                                    <th class="">Marca</th>
                                    <th class="">Modelo</th>
                                </tr>
                        @endif         
                    </thead>
                <tbody>
                    @php
                        $contador = 1; // Inicializa el contador
                        $minFilas = 8; // Define el número mínimo de filas
                    @endphp
                        @foreach ($DetallesSolicitud as $detalle)
                                @php
                                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                                @endphp
                                    @if($general->Tipo != 'CONSUMIBLES')
                                            @if($Manifiesto->SATBMPRO == 'SI')
                                                <tr>
                                                    <td class="">{{ $contador }}</td>
                                                    <td class="">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                                    <td class="">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->No_economico ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Serie ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Marca ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Modelo ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->SAT ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->BMPRO ?? 'N/A' }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td class="">{{ $contador }}</td>
                                                    <td class="">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                                    <td class="">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->No_economico ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Serie ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Marca ?? 'N/A' }}</td>
                                                    <td class="">{{ $general->Modelo ?? 'N/A' }}</td>
                                                </tr>
                                    @endif

                                    @php
                                        $contador++; // Incrementa el contador
                                    @endphp
                                @endif
                        @endforeach

                        @for($i = $contador; $i <= $minFilas; $i++)
                            <tr>
                                @if($Manifiesto->SATBMPRO == 'SI')
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                @else
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                    <td class="">----</td>
                                @endif
                            </tr>
                        @endfor
                    </tbody>
                </table>

            </div>

            <footer>

                    <div style="margin-bottom: 5px;"></div>
                    <table class="Comentarios">
                        <tr>
                            <td >Comentarios:
                                @if (isset($Manifiesto) && $Manifiesto->Observaciones != null)
                                        Salida: {{$Manifiesto->Observaciones}}
                                    @else
                                    <tr>
                                        <td><br></td>
                                    </tr>
                                @endif

                                @if (isset($Devolucion) && $Devolucion->Observaciones != null)
                                        Devolución: {{ $Devolucion->Observaciones }}
                                    @else
                                    <tr>
                                        <td><br></td>
                                    </tr>
                                @endif
                            </td>
                        </tr>     
                    </table>

                <table class="notas">
                    <tr>
                        <td class="" colspan="4">Nota a): Los Equipos se entregan en las siguientes condiciones: limpios,  operables para su uso.<br>
                        y quedan al resguardo del firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio.<br>
                        Nota b): El responsable y/o la persona que recibe el equipo y adicionales de este manifiesto se compromete con el cuidado del mismo.<br>
                        Nota c): Si se requiere adjuntar más información en el campo de obsevaciones se puede agregar otra página adicional o escribir en la parte de atrás del formato.
                        </td>
                    </tr>
                </table>

                <div style="margin-bottom: 5px;"></div>

                    <table class="Firmas">
                        <tr>
                            <td style="width: 7%;">

                                <table class="">
                                    <tr class="" >
                                        <td COLSPAN=2 class=""></td>
                                    </tr>
                                        
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>

                            <td>

                                <table class="DentroFirmas">
                                    <tr class="celdaAzul">
                                        <td COLSPAN=2 class="letraNegra">Entrega</td>
                                    </tr>
                                        
                                    <tr class="celdaAzul">
                                        <td class="letraNegra">Nombre</td>
                                        <td>{{ $nombre }}</td>
                                    </tr>
                                    <tr class="celdaAzul">
                                        <td class="letraNegra">Firma</td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>

                            <td>

                                <table class="" style="width: 10%;">
                                    <tr class="" >
                                        <td COLSPAN=2 class=""></td>
                                    </tr>
                                        
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>


                            <td>
                                
                                <table class="DentroFirmas">
                                        <tr class="celdaAzul">
                                            <td COLSPAN=2 class="letraNegra">Autoriza</td>
                                        </tr>
                                        <tr class="celdaAzul">
                                            <td class="letraNegra">Nombre</td>
                                            <td>{{ $Manifiesto->Responsable }} </td>
                                        </tr>
                                        <tr class="celdaAzul">
                                            <td class="letraNegra">Firma</td>
                                            <td></td>
                                        </tr>
                                </table>

                            </td>

                            <td style="width: 7%;">

                                <table class="">
                                    <tr class="" >
                                        <td COLSPAN=2 class=""></td>
                                    </tr>
                                        
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>
                        </tr>

                        <br>

                        <tr>
                            <td style="width: 7%;">

                                <table class="">
                                    <tr class="" >
                                        <td COLSPAN=2 class=""></td>
                                    </tr>
                                        
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>
                                <td>

                                    <table class="DentroFirmas">
                                        <tr class="celdaCrema">
                                            <td COLSPAN=2 class="letraNegra">Fecha de Recepción</td>
                                            <td> {{ $Solicitud->formatted_date }} </td>
                                        </tr>
                                        <tr class="celdaCrema">
                                            <td class="letraNegra">Nombre</td>
                                            <td COLSPAN=2>{{ $Solicitud->tecnico }}</td>
                                        </tr>
                                        <tr class="celdaCrema">
                                            <td class="letraNegra">Firma</td>
                                            <td COLSPAN=2></td>
                                        </tr>
                                    </table>
                                    
                                </td>

                            <td style="width: 10%;">

                                <table class="">
                                    <tr class="" >
                                        <td COLSPAN=2 class=""></td>
                                    </tr>
                                        
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                    <tr class="">
                                        <td class=""></td>
                                        <td></td>
                                    </tr>
                                </table>

                            </td>

                                <td>

                                    <table class="DentroFirmas">
                                        <tr class="celdaCrema">
                                            <td COLSPAN=2 class="letraNegra">Fecha Devolución</td>
                                                <td>
                                                    @if (isset($Devolucion->formatted_date) && $Devolucion->formatted_date == null)
                                                        {{ $Devolucion->formatted_date }}
                                                        @else
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                                                    @endif
                                                </td>
                                        </tr>

                                        <tr class="celdaCrema">
                                            <td class="letraNegra">Nombre</td>
                                                <td COLSPAN=2>
                                                    @if (isset($Devolucion->Recibe) && $Devolucion->Recibe == null)
                                                        {{ $Devolucion->Recibe }}
                                                    @endif
                                                </td>
                                        </tr>
                                        <tr class="celdaCrema">
                                            <td class="letraNegra">Firma</td>
                                            <td COLSPAN=2></td>
                                        </tr>
                                    </table>

                                </td>

                                <td style="width: 7%;">

                                    <table class="">
                                        <tr class="" >
                                            <td COLSPAN=2 class=""></td>
                                        </tr>
                                            
                                        <tr class="">
                                            <td class=""></td>
                                            <td></td>
                                        </tr>
                                        <tr class="">
                                            <td class=""></td>
                                            <td></td>
                                        </tr>
                                    </table>

                                </td>                                
                        </tr>
                    </table>
                    <br>
                    <br>
                    Documento Controlado, prohibida su reproducción parcial o total sin autorización. Propiedad de AICO S.C.,<br>
                    Documento Confidencial. La impresión de este documento se considera un Documento No Controlado.

            </footer>


        </body>
    </html>