<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
    .reportes-cabecera { 
        display: flex; 
        align-items: flex-start; 
        justify-content: space-between; 
        gap: 20px; 
        margin-bottom: 20px; 
    }
    .reportes-cabecera h2 { 
        margin: 8px 0 0; 
    }
    .encuesta-servicio { 
        width: min(310px, 100%); 
        padding: 16px; 
        border: 1px solid #cbd5e1; 
        border-radius: 12px; 
        background: rgba(255,255,255,.92); 
        box-shadow: 0 4px 12px rgba(15,23,42,.10); 
        text-align: left; 
    }
    .encuesta-servicio h3 { 
        margin: 0 0 8px; 
        color: #003b80; 
        font-size: 16px; 
    }
    .encuesta-servicio p { 
        margin: 0; 
        color: #475569; 
        font-size: 13px; 
        line-height: 1.45; 
    }
    .encuesta-servicio.realizada { 
        border-color: #0f766e; 
    }
    .btn-encuesta { 
        display: inline-block; 
        margin-top: 12px; 
        padding: 10px 13px; 
        border: 0; 
        border-radius: 7px; 
        color: #fff; 
        background: #003b80; 
        font: inherit; 
        font-size: 13px; 
        font-weight: 700; 
        text-decoration: none; 
        cursor: pointer; 
    }
    .btn-encuesta.realizada { 
        background: #0f766e; 
    }
    .encuesta-modal { 
        position: fixed; 
        inset: 0; 
        z-index: 1000; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        padding: 20px; 
        background: rgba(0,35,75,.72); 
        overflow-y: auto; 
    }
    .encuesta-panel { 
        position: relative; 
        width: min(860px,100%); 
        max-height: calc(100vh - 40px); 
        overflow-y: auto; 
        padding: 28px; 
        border-radius: 16px; 
        background: #fff; 
        box-shadow: 0 18px 45px rgba(0,0,0,.30); 
        text-align: left; 
    }
    .encuesta-panel h2 { 
        margin: 0 0 8px; 
        color: #003b80; 
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
        cursor: pointer; 
    }
    .cerrar-encuesta:hover { 
        color: #fff; 
        background: #e01a22; 
    }
    .encuesta-contexto { 
        margin: 0 0 24px; 
        color: #5f6770; 
    }
    .encuesta-error { 
        padding: 12px; 
        margin-bottom: 18px; 
        color: #991b1b; 
        background: #fef2f2; 
        border-radius: 8px; 
    }

    /* ===== Encuesta por pasos ===== */
    .progreso-encuesta { 
        height: 8px; 
        margin: 0 0 6px; 
        border-radius: 999px; 
        background: #e2e8f0; 
        overflow: hidden; 
    }
    .progreso-encuesta span { 
        display: block; 
        height: 100%; 
        width: 0; 
        background: #003b80; 
        transition: width .3s; 
    }
    .etiqueta-progreso { 
        margin: 0 0 8px; 
        color: #64748b; 
        font-size: 13px; 
        font-weight: 700; 
    }
    .paso-encuesta { 
        min-width: 0;
        margin: 0; 
        padding: 18px 0; 
        border: 0; 
        animation: pasoEntra .25s ease; 
    }
    .paso-encuesta[hidden] { 
        display: none; 
    }
    @keyframes pasoEntra { 
        from { opacity: 0; transform: translateX(16px); } 
        to { opacity: 1; transform: none; } 
    }
    .pregunta-encuesta legend { 
        margin-bottom: 12px; 
        color: #26323f; 
        font-size: 18px; 
        line-height: 1.35; 
        font-weight: 700; 
    }

    /* Riel con el logo AICO que se desliza a la calificación elegida */
    .control-calificacion { 
        max-width: 620px; 
        margin: 0 auto; 
    }
    .riel-calificacion { 
        position: relative; 
        height: 46px; 
    }
    .riel-calificacion::before { 
        content: ''; 
        position: absolute; 
        left: 10%; 
        right: 10%; 
        top: 50%; 
        height: 6px; 
        margin-top: -3px; 
        border-radius: 999px; 
        background: #cbd5e1; 
    }
    .riel-progreso { 
        position: absolute; 
        left: 10%; 
        top: 50%; 
        width: 0; 
        height: 6px; 
        margin-top: -3px; 
        border-radius: 999px; 
        background: #003b80; 
        transition: width .35s cubic-bezier(.4,0,.2,1); 
    }
    .marcador-logo { 
        position: absolute; 
        top: 50%; 
        left: 10%; 
        width: 42px; 
        height: 42px; 
        margin: -21px 0 0 -21px; 
        padding: 3px; 
        box-sizing: border-box; 
        border: 2px solid #fff; 
        border-radius: 50%; 
        background: #fff; 
        object-fit: contain; 
        box-shadow: 0 2px 8px rgba(0,0,0,.40); 
        opacity: 0; 
        transition: left .35s cubic-bezier(.34,1.56,.64,1), opacity .2s; 
        pointer-events: none; 
    }
    .opciones-calificacion { 
        display: grid; 
        grid-template-columns: repeat(5, 1fr); 
        gap: 10px; 
        margin-top: 6px; 
    }
    .opcion-calificacion { 
        position: relative; 
        cursor: pointer; 
    }
    .opcion-calificacion input { 
        position: absolute; 
        inset: 0; 
        opacity: 0; 
        cursor: pointer; 
    }
    .opcion-calificacion .tarjeta { 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 4px; 
        padding: 14px 4px; 
        border: 2px solid #cbd5e1; 
        border-radius: 12px; 
        background: #fff; 
        transition: transform .15s, border-color .15s, background .15s; 
    }
    .opcion-calificacion .carita { 
        font-size: 30px; 
        line-height: 1; 
    }
    .opcion-calificacion .numero { 
        color: #003b80; 
        font-weight: 800; 
    }
    .opcion-calificacion .texto { 
        color: #64748b; 
        font-size: 11px; 
    }
    .opcion-calificacion:hover .tarjeta { 
        transform: translateY(-3px); 
        border-color: #003b80; 
    }
    .opcion-calificacion input:checked + .tarjeta { 
        border-color: #003b80; 
        background: #003b80; 
        transform: scale(1.06); 
    }
    .opcion-calificacion input:checked + .tarjeta .numero, 
    .opcion-calificacion input:checked + .tarjeta .texto { 
        color: #fff; 
    }
    .opcion-calificacion input:focus-visible + .tarjeta { 
        outline: 3px solid #e01a22; 
        outline-offset: 2px; 
    }
    .navegacion-encuesta { 
        min-height: 30px; 
        margin-top: 10px; 
    }
    .btn-atras { 
        border: 0; 
        color: #003b80; 
        background: transparent; 
        font: inherit; 
        font-weight: 700; 
        cursor: pointer; 
        text-decoration: underline; 
    }
    .btn-atras[hidden] { 
        display: none; 
    }

    .comentario-encuesta, .firma-encuesta input[type="text"] { 
        width: 100%; 
        box-sizing: border-box; 
        padding: 10px; 
        border: 1px solid #b8c0cc; 
        border-radius: 8px; 
        font: inherit; 
    }
    .comentario-encuesta { 
        min-height: 92px; 
    }
    .firma-encuesta { 
        margin-top: 22px; 
        padding-top: 18px; 
        border-top: 1px solid #e5e7eb; 
    }
    .firma-encuesta input[type="text"] { 
        margin: 8px 0 12px; 
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
    @media(max-width: 768px) 
    { 
        .reportes-cabecera {
        flex-direction: column; 
        } 
        .encuesta-servicio {
        width: 100%; 
        } 
    }
    @media(max-width: 480px) 
    { 
        .encuesta-panel { padding: 20px 16px; }
        .opciones-calificacion { gap: 6px; }
        .opcion-calificacion .texto { display: none; }
        .opcion-calificacion .carita { font-size: 26px; }
    }
    .opciones-firma { 
        display: flex; 
        gap: 8px; 
        flex-wrap: wrap; 
        margin: 12px 0; 
    }
    .opcion-firma {
        padding: 9px 14px; 
        border: 1px solid #cbd5e1; 
        border-radius: 8px;
        background: #fff; 
        color: #475569; 
        font: inherit; 
        font-size: 13px; 
        font-weight: 700; 
        cursor: pointer;
    }
    .opcion-firma.activa { 
        color: #fff; 
        background: #003b80; 
        border-color: #003b80; 
    }
    .panel-firma[hidden] { 
        display: none; 
    }
    .subida-firma {
        padding: 16px; 
        border: 2px dashed #94a3b8; 
        border-radius: 8px; 
        background: #f8fafc;
    }
    .subida-firma input[type="file"] { 
        display: block; 
        width: 100%; 
        margin-top: 8px; 
    }
    .vista-previa-firma { 
        display: none; 
        max-width: 100%; 
        max-height: 140px; 
        margin-top: 12px; 
    }
    .nota-firma { 
        margin: 8px 0 0; 
        color: #64748b; 
        font-size: 12px; 
        }
</style>

@php
    $firmaCliente = $encuesta ? (json_decode($encuesta->Firmas, true)['CLIENTE'] ?? []) : [];
    $pendienteFirma = $encuesta
        && ($firmaCliente['Metodo'] ?? null) === 'documento'
        && empty($firmaCliente['Archivo']);
@endphp

<div class="encuesta-servicio {{ $encuesta && !$pendienteFirma ? 'realizada' : '' }}">
    <h3>Encuesta de satisfacción</h3>
    @if($pendienteFirma)
        <p>Encuesta guardada. Descárguela, fírmela a mano y súbala aquí para completarla.</p>
        <a class="btn-encuesta" href="{{ route('portal.encuesta.pdf', ['token' => request()->route('token'), 'idEncuesta' => $encuesta->idEncuesta]) }}">1. Descargar para firmar</a>
        <form method="POST" enctype="multipart/form-data" action="{{ route('portal.encuesta.firmada', ['token' => request()->route('token'), 'idEncuesta' => $encuesta->idEncuesta]) }}">
            @csrf
            <input type="file" name="archivo" accept="application/pdf,image/png,image/jpeg" required style="display:block;margin-top:12px;width:100%">
            @error('archivo')<p style="color:#991b1b;margin-top:6px">{{ $message }}</p>@enderror
            <button class="btn-encuesta" type="submit">2. Subir encuesta firmada</button>
        </form>
    @elseif($encuesta)
        <p>Encuesta contestada.<br>Promedio: <strong>{{ number_format($encuesta->Promedio, 2) }} / 5</strong></p>
        <a class="btn-encuesta realizada" href="{{ route('portal.encuesta.pdf', ['token' => request()->route('token'), 'idEncuesta' => $encuesta->idEncuesta]) }}">Descargar PDF firmado</a>
    @elseif($encuestaPendiente)
        <p>Todos los reportes del servicio están firmados. Tu opinión nos ayuda a mejorar.</p>
        <button class="btn-encuesta" type="button" data-abrir-encuesta>Contestar encuesta</button>
    @else
        <p>La encuesta se habilitará cuando se entreguen y firmen todos los reportes de este servicio.</p>
    @endif
</div>

@if($encuestaPendiente)
    @php
        $preguntasEncuesta = [
            '¿Cómo califica el servicio de AICO S.C.?',
            '¿Cómo califica la atención de ventas?',
            '¿Cómo califica el trato y atención del personal?',
            '¿El personal está capacitado para realizar los servicios?',
            '¿Cómo califica las instalaciones y equipos utilizados?',
            '¿Se utiliza correctamente el equipo de protección?',
            '¿Cómo califica la entrega de los reportes?',
            '¿Cómo califica el profesionalismo del servicio?',
            '¿Recomendaría los servicios de AICO S.C.?',
            '¿El servicio cumplió con sus expectativas?',
        ];
        $nivelesSatisfaccion = [1 => 'Malo', 2 => 'Regular', 3 => 'Aceptable', 4 => 'Bueno', 5 => 'Excelente'];
        $caritasSatisfaccion = [1 => '⭐', 2 => '⭐⭐', 3 => '⭐⭐⭐', 4 => '⭐⭐⭐⭐', 5 => '⭐⭐⭐⭐⭐'];
    @endphp
    <div class="encuesta-modal" data-modal-encuesta role="dialog" aria-modal="true" aria-labelledby="titulo-encuesta">
        <form class="encuesta-panel" method="POST" enctype="multipart/form-data" action="{{ route('portal.encuesta.store', ['token' => request()->route('token')]) }}">
            @csrf
            <button class="cerrar-encuesta" type="button" data-cerrar-encuesta aria-label="Cerrar encuesta">×</button>
            <input type="hidden" name="idOrden_Servicio" value="{{ $orden->idOrden_Servicio }}">
            <h2 id="titulo-encuesta">Encuesta de satisfacción</h2>
            <p class="encuesta-contexto">Contrato: {{ $orden->Contrato ?: 'Sin contrato' }} · Proyecto: {{ $orden->Proyecto_actividad ?: 'Sin proyecto' }}</p>
            @if($errors->any())<div class="encuesta-error">Completa las diez preguntas, la firma y la confirmación para enviar la encuesta.</div>@endif

            {{-- Progreso --}}
            <div class="progreso-encuesta"><span data-barra></span></div>
            <p class="etiqueta-progreso" data-etiqueta-paso></p>

            {{-- Pasos 1-10: una pregunta por pantalla --}}
            @foreach($preguntasEncuesta as $indice => $pregunta)
                @php $campo = 'PREGUNTA_' . ($indice + 1); $valorAnterior = old('Preguntas.' . $campo); @endphp
                <fieldset class="pregunta-encuesta paso-encuesta" data-paso>
                    <legend>{{ $indice + 1 }}. {{ $pregunta }}</legend>
                    <div class="control-calificacion">
                        {{-- Riel: el logo AICO se desliza hasta la calificación elegida --}}
                        <div class="riel-calificacion" aria-hidden="true">
                            <span class="riel-progreso" data-riel-progreso></span>
                            <img class="marcador-logo" src="{{ asset('images/Logo_AICO_R.jpg') }}" alt="" data-marcador>
                        </div>
                        <div class="opciones-calificacion">
                            @foreach($nivelesSatisfaccion as $valor => $nivel)
                                <label class="opcion-calificacion">
                                    <input type="radio" name="Preguntas[{{ $campo }}]" value="{{ $valor }}" @checked((string) $valorAnterior === (string) $valor) aria-label="{{ $valor }} · {{ $nivel }}">
                                    <span class="tarjeta">
                                        <span class="carita">{{ $caritasSatisfaccion[$valor] }}</span>
                                        <span class="numero">{{ $valor }}</span>
                                        <span class="texto">{{ $nivel }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </fieldset>
            @endforeach

            {{-- Paso final: comentario + firma + enviar --}}
            <div class="paso-encuesta" data-paso data-paso-final>
                <label for="Comentario"><strong>¿Desea realizar algún comentario adicional?</strong></label>
                <textarea class="comentario-encuesta" id="Comentario" name="Comentario" maxlength="2000">{{ old('Comentario') }}</textarea>
                <section class="firma-encuesta" data-firma>
                    <label for="nombre-firma"><strong>Nombre de quien firma</strong></label>
                    <input id="nombre-firma" type="text" name="Firma[nombre]" maxlength="200" value="{{ old('Firma.nombre') }}" required>

                    <input type="hidden" name="Firma[metodo]" value="{{ old('Firma.metodo', 'dibujar') }}" data-metodo-firma>

                    <p><strong>Elija cómo desea firmar</strong></p>
                    <div class="opciones-firma">
                        <button type="button" class="opcion-firma" data-opcion="dibujar">
    <i class="fas fa-pen"></i>
    Firmar aquí
</button>

<button type="button" class="opcion-firma" data-opcion="imagen">
    <i class="fas fa-image"></i>
    Subir imagen de mi firma
</button>

<button type="button" class="opcion-firma" data-opcion="documento">
    <i class="fas fa-file-signature"></i>
    Subir encuesta firmada
</button>
                    </div>

                    {{-- Opción 1: dibujar --}}
                    <div class="panel-firma" data-panel="dibujar">
                        <small>Firme dentro del recuadro con el mouse o con el dedo.</small>
                        <canvas class="lienzo-firma" data-lienzo-firma></canvas>
                        <input type="hidden" name="Firma[imagen]" value="{{ old('Firma.imagen') }}" data-imagen-firma>
                        <div class="acciones-firma">
                            <small data-estado-firma>Firma pendiente</small>
                            <button class="btn-limpiar-firma" type="button" data-limpiar-firma>Limpiar firma</button>
                        </div>
                    </div>

                    {{-- Opción 2: imagen de la firma --}}
                    <div class="panel-firma" data-panel="imagen" hidden>
                        <div class="subida-firma">
                            <strong>Imagen de su firma</strong>
                            <input type="file" name="Firma[archivo_imagen]" accept="image/png,image/jpeg" data-archivo="imagen" data-max-mb="2">
                            <img class="vista-previa-firma" alt="Vista previa de la firma" data-vista-previa>
                            <p class="nota-firma">Formatos PNG o JPG, máximo 2 MB. Se recomienda firma en fondo blanco.</p>
                        </div>
                    </div>

                    {{-- Opción 3: firmar a mano y subir después --}}
                    <div class="panel-firma" data-panel="documento" hidden>
                        <div class="subida-firma">
                            <strong>Firmar a mano</strong>
                            <p class="nota-firma">Al enviar, su encuesta se guardará y podrá descargarla en PDF. Fírmela a mano y súbala desde esta misma pantalla.</p>
                        </div>
                    </div>

                    <label class="confirmacion-firma">
                        <input type="checkbox" name="Firma[confirmacion]" value="1" required @checked(old('Firma.confirmacion'))>
                        Confirmo que la información y esta firma son correctas.
                    </label>
                    <small data-error-firma style="color:#991b1b"></small>
                </section>
                <button class="btn-enviar-encuesta" type="submit">Enviar encuesta</button>
            </div>

            <div class="navegacion-encuesta">
                <button type="button" class="btn-atras" data-atras hidden>← Atrás</button>
            </div>
        </form>
    </div>
@endif

<script>
document.querySelectorAll('[data-cerrar-encuesta]').forEach(b => b.addEventListener('click', () => b.closest('[data-modal-encuesta]').style.display = 'none'));
document.querySelectorAll('[data-abrir-encuesta]').forEach(b => b.addEventListener('click', () => document.querySelector('[data-modal-encuesta]').style.display = 'flex'));

// Encuesta por pasos: una pregunta por pantalla, avance automático y logo AICO deslizante
document.querySelectorAll('[data-modal-encuesta] form').forEach(form => {
    const pasos = [...form.querySelectorAll('[data-paso]')];
    const total = pasos.length - 1; // cantidad de preguntas (el último paso es comentario/firma)
    const barra = form.querySelector('[data-barra]');
    const etiqueta = form.querySelector('[data-etiqueta-paso]');
    const atras = form.querySelector('[data-atras]');
    let actual = 0, temporizador;

    // Mueve el logo AICO y la barra del riel hasta el valor elegido
    const marcar = (paso, valor) => {
        const marcador = paso.querySelector('[data-marcador]');
        const progreso = paso.querySelector('[data-riel-progreso]');
        if (!marcador || !progreso) return;
        marcador.style.left = ((valor - 0.5) * 20) + '%';
        marcador.style.opacity = 1;
        progreso.style.width = ((valor - 1) * 20) + '%';
    };

    const ir = i => {
        actual = Math.max(0, Math.min(i, pasos.length - 1));
        pasos.forEach((p, k) => p.hidden = k !== actual);
        barra.style.width = (actual / total * 100) + '%';
        etiqueta.textContent = actual < total ? 'Pregunta ' + (actual + 1) + ' de ' + total : 'Último paso: comentario y firma';
        atras.hidden = actual === 0;
        if (actual === total) form.querySelector('[data-firma]')?.dispatchEvent(new Event('mostrar'));
    };

    // Al tocar una calificación: el logo se desliza y luego avanza solo
    pasos.forEach((paso, k) => paso.querySelectorAll('input[type="radio"]').forEach(radio =>
        radio.addEventListener('change', () => {
            marcar(paso, Number(radio.value));
            clearTimeout(temporizador);
            temporizador = setTimeout(() => ir(k + 1), 450);
        })
    ));

    atras.addEventListener('click', () => { clearTimeout(temporizador); ir(actual - 1); });

    // Atajo de teclado: teclas 1-5 responden, Alt + ← regresa
    form.addEventListener('keydown', e => {
        if (['INPUT', 'TEXTAREA'].includes(e.target.tagName) && e.target.type !== 'radio') return;
        const radios = pasos[actual].querySelectorAll('input[type="radio"]');
        if (/^[1-5]$/.test(e.key) && radios.length) radios[Number(e.key) - 1].click();
        if (e.key === 'ArrowLeft' && e.altKey) ir(actual - 1);
    });

    // Restaurar respuestas previas (old) y abrir en la primera pregunta sin responder
    pasos.forEach(paso => { const marcada = paso.querySelector('input[type="radio"]:checked'); if (marcada) marcar(paso, Number(marcada.value)); });
    const primera = pasos.findIndex(p => p.querySelector('input[type="radio"]') && !p.querySelector('input[type="radio"]:checked'));
    ir(primera === -1 ? total : primera);
});

document.querySelectorAll('[data-firma]').forEach(seccion => {
    const form = seccion.closest('form');
    const lienzo = seccion.querySelector('[data-lienzo-firma]');
    const contexto = lienzo.getContext('2d');
    const imagen = seccion.querySelector('[data-imagen-firma]');
    const estado = seccion.querySelector('[data-estado-firma]');
    const error = seccion.querySelector('[data-error-firma]');
    const campoMetodo = seccion.querySelector('[data-metodo-firma]');
    const vistaPrevia = seccion.querySelector('[data-vista-previa]');
    let dibujando = false, tieneFirma = false;

    // El canvas necesita medirse con el panel visible
    const ajustarLienzo = () => {
        if (tieneFirma) return;
        const r = lienzo.getBoundingClientRect();
        if (!r.width) return;
        const escala = window.devicePixelRatio || 1;
        lienzo.width = r.width * escala;
        lienzo.height = r.height * escala;
        contexto.setTransform(escala, 0, 0, escala, 0, 0);
        contexto.lineWidth = 2; contexto.lineCap = 'round'; contexto.strokeStyle = '#003b80';
    };

    const seleccionar = metodo => {
        campoMetodo.value = metodo;
        error.textContent = '';
        seccion.querySelectorAll('[data-opcion]').forEach(b => b.classList.toggle('activa', b.dataset.opcion === metodo));
        seccion.querySelectorAll('[data-panel]').forEach(p => p.hidden = p.dataset.panel !== metodo);
        if (metodo === 'dibujar') ajustarLienzo();
    };
    seccion.querySelectorAll('[data-opcion]').forEach(b => b.addEventListener('click', () => seleccionar(b.dataset.opcion)));

    // El paso final empieza oculto: medir el lienzo cuando se hace visible
    seccion.addEventListener('mostrar', () => { if (campoMetodo.value === 'dibujar') ajustarLienzo(); });

    // Dibujo
    const punto = e => { const r = lienzo.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; };
    lienzo.addEventListener('pointerdown', e => { dibujando = true; lienzo.setPointerCapture(e.pointerId); const p = punto(e); contexto.beginPath(); contexto.moveTo(p.x, p.y); });
    lienzo.addEventListener('pointermove', e => { if (!dibujando) return; const p = punto(e); contexto.lineTo(p.x, p.y); contexto.stroke(); tieneFirma = true; estado.textContent = 'Firma capturada'; });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev => lienzo.addEventListener(ev, () => dibujando = false));
    seccion.querySelector('[data-limpiar-firma]').addEventListener('click', () => {
        contexto.clearRect(0, 0, lienzo.width, lienzo.height);
        imagen.value = ''; tieneFirma = false; estado.textContent = 'Firma pendiente';
    });

    // Archivos: validar tamaño y mostrar vista previa
    seccion.querySelectorAll('[data-archivo]').forEach(input => {
        input.addEventListener('change', () => {
            error.textContent = '';
            const archivo = input.files[0];
            if (!archivo) return;
            if (archivo.size > Number(input.dataset.maxMb) * 1024 * 1024) {
                error.textContent = 'El archivo supera ' + input.dataset.maxMb + ' MB.';
                input.value = ''; vistaPrevia.style.display = 'none'; return;
            }
            if (input.dataset.archivo === 'imagen') {
                vistaPrevia.src = URL.createObjectURL(archivo);
                vistaPrevia.style.display = 'block';
            }
        });
    });

    form.addEventListener('submit', e => {
        const metodo = campoMetodo.value;
        const bloquear = msg => { e.preventDefault(); error.textContent = msg; };

        if (metodo === 'dibujar') {
            if (!tieneFirma) return bloquear('La firma es obligatoria.');
            imagen.value = lienzo.toDataURL('image/png');
        } else if (metodo === 'imagen') {
            imagen.value = '';
            const input = seccion.querySelector('[data-archivo="imagen"]');
            if (!input.files.length) return bloquear('Seleccione la imagen de su firma.');
        } else {
            imagen.value = ''; // documento: no se requiere firma ahora
        }
    });

    seleccionar(campoMetodo.value || 'dibujar');
});
</script>