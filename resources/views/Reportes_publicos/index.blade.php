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

            background: white;

            border-radius: 12px;

            padding: 25px;

            box-shadow:
                0 3px 10px rgba(0,0,0,0.12);

            transition: 0.2s;

            text-align: left;

            border: 1px solid #e5e5e5;
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

        .btn-contrato {

            display: inline-block;

            margin-top: 15px;

            padding: 10px 18px;

            background-color: #1f4e79;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            transition: 0.2s;
        }

        .btn-contrato:hover {

            background-color: #163a5c;

            color: white;
        }
        .servicio-proyecto {
            padding: 12px 0;
            border-top: 1px solid #e5e7eb;
        }

        .servicio-proyecto:first-of-type {
            border-top: 0;
            padding-top: 0;
        }

        .servicio-proyecto p { 
            margin: 0 0 8px; 
        }

        .btn-encuesta-pdf {
            display: inline-block;
            margin: 8px 0 0 8px;
            padding: 10px 14px;
            border-radius: 6px;
            color: #fff;
            background: #0f766e;
            text-decoration: none;
        }

        .encuesta-realizada { 
            margin: 9px 0 0; 
            color: #0f766e !important; 
            font-size: 13px; 
            font-weight: 700; 
        }

        /* ==============================
            SIN CONTRATOS
        ============================== */

        .sin-contratos {

            padding: 30px;

            color: #777;

            text-align: center;
        }

        .encuesta-modal {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(0, 35, 75, .72);
            overflow-y: auto;
        }

        .encuesta-panel {
            position: relative;
            width: min(860px, 100%);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            padding: 28px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .30);
            text-align: left;
        }

        .encuesta-panel h2 { 
            color: #003b80; 
            margin: 0 0 8px; 
        }
        .cerrar-encuesta { 
            position: absolute; 
            top: 12px; 
            right: 14px; 
            width: 34px; 
            height: 34px; 
            border: 0; 
            border-radius: 50%; 
            color: #4b5563; 
            background: #eef2f7; 
            font-size: 25px; 
            line-height: 28px; 
            cursor: pointer; 
        }
        .cerrar-encuesta:hover { 
            color: #fff; 
            background: #e01a22; 
        }
        .encuesta-panel .encuesta-contexto { 
            color: #5f6770; 
            margin: 0 0 24px; 
        }
        .encuesta-error { 
            padding: 12px; 
            margin-bottom: 18px; 
            color: #991b1b; 
            background: #fef2f2; 
            border-radius: 8px; 
        }
        .pregunta-encuesta { 
            padding: 18px 0; 
            border-top: 1px solid #e5e7eb; 
        }
        .pregunta-encuesta legend { 
            margin-bottom: 12px; 
            font-weight: 700; 
            color: #26323f; 
        }
        .control-volumen { 
            max-width: 560px; 
            padding: 10px 4px 2px; 
        }
        .logos-nivel { 
            display: flex; 
            justify-content: space-between; 
            padding: 0 3px; 
            margin-bottom: 5px; 
        }
        .logo-nivel { 
            display: flex; 
            width: 54px; 
            flex-direction: column; 
            align-items: center; 
            color: #94a3b8; 
            font-size: 13px; 
            transition: .18s; 
        }
        .logo-nivel.activo { 
            color: #003b80; 
            font-weight: 700; 
        }
        .rango-satisfaccion { 
            width: 100%; 
            height: 26px; 
            margin: 0; 
            cursor: pointer; 
            appearance: none; 
            background: transparent; 
        }
        .rango-satisfaccion::-webkit-slider-runnable-track { 
            height: 6px; 
            border-radius: 999px; 
            background: linear-gradient(to right, #003b80 0 var(--progreso), #cbd5e1 var(--progreso) 100%); 
        }
        .rango-satisfaccion::-webkit-slider-thumb { 
            width: 32px; 
            height: 32px; 
            margin-top: -13px; 
            border: 2px solid #fff; 
            border-radius: 50%; 
            appearance: none; 
            background: #fff url('{{ asset('images/Logo_AICO_R.jpg') }}') center / 27px auto no-repeat; 
            box-shadow: 0 1px 5px rgba(0, 0, 0, .40); 
        }
        .rango-satisfaccion::-moz-range-track { 
            height: 6px; 
            border-radius: 999px; 
            background: #cbd5e1; 
        }
        .rango-satisfaccion::-moz-range-progress { 
            height: 6px; 
            border-radius: 999px; 
            background: #003b80; 
        }
        .rango-satisfaccion::-moz-range-thumb { 
            width: 28px; 
            height: 28px; 
            border: 2px solid #fff; 
            border-radius: 50%; 
            background: #fff url('{{ asset('images/Logo_AICO_R.jpg') }}') center / 24px auto no-repeat; 
            box-shadow: 0 1px 5px rgba(0, 0, 0, .40); 
        }
        .rango-satisfaccion:focus-visible { 
            outline: 3px solid #e01a22; 
            outline-offset: 4px; 
            border-radius: 5px; 
        }
        .nivel-seleccionado { 
            margin: 3px 0 0; 
            color: #003b80; 
            font-size: 13px; 
            font-weight: 700; 
            text-align: center; 
        }
        .comentario-encuesta { 
            width: 100%; 
            min-height: 92px; 
            box-sizing: border-box; 
            padding: 10px; 
            border: 1px solid #b8c0cc; 
            border-radius: 8px; 
            font: inherit; 
        }
        .btn-enviar-encuesta { 
            margin-top: 20px; 
            padding: 12px 20px; 
            border: 0; 
            border-radius: 7px; 
            color: #fff; 
            background: #e01a22; 
            font: inherit; 
            font-weight: 700; 
            cursor: pointer; 
        }
        .btn-enviar-encuesta:hover { 
            background: #b8141c; 
        }
        .firma-encuesta { 
            margin-top: 22px; 
            padding-top: 18px; 
            border-top: 1px solid #e5e7eb; 
        }
        .firma-encuesta input[type="text"] { 
            width: 100%; 
            box-sizing: border-box; 
            margin: 8px 0 12px; 
            padding: 10px; 
            border: 1px solid #b8c0cc; 
            border-radius: 8px; 
            font: inherit; 
        }
        .lienzo-firma { 
            display: block; 
            width: 100%; 
            height: 160px; 
            box-sizing: border-box; 
            border: 2px dashed #94a3b8; 
            border-radius: 8px; 
            background: #f8fafc; 
            cursor: crosshair; 
            touch-action: none; 
        }
        .acciones-firma { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: 12px; 
            margin-top: 8px; 
        }
        .btn-limpiar-firma { 
            border: 0; 
            color: #003b80; 
            background: transparent; 
            font: inherit; 
            font-weight: 700; 
            cursor: pointer; 
            text-decoration: underline; 
        }
        .confirmacion-firma { 
            display: block; 
            margin-top: 14px; 
            font-size: 13px; 
        }
        .btn-descargar-encuesta { 
            display: inline-block; 
            margin-top: 12px; 
            padding: 11px 16px; 
            border-radius: 7px; 
            color: #fff; 
            background: #003b80; 
            text-decoration: none; 
            font-weight: 700; 
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
            {{-- Este es el portal de {{ $cliente->Cliente }}.  --}}
        </p>

        <!-- ==================================
            CONTRATOS
        =================================== -->

        <div class="contratos-container">

            <h2>
                Mis contratos / Proyectos
            </h2>


            @if($contratos->count() > 0)

                <div class="contratos-grid">

                    @foreach($contratos as $nombreContrato => $proyectos)

                        <div class="contrato-card">
                            <p>
                                <strong>Contrato:</strong>
                            </p>
                            <h3>
                                {{ $nombreContrato }}
                            </h3>

                            @foreach($proyectos as $proyecto)
                                <div class="servicio-proyecto">
                                    <p><strong>Proyecto / Actividad:</strong> {{ $proyecto->Proyecto_actividad }}</p>
                                    <a href="{{ route('Reportes.Clientes',['token' => request()->route('token'),'idOrden_Servicio' => $proyecto->idOrden_Servicio]) }}" class="btn-contrato">Ver Reportes</a>
                                </div>
                            @endforeach
                        </div>

                    @endforeach

                </div>

            @else

                <div class="sin-contratos">

                    <h3>
                        No hay contratos registrados
                    </h3>

                    <p>
                        Actualmente no existen contratos asociados a este cliente.
                    </p>

                </div>

            @endif

        </div>
    </main>

    @if(session('encuesta_guardada'))
        <div class="encuesta-modal" role="status" aria-live="polite">
            <div class="encuesta-panel">
                <h2>¡Gracias por tu respuesta!</h2>
                <p>{{ session('encuesta_guardada') }}</p>
                @if(session('encuesta_id_descarga'))
                    <a class="btn-descargar-encuesta" href="{{ route('portal.encuesta.pdf', ['token' => request()->route('token'), 'idEncuesta' => session('encuesta_id_descarga')]) }}">Descargar encuesta firmada en PDF</a>
                @endif
            </div>
        </div>
    @elseif($encuestaPendiente)
        @php
            $preguntasEncuesta = [
                '¿Cómo califica el servicio que le brindó AICO S.C.?',
                '¿Cómo considera la atención del personal de ventas?',
                '¿Cómo es el trato y la atención del personal hacia usted y/o sus representantes?',
                '¿Considera que todo el personal se encuentra capacitado e idóneo para realizar los servicios?',
                '¿Cómo califica las instalaciones, elementos, productos o equipos empleados en el servicio?',
                '¿Se utiliza adecuadamente el equipo de protección personal por el personal de AICO S.C.?',
                '¿Qué tan eficiente considera la entrega de los reportes?',
                '¿En qué escala considera el profesionalismo del servicio brindado?',
                '¿Recomendaría los servicios que AICO S.C. le brindó?',
                '¿El servicio que le brindó AICO S.C. cumplió con sus expectativas?',
            ];
            $nivelesSatisfaccion = [1 => 'Malo', 2 => 'Regular', 3 => 'Aceptable', 4 => 'Bueno', 5 => 'Excelente'];
        @endphp
        <div class="encuesta-modal" role="dialog" aria-modal="true" aria-labelledby="titulo-encuesta">
            <form class="encuesta-panel" method="POST" action="{{ route('portal.encuesta.store', ['token' => request()->route('token')]) }}">
                @csrf
                <button class="cerrar-encuesta" type="button" data-cerrar-encuesta aria-label="Cerrar encuesta">×</button>
                <input type="hidden" name="idOrden_Servicio" value="{{ $encuestaPendiente->idOrden_Servicio }}">
                <h2 id="titulo-encuesta">Encuesta de satisfacción</h2>
                <p class="encuesta-contexto">Contrato: {{ $encuestaPendiente->Contrato ?: 'Sin contrato' }} · Proyecto: {{ $encuestaPendiente->Proyecto_actividad ?: 'Sin proyecto' }}</p>

                @if($errors->any())
                    <div class="encuesta-error">Por favor contesta las diez preguntas antes de enviar la encuesta.</div>
                @endif

                @foreach($preguntasEncuesta as $indice => $pregunta)
                    @php $campo = 'PREGUNTA_' . ($indice + 1); @endphp
                    <fieldset class="pregunta-encuesta">
                        <legend>{{ $indice + 1 }}. {{ $pregunta }}</legend>
                        @php
                            $valorAnterior = old('Preguntas.' . $campo);
                            $valorActual = $valorAnterior ?? 3;
                        @endphp
                        <div class="control-volumen" data-control-volumen>
                            <div class="logos-nivel" aria-hidden="true">
                                @foreach($nivelesSatisfaccion as $valor => $nivel)
                                    <span class="logo-nivel" data-nivel="{{ $valor }}">{{ $valor }}</span>
                                @endforeach
                            </div>
                            <input type="hidden" name="Preguntas[{{ $campo }}]" value="{{ $valorAnterior }}" data-respuesta>
                            <input class="rango-satisfaccion" type="range" min="1" max="5" step="1" value="{{ $valorActual }}" data-respondida="{{ $valorAnterior === null ? '0' : '1' }}" aria-label="Calificación para la pregunta {{ $indice + 1 }}">
                            <p class="nivel-seleccionado" data-texto-nivel></p>
                        </div>
                    </fieldset>
                @endforeach

                <label for="Comentario"><strong>¿Desea realizar algún comentario adicional?</strong></label>
                <textarea class="comentario-encuesta" id="Comentario" name="Comentario" maxlength="2000">{{ old('Comentario') }}</textarea>

                <section class="firma-encuesta">
                    <label for="nombre-firma"><strong>Nombre de quien firma</strong></label>
                    <input id="nombre-firma" type="text" name="Firma[nombre]" maxlength="200" value="{{ old('Firma.nombre') }}" required>
                    <p><strong>Firma del cliente</strong><br><small>Firme dentro del recuadro con el mouse o con el dedo.</small></p>
                    <canvas class="lienzo-firma" data-lienzo-firma></canvas>
                    <input type="hidden" name="Firma[imagen]" value="{{ old('Firma.imagen') }}" data-imagen-firma>
                    <div class="acciones-firma">
                        <small data-estado-firma>Firma pendiente</small>
                        <button class="btn-limpiar-firma" type="button" data-limpiar-firma>Limpiar firma</button>
                    </div>
                    <label class="confirmacion-firma">
                        <input type="checkbox" name="Firma[confirmacion]" value="1" required @checked(old('Firma.confirmacion'))>
                        Confirmo que la información de esta encuesta es correcta y que esta firma corresponde a mi autorización.
                    </label>
                </section>
                <button class="btn-enviar-encuesta" type="submit">Enviar encuesta</button>
            </form>
        </div>
    @endif

    <script>
        document.querySelectorAll('[data-cerrar-encuesta]').forEach(function (boton) {
            boton.addEventListener('click', function () {
                boton.closest('.encuesta-modal').style.display = 'none';
            });
        });

        document.querySelectorAll('[data-control-volumen]').forEach(function (control) {
            const rango = control.querySelector('.rango-satisfaccion');
            const texto = control.querySelector('[data-texto-nivel]');
            const respuesta = control.querySelector('[data-respuesta]');
            const etiquetas = { 1: 'Malo', 2: 'Regular', 3: 'Aceptable', 4: 'Bueno', 5: 'Excelente' };

            function actualizarNivel() {
                const valor = Number(rango.value);
                const respondida = rango.dataset.respondida === '1';
                const progreso = respondida ? ((valor - 1) / 4) * 100 : 0;
                rango.style.setProperty('--progreso', progreso + '%');
                texto.textContent = respondida ? valor + ' · ' + etiquetas[valor] : 'Desliza el logo AICO para calificar';

                control.querySelectorAll('[data-nivel]').forEach(function (logo) {
                    logo.classList.toggle('activo', respondida && Number(logo.dataset.nivel) <= valor);
                });
            }

            rango.addEventListener('input', function () {
                rango.dataset.respondida = '1';
                respuesta.value = rango.value;
                actualizarNivel();
            });
            actualizarNivel();
        });

        document.querySelectorAll('[data-lienzo-firma]').forEach(function (lienzo) {
            const formulario = lienzo.closest('form');
            const imagen = formulario.querySelector('[data-imagen-firma]');
            const estado = formulario.querySelector('[data-estado-firma]');
            const limpiar = formulario.querySelector('[data-limpiar-firma]');
            const contexto = lienzo.getContext('2d');
            let dibujando = false;
            let tieneFirma = false;

            function prepararLienzo() {
                const rectangulo = lienzo.getBoundingClientRect();
                const escala = window.devicePixelRatio || 1;
                lienzo.width = rectangulo.width * escala;
                lienzo.height = rectangulo.height * escala;
                contexto.scale(escala, escala);
                contexto.lineWidth = 2;
                contexto.lineCap = 'round';
                contexto.strokeStyle = '#003b80';
            }

            function punto(evento) {
                const rectangulo = lienzo.getBoundingClientRect();
                return { x: evento.clientX - rectangulo.left, y: evento.clientY - rectangulo.top };
            }

            prepararLienzo();
            lienzo.addEventListener('pointerdown', function (evento) {
                dibujando = true;
                lienzo.setPointerCapture(evento.pointerId);
                const posicion = punto(evento);
                contexto.beginPath();
                contexto.moveTo(posicion.x, posicion.y);
            });
            lienzo.addEventListener('pointermove', function (evento) {
                if (!dibujando) return;
                const posicion = punto(evento);
                contexto.lineTo(posicion.x, posicion.y);
                contexto.stroke();
                tieneFirma = true;
                estado.textContent = 'Firma capturada';
            });
            ['pointerup', 'pointercancel', 'pointerleave'].forEach(function (evento) {
                lienzo.addEventListener(evento, function () { dibujando = false; });
            });
            limpiar.addEventListener('click', function () {
                contexto.clearRect(0, 0, lienzo.width, lienzo.height);
                imagen.value = '';
                tieneFirma = false;
                estado.textContent = 'Firma pendiente';
            });
            formulario.addEventListener('submit', function (evento) {
                if (!tieneFirma) {
                    evento.preventDefault();
                    estado.textContent = 'La firma es obligatoria.';
                    return;
                }
                imagen.value = lienzo.toDataURL('image/png');
            });
        });
    </script>
</body>
</html>
