<!DOCTYPE html>
<html>
<head>
    <style>
        /* Aquí puedes incluir cualquier estilo para el contenido principal */
        .contenido {
            font-size: 12px;
            text-align: justify;
        }

        /*.tabla {
            width: 100%;
            border-collapse: collapse;
        }*/

        /*.tabla, .tabla th, .tabla td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }*/

        .titulo {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
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
                <tr>
                    <td>Dato 1</td>
                    <td>Dato 2</td>
                    <td>Dato 3</td>
                </tr>
                <!-- Más filas según tus datos -->
            </tbody>
        </table>
    </div>

</body>
</html>
