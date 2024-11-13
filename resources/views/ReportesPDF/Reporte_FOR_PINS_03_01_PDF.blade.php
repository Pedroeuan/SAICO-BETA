<!DOCTYPE html>
<html>
<head>
    <style>

    </style>
</head>
<body>

    <div class="titulo">Reporte de Inspección</div>

    <div class="contenido">
        <p>Este es el reporte generado para el usuario: <strong>{{ $nombre }}</strong>.</p>
        <p>A continuación, se muestran los resultados de la inspección:</p>

        <table class="tabla">
            <thead>
                <tr>
                    <th>Campo 1</th>
                    <th>Campo 2</th>
                    <th>Campo 3</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 24; $i++)
                <tr>
                    <td>Dato 1</td>
                    <td>Dato 2</td>
                    <td>Dato 3</td>
                </tr>
                <!-- Más filas según tus datos -->
            </tbody>
    </table>
    </htmlpageheader> 
<htmlpagefooter name="myFooter">
    <div style="text-align: center;">
        <footer>
                <table class="tablaManifiesto3">
                    <thead>
                        <tr>
                            <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                        </tr>

                        <tr>
                            <td style="width: 20px;"><strong>NPIR</strong></td>
                            <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                            <td style="width: 20px;"><strong>DM</strong></td>
                            <td style="width: 150px;">DAÑO MECÁNICO</td>
                            <td style="width: 20px;"><strong>PT</strong></td>
                            <td style="width: 180px;">POROSIDAD TUBULAR</td>
                        </tr>

                        <tr>
                            <td><strong>G</strong></td>
                            <td>GRIETA</td>
                            <td><strong>S</strong></td>
                            <td>SOCAVADO</td>
                            <td><strong>C</strong></td>
                            <td>CRATER</td>
                        </tr>

                        <tr>
                            <td><strong>ZG</strong></td>
                            <td>ZONA DE GRIETAS</td>
                            <td><strong>P</strong></td>
                            <td>POROSIDAD</td>
                            <td><strong>IL</strong></td>
                            <td>INDICACIÓN LINEAL</td>
                        </tr>

                        <tr>
                            <td><strong>FF</strong></td>
                            <td>FALTA DE FUSIÓN</td>
                            <td><strong>ZP</strong></td>
                            <td>ZONA DE POROS</td>
                            <td><strong>IR</strong></td>
                            <td>INDICACIÓN REDONDEADA</td>
                        </tr>

                    </thead>
                </table>

                <div style="margin-bottom: 20px;"></div>

                <div class="">
                    <table class="sinBorde">
                        <tr>
                            <td class="datosGeneralesCortos">Observaciones:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom: 20px;"></div>

                    <div class="">
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
                                    <td><strong>Técnico N-II SNT-TC-1A</strong></td>
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
    </div>

</body>
</html>
