<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-03/01</title>
            <style>
                @page {
                    margin: 
                    /*3.0cm /* superior */
                    /*2.1cm /* derecho */
                    /*2.1cm /* inferior */
                    /*2.4cm; /* izquierdo */
                    3.0cm /* superior */
                    1.2cm /* derecho */
                    2.1cm /* inferior */
                    2.2cm; /* izquierdo */
                }

                header {
                    width: 100%;
                    top: -30px; /* Ajusta para que no interfiera con el margen de la página */
                    height: auto; /* Permite crecer según el contenido */
                    text-align: center;
                    /*background-color: rgb(226, 45, 45);*/
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18);*/
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin: -30px, 0; /* Ajusta el margen de la página */
                    padding-bottom: 60px; /* Para que el contenido no se monte en el footer */
                    font-family: 'arial', sans-serif;
                    /*background-color: rgb(45, 78, 226);*/
                }
                
                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 8px !important;
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
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
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
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 400%;">FORMATO</th>
                            <th style="width: 70%;">Código:</th>
                            <th style="width: 100%;">FOR-PIMP-02_B/03</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> Informe de Ensayo de Durezas en Metales Base Hardness Test Report on Base Metals</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="6">DATOS GENERALES</th></tr>
                    </thead>   

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr>
                            <th style="width: 12%;">FECHA:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">2</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior">3</td>
                            <th>No. CONTRATO:</th>
                            <td class="lineaInferior">4</td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="3">5</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3">6</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="3">7</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="3">8</td>
                        </tr>
                        <tr>
                            <th>INSTALACIÓN:</th>
                            <td class="lineaInferior">9</td>
                            <th>No. ISOMÉTRICO:</th>
                            <td class="lineaInferior">10</td>
                        </tr>
                        <tr>
                            <th>NOMBRE DE LA PIEZA:</th>
                            <td class="lineaInferior">11</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">12</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior">13</td>
                            <th style="width: 160px;">TRAZABILIDAD:</th>
                            <td class="lineaInferior">14</td>
                        </tr>
                        <tr>
                            <th >ACCESORIO:</th>
                            <td class="lineaInferior">13</td>
                            <th style="width: 160px;">TUBERIA:</th>
                            <td class="lineaInferior">14</td>
                            <th style="width: 160px;">ESTRUCTURAL:</th>
                            <td class="lineaInferior">14</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DE LA PRUEBA</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 100px;">MÉTODO:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">TEMPERATURA DE LA PIEZA:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">ESPESOR/CEDÚLA:</th>
                                <th style="width: 100px;"></th>
                            </tr>
                        </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 100px;">MARCA:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">NO. DE SERIE:</th>
                                <th style="width: 100px;"></th>
                            </tr>

                        </tbody>
                </table>


            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
        {{-- COLUMNA IZQUIERDA --}}
        <td width="70%" style="vertical-align: top;">

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="5">VALORES DE DUREZA MEDIDOS (ESCALA BRINELL)</th></tr>
                    </thead>  

                {{-- Línea en blanco 1 (5 espacios) --}}
                <tr>
                    <td style="height: 25px;">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                {{-- Línea en blanco 2 (5 espacios) --}}
                <tr>
                    <td style="height: 25px;">&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table>
            </table>
            <table>
            </table>
            <table>
            </table>
            <table>
            </table>

            {{-- TABLA INFERIOR: PROMEDIO Y ESPECIFICACIÓN --}}
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="padding: 6px; width: 40%;">
                        <strong>DUREZA PROMEDIO MEDIDO</strong><br>
                        <em>Measured Average Hardness</em>
                    </td>
                    <td></td>
                    <td ></td>
                    <td ></td>
                </tr>

                <tr>
                    <td style="padding: 6px;">
                        <strong>DUREZA DE ACUERDO A LA ESPECIFICACIÓN DE REFERENCIA</strong><br>
                        <em>Hardness According to the Reference Specification</em>
                    </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>

        </td>


        {{-- COLUMNA DERECHA CON FOTO --}}
        <td width="30%" style="text-align: center; vertical-align: top;">
            <img src="{{ asset('storage/fotos/' . ($foto ?? 'default.png')) }}" 
                alt="Foto" width="180" style="margin-top: 10px;">
        </td>

    </tr>
</table>


                    <table>
                        <thead> 
                                4 Firmas 
                                <tr>
                                    <td style="width: 15px;"></td>
                                    <th>37</th>
                                    <td style="width: 15px;"></td>
                                    <th>38</th>
                                    <td style="width: 15px;"></td>
                                    <th>39</th>
                                    <td style="width: 15px;"></td>
                                    <th>40</th>
                                    <td style="width: 15px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>41</strong></td>
                                    <td></td>
                                    <td><strong>42</strong></td>
                                    <td></td>
                                    <td><strong>43</strong></td>
                                    <td></td>
                                    <td><strong>44</strong></td>
                                    <th></th>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>45</strong></td>
                                    <td></td>
                                    <td><strong>46</strong></td>
                                    <td></td>
                                    <td><strong>47</strong></td>
                                    <td></td>
                                    <td><strong>48</strong></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>49</strong></td>
                                    <td></td>
                                    <td><strong>50</strong></td>
                                    <td></td>
                                    <td><strong>51</strong></td>
                                    <th></th>
                                </tr>
                        </thead>                            
                    </table>
            </footer>

            <div class="content"> 

                
            </div>
        </body>
        
    </html>