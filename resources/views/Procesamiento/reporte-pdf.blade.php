<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generando reporte</title>
    <style>
        body { margin: 0; background: #f4f6f9; color: #212529; font-family: Arial, sans-serif; }
        .estado { max-width: 520px; margin: 15vh auto; padding: 32px; text-align: center; background: white; border: 1px solid #d6d8db; border-radius: 6px; }
        .cargador { width: 38px; height: 38px; margin: 0 auto 20px; border: 4px solid #dbe7ff; border-top-color: #0d6efd; border-radius: 50%; animation: giro .8s linear infinite; }
        .error { color: #b02a37; }
        @keyframes giro { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <main class="estado">
        <div id="cargador" class="cargador" aria-hidden="true"></div>
        <p id="mensaje">Generando reporte PDF...</p>
    </main>
    <script src="{{ asset('js/procesamiento-asincrono-saico.js') }}?v={{ filemtime(public_path('js/procesamiento-asincrono-saico.js')) }}"></script>
    <script>
        // Al terminar abre el PDF en esta misma pestana, igual que el flujo anterior.
        window.SaicoProcesamiento.esperar(@json($estadoUrl), {
            alCambiar: function (trabajo) {
                document.getElementById('mensaje').textContent = trabajo.mensaje || 'Generando reporte PDF...';
            }
        }).then(function (trabajo) {
            document.getElementById('mensaje').textContent = 'Reporte generado correctamente.';
            window.location.replace(trabajo.resultado.descarga_url);
        }).catch(function (error) {
            document.getElementById('cargador').style.display = 'none';
            const mensaje = document.getElementById('mensaje');
            mensaje.className = 'error';
            mensaje.textContent = error.message || 'No fue posible generar el reporte PDF.';
        });
    </script>
</body>
</html>
