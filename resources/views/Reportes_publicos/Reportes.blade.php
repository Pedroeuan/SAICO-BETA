<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $cliente->Cliente }}</title>


    <style>

        body {

            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;

            background-image:
                linear-gradient(
                    rgba(255, 255, 255, 0.55),
                    rgba(255, 255, 255, 0.55)
                ),
                url('{{ asset('images/fondo.png') }}');

            background-size: cover;
            background-position: center 0%;
            background-repeat: no-repeat;
            background-attachment: fixed;

            min-height: 100vh;

        }

        /*
        |--------------------------------------------------------------------------
        | ENCABEZADO
        |--------------------------------------------------------------------------
        */

        .header-navbar {

            position: relative;

            height: 80px;

            background-color: #ffffff;

            padding: 15px 30px;

            box-shadow: 0 2px 4px rgba(0,0,0,0.1);

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO AICO
        |--------------------------------------------------------------------------
        */

        .logo-aico {

            position: absolute;

            left: 30px;

            top: 15px;

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO SAICO
        |--------------------------------------------------------------------------
        */

        .logo-centro {

            position: absolute;

            left: 50%;

            top: 15px;

            transform: translateX(-50%);

        }


        /*
        |--------------------------------------------------------------------------
        | IMÁGENES DEL ENCABEZADO
        |--------------------------------------------------------------------------
        */

        .header-navbar img {

            max-height: 50px;

            width: auto;

        }


        /*
        |--------------------------------------------------------------------------
        | CONTENIDO
        |--------------------------------------------------------------------------
        */

        .content {

            padding: 30px 20px;

            text-align: center;

        }


        /*
        |--------------------------------------------------------------------------
        | LOGO DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        .logo-cliente {

            margin-bottom: 20px;

        }


        .logo-cliente img {

            max-width: 300px;

            max-height: 120px;

            width: auto;

            height: auto;

            object-fit: contain;

        }


        /*
        |--------------------------------------------------------------------------
        | NOMBRE DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        .content h1 {

            margin-top: 10px;

            margin-bottom: 10px;

        }

 /* ==============================
            CONTRATOS
        ============================== */

        .contratos-container {
            max-width: 1100px;

            margin: 0 auto;

            background: rgba(255,255,255,0.96);

            padding: 30px;

            border-radius: 15px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .contratos-container h2 {
            margin-top: 0;

            margin-bottom: 30px;

            color: #333;
        }

        .contratos-grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));

            gap: 20px;

        }

        .contrato-card {

            background: rgba(255, 255, 255, 0.38);

            backdrop-filter: blur(6px);

            -webkit-backdrop-filter: blur(6px);

            border-radius: 12px;

            padding: 25px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.12);

            transition: 0.2s;

            text-align: left;

            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .contrato-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 6px 18px rgba(0,0,0,0.18);
        }

        .contrato-card h3 {

            margin-top: 0;

            color: #1f4e79;

            font-size: 22px;
        }

        .contrato-card p {

            color: #666;

            margin: 8px 0;
        }

        .detalle-item {
            margin: 8px 0;
            color: #444;
        }

        .detalle-label {
            font-weight: 700;
            color: #000000;
        }

        .detalle-valor {
            color: #0b5a8a;
            font-weight: 600;
        }

        .detalle-valor.success {
            color: #1f7a1f;
        }

        .detalle-valor.warning {
            color: #a16207;
        }

        .detalle-valor.danger {
            color: #b42318;
        }

        .comentarios-box {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid rgba(31, 78, 121, 0.18);
        }

        .comentario-login {
            margin-top: 14px;
            padding: 16px;
            border: 1px solid rgba(31, 78, 121, 0.18);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.65);
            color: #334155;
            text-align: center;
        }

        .comentario-login p {
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #475569;
        }

        .comentarios-box label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #1f4e79;
        }

        .comentarios-box textarea {
            width: 100%;
            min-height: 80px;
            resize: vertical;
            border: 1px solid rgba(31, 78, 121, 0.25);
            border-radius: 8px;
            padding: 10px 12px;
            font: inherit;
            background: rgba(255,255,255,0.7);
            box-sizing: border-box;
        }

        .comentarios-box textarea:focus {
            outline: 2px solid rgba(31, 78, 121, 0.2);
            border-color: #1f4e79;
        }

        .comentarios-actions {
            margin-top: 10px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-comentario {
            background: #1f4e79;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 9px 14px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-comentario:hover {
            background: #163a5c;
        }

        .historial-comentarios {
            margin-top: 20px;
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid rgba(31, 78, 121, 0.15);
            border-radius: 8px;
            background: rgba(255,255,255,0.5);
            padding: 12px;
        }

        .historial-comentarios.vacio {
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 13px;
        }

        .comentario-item {
            padding: 12px;
            border-bottom: 1px solid rgba(31, 78, 121, 0.1);
            font-size: 13px;
            line-height: 1.5;
        }

        .comentario-item:last-child {
            border-bottom: none;
        }

        .comentario-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .comentario-autor {
            font-weight: 600;
            color: #1f4e79;
        }

        .comentario-tipo {
            background: rgba(31, 78, 121, 0.15);
            color: #1f4e79;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }

        .comentario-fecha {
            color: #999;
            font-size: 12px;
        }

        .comentario-texto {
            color: #333;
            word-break: break-word;
        }

        .reporte-actions {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            margin-top: 15px;

        }

        .btn-contrato {

            display: inline-block;

            padding: 10px 18px;

            background-color: #1f4e79;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            transition: 0.2s;

            border: none;

            cursor: pointer;

            font-size: 14px;

        }

        .btn-contrato:hover {

            background-color: #163a5c;

            color: white;
        }

        .btn-contrato.secondary {

            background-color: #2e7d32;

        }

        .btn-contrato.secondary:hover {

            background-color: #225d26;

        }

        .btn-contrato:disabled {

            opacity: 0.7;

            cursor: wait;

        }

        .loader-overlay {

            position: fixed;

            inset: 0;

            background: rgba(15, 23, 42, 0.65);

            display: none;

            align-items: center;

            justify-content: center;

            z-index: 9999;

        }

        .loader-overlay.show {

            display: flex;

        }

        .loader-box {

            background: rgba(255,255,255,0.96);

            color: #1f2937;

            border-radius: 16px;

            padding: 24px 30px;

            text-align: center;

            box-shadow: 0 10px 30px rgba(0,0,0,0.2);

            min-width: 220px;

        }

        .loader-spinner {

            width: 42px;

            height: 42px;

            margin: 0 auto 12px auto;

            border: 4px solid rgba(31, 78, 121, 0.2);

            border-top-color: #1f4e79;

            border-radius: 50%;

            animation: spin 0.9s linear infinite;

        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .reporte-pdf {

            width: 100%;

            min-height: 600px;

            margin-top: 20px;

            border: 1px solid #e5e5e5;

        }

        /* ==============================
            SIN CONTRATOS
        ============================== */

        .sin-contratos {

            padding: 30px;

            color: #777;

            text-align: center;
        }

        /* ==============================
            RESPONSIVE
        ============================== */

        @media(max-width: 768px) {

            .header-navbar {

                padding: 10px 15px;

            }

            .header-navbar img {

                max-height: 45px;

            }

            .bienvenida h1 {

                font-size: 25px;

            }

            .contratos-container {

                padding: 20px;

            }

        }

    </style>

</head>


<body>


    <!--
    |--------------------------------------------------------------------------
    | ENCABEZADO
    |--------------------------------------------------------------------------
    -->

    <header class="header-navbar">


        <!-- LOGO AICO - IZQUIERDA -->

        <div class="logo-aico">

            <img src="{{ asset('images/Logo_AICO_R.jpg') }}"
                alt="Logo AICO">

        </div>


        <!-- SAICO - CENTRO -->

        <div class="logo-centro">

            <img src="{{ asset('images/saico3.png') }}"
                alt="Logo SAICO">

        </div>


    </header>



    <!--
    |--------------------------------------------------------------------------
    | CONTENIDO
    |--------------------------------------------------------------------------
    -->

    <main class="content">


        <!-- LOGO DEL CLIENTE -->

        <div class="logo-cliente">

            @if($cliente->logo)

                <img src="{{ asset('storage/' . $cliente->logo) }}"
                    alt="{{ $cliente->Cliente }}">

            @else

                <h2>
                    {{ $cliente->Cliente }}
                </h2>

            @endif

        </div>


        <!-- BIENVENIDA -->

        <h1>
            Bienvenido, {{ $cliente->Cliente }}
        </h1>


        <p>
            Este es el portal de {{ $cliente->Cliente }}.
        </p>

        <p>
            Contrato seleccionado: <strong>{{ $orden->Contrato }}</strong>
        </p>
        <!-- ==================================
            REPORTES
        =================================== -->

        <div class="reportes-container">

            <h2>
                Mis Reportes
            </h2>

            @forelse($reportes as $reporte)
                <div class="contrato-card">
                    <h3>
                        {{ $reporte->detalles['No_Reporte'] ?? 'Reporte #' . $reporte->idReportes }}
                    </h3>
                    <p class="detalle-item">
                        <span class="detalle-label">Fecha:</span>
                        <span class="detalle-valor success">{{ $reporte->detalles['Fecha'] ?? 'Sin fecha' }}</span>

                        <span class="detalle-label">Número de isométrico:</span>
                        <span class="detalle-valor warning">{{ $reporte->detalles['No_Isometrico'] ?? 'Sin número' }}</span>

                        <span class="detalle-label">No. Junta:</span>
                        <span class="detalle-valor danger">{{ $reporte->detalles['No_Junta'] ?? 'Sin número' }}</span>

                        <span class="detalle-label">Nombre de la pieza:</span>
                        <span class="detalle-valor">{{ $reporte->detalles['Nom_Pieza'] ?? 'Sin nombre' }}</span>

                        <span class="detalle-label">Estado:</span>
                        <span class="detalle-valor success">{{ $reporte->Estatus ?? 'Sin estado' }}</span>
                    </p>
                    <p class="detalle-item">
                        <span class="detalle-label">Proyecto:</span>
                        <span class="detalle-valor">{{ $reporte->detalles['Proyecto'] ?? $orden->Proyecto_actividad }}</span>
                    </p>

                    <div class="reporte-actions">
                        <a href="{{ route('Reportes.Clientes.Pdf', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio, 'idReporte' => $reporte->idReportes]) }}"
                            class="btn-contrato"
                            target="_blank"
                            rel="noopener"
                            data-pdf-action="view"
                            data-pdf-url="{{ route('Reportes.Clientes.Pdf', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio, 'idReporte' => $reporte->idReportes]) }}">
                            Ver PDF
                        </a>
                        <a href="{{ route('Reportes.Clientes.Pdf', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio, 'idReporte' => $reporte->idReportes]) }}"
                            class="btn-contrato secondary"
                            data-pdf-action="download"
                            data-pdf-url="{{ route('Reportes.Clientes.Pdf', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio, 'idReporte' => $reporte->idReportes]) }}"
                            download>
                            Descargar
                        </a>
                    </div>

                    @auth
                        <div class="comentarios-box">
                            <label for="comentario-{{ $reporte->idReportes }}">Comentarios</label>
                            <textarea id="comentario-{{ $reporte->idReportes }}"
                                data-reporte-id="{{ $reporte->idReportes }}"
                                data-comentario-url="{{ route('portal.reporte.comentario', ['token' => request()->route('token'), 'idReporte' => $reporte->idReportes], false) }}"
                                data-comentarios-url="{{ route('portal.reporte.comentarios', ['token' => request()->route('token'), 'idReporte' => $reporte->idReportes], false) }}"
                                placeholder="Escribe un comentario para este reporte..."></textarea>
                            <div class="comentarios-actions">
                                <button type="button" class="btn-comentario" data-save-comment="{{ $reporte->idReportes }}">Guardar comentario</button>
                            </div>

                            <div class="historial-comentarios vacio" id="historial-{{ $reporte->idReportes }}">
                                <p>No hay comentarios aún</p>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <div class="comentario-login">
                            <p>Inicia sesión para dejar un comentario sobre este reporte.</p>
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}" class="btn-contrato secondary">Iniciar sesión</a>
                        </div>
                    @endguest
                    {{--<iframe
                        class="reporte-pdf"
                        src="{{ route('Reportes.Clientes.Pdf', ['token' => request()->route('token'), 'idOrden_Servicio' => $orden->idOrden_Servicio, 'idReporte' => $reporte->idReportes]) }}"
                        title="PDF del reporte {{ $reporte->idReportes }}"
                    ></iframe>--}}
                </div>
            @empty
                <div class="sin-contratos">
                    <h3>No hay reportes registrados</h3>
                    <p>Este contrato todavía no tiene reportes disponibles.</p>
                </div>
            @endforelse


        </div>
    </main>

    <div id="pdf-loader" class="loader-overlay" role="status" aria-live="polite" aria-busy="true">
        <div class="loader-box">
            <div class="loader-spinner"></div>
            <div id="loader-text">Preparando PDF...</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overlay = document.getElementById('pdf-loader');
            const loaderText = document.getElementById('loader-text');
            const pdfLinks = document.querySelectorAll('[data-pdf-action]');

            const showLoader = (message) => {
                if (!overlay) return;
                loaderText.textContent = message;
                overlay.classList.add('show');
            };

            const hideLoader = () => {
                if (!overlay) return;
                overlay.classList.remove('show');
            };

            document.querySelectorAll('[data-save-comment]').forEach((button) => {

                button.addEventListener('click', function () {

                    const reporteId = button.dataset.saveComment;

                    const textarea = document.querySelector(
                        'textarea[data-reporte-id="' + reporteId + '"]'
                    );

                    if (!textarea) return;

                    const comentario = textarea.value;

                    if (!comentario.trim()) {
                        alert('Por favor escribe un comentario');
                        return;
                    }

                    // Deshabilitar botón mientras se guarda
                    button.disabled = true;
                    button.textContent = 'Guardando...';

                    const comentarioUrl = textarea.dataset.comentarioUrl;

                    if (!comentarioUrl) {
                        button.disabled = false;
                        button.textContent = 'Guardar comentario';
                        console.error('No se encontró la URL para guardar el comentario.');
                        return;
                    }

                    fetch(comentarioUrl, {

                        method: 'POST',

                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },

                        body: JSON.stringify({
                            comentario: comentario
                        })

                    })
                    .then(async response => {

                        if (response.status === 401) {
                            const data = await response.json().catch(() => ({}));
                            window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window.location.href);
                            throw new Error(data.message || 'Debes iniciar sesión para comentar.');
                        }

                        if (!response.ok) {
                            throw new Error('Error al guardar el comentario');
                        }

                        return response.json();

                    })
                    .then(data => {

                        if (data.success) {

                            // Limpiar textarea
                            textarea.value = '';

                            button.textContent = '✓ Comentario guardado';

                            // Mostrar el comentario guardado inmediatamente.
                            cargarComentarios(reporteId);

                            setTimeout(() => {
                                button.textContent = 'Guardar comentario';
                            }, 1500);

                        }

                    })
                    .catch(error => {

                        console.error(error);

                        button.textContent = 'Error al guardar';

                        alert('No fue posible guardar el comentario.');

                        setTimeout(() => {
                            button.textContent = 'Guardar comentario';
                        }, 1500);

                    })
                    .finally(() => {

                        button.disabled = false;

                    });

                });

            });

            function renderComentarios(reporteId, comentarios) {
                const historialDiv = document.getElementById('historial-' + reporteId);

                if (!historialDiv) return;

                if (!comentarios || comentarios.length === 0) {
                    historialDiv.classList.add('vacio');
                    historialDiv.innerHTML = '<p>No hay comentarios todavia</p>';
                    return;
                }

                historialDiv.classList.remove('vacio');
                historialDiv.innerHTML = '';

                comentarios.forEach(comentario => {
                    const item = document.createElement('div');
                    item.className = 'comentario-item';
                    item.innerHTML = `
                        <div class="comentario-header">
                            <span class="comentario-autor"></span>
                            <span class="comentario-tipo">${comentario.tipo_autor === 'cliente' ? 'Cliente' : 'Interno'}</span>
                        </div>
                        <div class="comentario-fecha"></div>
                        <div class="comentario-texto"></div>
                    `;
                    item.querySelector('.comentario-autor').textContent = comentario.autor || '';
                    item.querySelector('.comentario-fecha').textContent = comentario.fecha || '';
                    item.querySelector('.comentario-texto').textContent = comentario.comentario || '';
                    historialDiv.appendChild(item);
                });
            }

            // Función para cargar comentarios
            function cargarComentarios(reporteId) {
                const textarea = document.querySelector('textarea[data-reporte-id="' + reporteId + '"]');
                const comentariosUrl = textarea && textarea.dataset.comentariosUrl;

                if (!comentariosUrl) {
                    return Promise.reject(new Error('No se encontró la URL de comentarios del reporte.'));
                }

                fetch(comentariosUrl, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('No fue posible cargar los comentarios (' + response.status + ')');
                    }
                    return response.json();
                })
                .then(data => {
                    renderComentarios(reporteId, data.comentarios || []);
                })
                .catch(error => console.error('Error al cargar comentarios:', error));
            }

            // Cargar comentarios al cargar la página
            document.querySelectorAll('[data-reporte-id]').forEach(textarea => {
                const reporteId = textarea.dataset.reporteId;
                cargarComentarios(reporteId);
            });
            /*
            const saveComment = (reporteId, value) => {
                const storageKey = 'reporte_comentario_' + reporteId;
                localStorage.setItem(storageKey, value);
            };

            const loadComment = (reporteId) => {
                const storageKey = 'reporte_comentario_' + reporteId;
                const value = localStorage.getItem(storageKey);
                const textarea = document.querySelector('textarea[data-reporte-id="' + reporteId + '"]');

                if (textarea && value !== null) {
                    textarea.value = value;
                }
            };

            document.querySelectorAll('textarea[data-reporte-id]').forEach((textarea) => {
                const reporteId = textarea.dataset.reporteId;
                loadComment(reporteId);

                textarea.addEventListener('input', function () {
                    saveComment(reporteId, textarea.value);
                });
            });

            document.querySelectorAll('[data-save-comment]').forEach((button) => {
                button.addEventListener('click', function () {
                    const reporteId = button.dataset.saveComment;
                    const textarea = document.querySelector('textarea[data-reporte-id="' + reporteId + '"]');

                    if (!textarea) return;

                    saveComment(reporteId, textarea.value);
                    button.textContent = 'Comentario guardado';

                    setTimeout(() => {
                        button.textContent = 'Guardar comentario';
                    }, 1200);
                });
            });
            */
            pdfLinks.forEach((link) => {
                link.addEventListener('click', function () {
                    const action = link.dataset.pdfAction;
                    const url = link.dataset.pdfUrl;

                    if (!url) return;

                    if (action === 'view') {
                        showLoader('Cargando PDF...');

                        const popup = window.open(url, '_blank', 'noopener');

                        if (!popup) {
                            hideLoader();
                            alert('El navegador bloqueó la ventana emergente. Permite las ventanas para ver el PDF.');
                            return;
                        }

                        setTimeout(() => {
                            hideLoader();
                        }, 2000);
                    }

                    if (action === 'download') {
                        showLoader('Descargando PDF...');

                        setTimeout(() => {
                            hideLoader();
                        }, 2500);
                    }
                });
            });

            window.addEventListener('focus', hideLoader);
        });
    </script>

</body>

</html>