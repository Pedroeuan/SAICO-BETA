<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Generando PDF</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 60px 20px; color: #334155; }
        .spinner { width: 42px; height: 42px; border: 4px solid #e2e8f0; border-top-color: #2563eb;
                   border-radius: 50%; animation: girar 1s linear infinite; margin: 0 auto 20px; }
        @keyframes girar { to { transform: rotate(360deg); } }
        h1 { font-size: 20px; margin: 0 0 8px; }
        p { color: #64748b; margin: 0 0 6px; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
    <div class="spinner" id="spinner"></div>
    <h1 id="titulo">Generando el reporte PDF...</h1>
    <p>Reporte #{{ $reporteId }}. Este paso puede tomar un par de minutos la primera vez.</p>
    <p id="estado">El PDF se abrirá automáticamente cuando esté listo.</p>

    <script>
        const url = @json($url);
        const reporteId = {{ (int) $reporteId }};
        let intentos = 0;
        const maxIntentos = 90;

        const abrir = (blob) => {
            const objeto = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = objeto;
            a.download = 'reporte_' + reporteId + '.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => URL.revokeObjectURL(objeto), 60000);
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('titulo').textContent = 'PDF listo';
            document.getElementById('estado').textContent = 'Si no se abrió, descárgalo desde la barra inferior.';
        };

        const esperar = () => {
            if (intentos++ >= maxIntentos) {
                document.getElementById('spinner').style.display = 'none';
                document.getElementById('titulo').className = 'error';
                document.getElementById('titulo').textContent = 'La generación está tardando más de lo normal';
                document.getElementById('estado').innerHTML =
                    'Puedes <a href="' + url + '">reintentar recargando esta página</a>.';
                return;
            }

            fetch(url, { credentials: 'same-origin', cache: 'no-store' })
                .then(r => {
                    if ((r.headers.get('content-type') || '').includes('application/pdf')) {
                        return r.blob().then(abrir);
                    }
                    setTimeout(esperar, 4000);
                })
                .catch(() => setTimeout(esperar, 4000));
        };

        setTimeout(esperar, 4000);
    </script>
</body>
</html>
