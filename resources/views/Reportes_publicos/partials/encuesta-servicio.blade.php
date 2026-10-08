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

    [data-modal-encuesta] .categoria-pregunta {
        display: flex;
        align-items: center;
        gap: 8px;
        width: fit-content;
        max-width: 100%;
        box-sizing: border-box;
        margin-bottom: 10px;
        padding: 6px 12px;
        border: 1px solid #bfd2ec;
        border-radius: 20px;
        background: #edf3fb;
        color: #003b80;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
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
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 10px; 
        margin-top: 6px; 
    }
    .opcion-calificacion { 
        position: relative; 
        min-width: 0;
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
        justify-content: center;
        gap: 8px;
        min-height: 112px;
        height: 100%;
        box-sizing: border-box;
        padding: 14px 4px; 
        border: 2px solid #cbd5e1; 
        border-radius: 12px; 
        background: #fff; 
        transition: border-color .15s, background .15s, box-shadow .15s;
    }
    .opcion-calificacion .carita { 
        display: flex;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        gap: 2px;
        min-height: 32px;
        max-width: 100%;
        color: #f4c542;
        font-size: 17px;
        line-height: 1; 
    }
    .opcion-calificacion .numero { 
        color: #003b80; 
        font-size: 20px;
        font-weight: 800; 
    }
    .opcion-calificacion .texto { 
        color: #64748b; 
        font-size: 13px;
        line-height: 1.3;
        text-align: center;
        overflow-wrap: anywhere;
    }
    .opcion-calificacion:hover .tarjeta { 
        border-color: #003b80; 
        box-shadow: 0 3px 10px rgba(0, 59, 128, .12);
    }
    .opcion-calificacion input:checked + .tarjeta { 
        border-color: #003b80; 
        background: #003b80; 
        box-shadow: 0 3px 10px rgba(0, 59, 128, .22);
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
        .opcion-calificacion .tarjeta { min-height: 104px; padding: 12px 3px; gap: 6px; }
        .opcion-calificacion .texto { font-size: 11px; }
        .opcion-calificacion .carita { font-size: 11px; min-height: 28px; }
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
    .encuesta-recordatorio[hidden] { display: none; }
    .encuesta-recordatorio {
        position: fixed;
        inset: 0;
        z-index: 1001;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0,35,75,.72);
    }
    .encuesta-recordatorio > div {
        width: min(420px, 100%);
        padding: 24px;
        border-radius: 12px;
        background: #fff;
    }
    .firma-encuesta input.firma-nombre {
        font-family: 'Segoe Script', 'Bradley Hand', 'Brush Script MT', cursive;
        font-size: 26px;
    }
    .lienzo-firma.firma-nombre { cursor: default; touch-action: auto; }
    /* Presentacion del portal SAICO: azul institucional y rojo para la accion final. */
    [data-modal-encuesta] .encuesta-panel {
        padding: 30px;
        border-top: 5px solid #003b80;
        border-radius: 14px;
        color: #26323f;
        font-family: Arial, sans-serif;
    }
    [data-modal-encuesta] .encuesta-panel h2 { padding-right: 32px; font-size: 24px; }
    [data-modal-encuesta] .encuesta-contexto {
        padding: 12px 14px;
        margin: 14px 0 20px;
        border-left: 3px solid #003b80;
        border-radius: 0 8px 8px 0;
        background: #f1f5fa;
        font-size: 13px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }
    [data-modal-encuesta] .etiqueta-progreso { margin-top: 8px; font-size: 12px; }
    [data-modal-encuesta] .bloque-comentario,
    [data-modal-encuesta] .firma-encuesta {
        padding: 20px;
        border: 1px solid #dce3ec;
        border-radius: 10px;
        background: #fff;
    }
    [data-modal-encuesta] .firma-encuesta { 
        margin-top: 16px; 
    }
    [data-modal-encuesta] .titulo-seccion-encuesta {
        margin: 0 0 8px;
        color: #003b80;
        font-size: 16px;
    }
    [data-modal-encuesta] .ayuda-encuesta { 
        margin: 0 0 14px; 
        color: #64748b; 
        font-size: 13px; 
        line-height: 1.5; 
    }
    [data-modal-encuesta] .comentario-encuesta { 
        margin-top: 10px; 
        min-height: 90px; 
        resize: vertical; 
    }
    [data-modal-encuesta] .comentario-encuesta:focus,
    [data-modal-encuesta] #nombre-firma:focus { 
        outline: 2px solid #003b80; 
        outline-offset: 2px; 
    }
    [data-modal-encuesta] .opciones-firma { 
        display: grid; 
        grid-template-columns: repeat(3, minmax(0, 1fr)); 
        gap: 8px; 
        margin: 12px 0 18px; 
    }
    [data-modal-encuesta] .opcion-firma { 
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
        align-items: center; 
        gap: 8px; 
        min-height: 76px; 
        padding: 12px 8px; 
        line-height: 1.35; 
    }
    [data-modal-encuesta] .opcion-firma i { 
        font-size: 18px; 
    }
    [data-modal-encuesta] .opcion-firma:hover { 
        border-color: #003b80; 
    }
    [data-modal-encuesta] button:focus-visible { 
        outline: 2px solid #003b80; 
        outline-offset: 3px; 
    }
    [data-modal-encuesta] [data-instruccion-firma] { 
        display: block; 
        margin-bottom: 10px; 
        color: #64748b; 
        line-height: 1.5; 
    }
    [data-modal-encuesta] .lienzo-firma { 
        height: 150px; 
        border: 1px dashed #8ea4bf; 
        background: #f5f8fc; 
    }
    [data-modal-encuesta] [data-estado-firma] { 
        color: #003b80; 
        font-size: 12px; 
    }
    [data-modal-encuesta] .confirmacion-firma { 
        display: flex; 
        align-items: flex-start; 
        gap: 10px; 
        padding: 14px; 
        border-radius: 8px; 
        background: #f1f5fa; 
        line-height: 1.5; 
    }
    [data-modal-encuesta] .confirmacion-firma input { 
        margin: 3px 0 0; 
        flex-shrink: 0; 
        accent-color: #003b80; 
    }
    [data-modal-encuesta] [data-error-firma]:not(:empty) { 
        display: block; 
        margin-top: 10px; 
    }
    [data-modal-encuesta] .acciones-envio-encuesta { 
        display: flex; 
        justify-content: flex-end; 
    }
    [data-modal-encuesta] .btn-enviar-encuesta { 
        min-height: 44px; 
        box-shadow: 0 3px 8px rgba(224,26,34,.15); 
    }
    [data-modal-encuesta] .btn-enviar-encuesta:hover { 
        background: #bd141c; 
    }
    [data-modal-encuesta] .navegacion-encuesta { 
        display: flex; 
        justify-content: space-between; 
        gap: 12px; 
        padding-top: 16px; 
        border-top: 1px solid #dce3ec; 
    }
    [data-modal-encuesta] .navegacion-encuesta button { 
        min-height: 40px; 
    }
    [data-modal-encuesta] .navegacion-encuesta [data-cerrar-encuesta] { 
        margin-left: auto; 
        color: #64748b; 
        font-weight: 400; 
    }
    @media (max-width: 540px) {
        [data-modal-encuesta] { 
            padding: 10px; 
        }
        [data-modal-encuesta] .encuesta-panel { 
            padding: 20px 14px; 
            max-height: calc(100dvh - 20px); }
        [data-modal-encuesta] .encuesta-panel h2 { 
            font-size: 20px; 
        }
        [data-modal-encuesta] .bloque-comentario, [data-modal-encuesta] .firma-encuesta { 
            padding: 15px; 
        }
        [data-modal-encuesta] .opciones-firma { 
            grid-template-columns: repeat(2, minmax(0, 1fr)); 
        }
        [data-modal-encuesta] .btn-enviar-encuesta { 
            width: 100%; 
        }
    }
</style>

<div class="encuesta-servicio {{ $encuesta ? 'realizada' : '' }}"
    data-tarjeta-encuesta data-encuesta-completada="{{ $encuesta ? '1' : '0' }}"
    data-borrador-clave="saico:encuesta:v1:{{ hash('sha256', (string) request()->route('token')) }}:{{ $orden->idOrden_Servicio }}">
    <h3>Encuesta de satisfacción</h3>
    @if($encuesta)
        <p>Encuesta contestada.<br>Promedio: <strong>{{ number_format($encuesta->Promedio, 2) }} / 5</strong></p>
        <a class="btn-encuesta realizada" href="{{ route('portal.encuesta.pdf', ['token' => request()->route('token'), 'idEncuesta' => $encuesta->idEncuesta]) }}">Descargar PDF firmado</a>
    @elseif($encuestaPendiente)
        <p>Todos los reportes del servicio han sido verificados y liberados. Tu opinión nos ayuda a mejorar</p>
        <button class="btn-encuesta" type="button" data-abrir-encuesta>Contestar encuesta</button>
        <p class="nota-firma" data-avance-encuesta hidden aria-live="polite"></p>
    @else
        <p>La encuesta se habilitará cuando se entreguen y firmen todos los reportes de este servicio.</p>
    @endif
</div>

@if($encuestaPendiente)
    @php
        $preguntasEncuesta = [
            '¿Cómo califica el servicio recibido?',
            '¿Cómo califica la atención del personal de ventas?',
            '¿Cómo califica la amabilidad del personal en sitio?',
            '¿El personal demostró estar capacitado para el trabajo?',
            '¿Cómo califica la calidad de los equipos utilizados?',
            '¿El personal usó correctamente su equipo de protección?',
            '¿Los reportes se entregaron a tiempo?',
            '¿Cómo califica el profesionalismo del equipo?',
            '¿Recomendaría nuestros servicios?',
            '¿El servicio cumplió con sus expectativas?',
        ];
        $categoriasEncuesta = [
            'Servicio general', 'Ventas', 'Amabilidad', 'Capacitación', 'Equipos',
            'Seguridad', 'Tiempos', 'Profesionalismo', 'Recomendación', 'Expectativas',
        ];
        $iconosCategoriasEncuesta = [
            'fa-star', 'fa-handshake', 'fa-face-smile', 'fa-graduation-cap', 'fa-screwdriver-wrench',
            'fa-shield-halved', 'fa-clock', 'fa-user-tie', 'fa-thumbs-up', 'fa-bullseye',
        ];
        $nivelesSatisfaccion = [1 => 'Malo', 2 => 'Regular', 3 => 'Aceptable', 4 => 'Bueno', 5 => 'Excelente'];
    @endphp
    <div class="encuesta-modal" data-modal-encuesta role="dialog" aria-modal="true" aria-labelledby="titulo-encuesta">
        <form class="encuesta-panel" method="POST" enctype="multipart/form-data" data-respuestas-servidor="{{ $errors->any() ? '1' : '0' }}" action="{{ route('portal.encuesta.store', ['token' => request()->route('token')]) }}">
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
                    <legend>
                        <span class="categoria-pregunta">
                            <i class="fa-solid {{ $iconosCategoriasEncuesta[$indice] }}" aria-hidden="true"></i>
                            {{ $categoriasEncuesta[$indice] }}
                        </span>
                        {{ $indice + 1 }}. {{ $pregunta }}
                    </legend>
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
                                        <span class="carita" aria-hidden="true">
                                            @for($estrella = 0; $estrella < $valor; $estrella++)
                                                <i class="fa-solid fa-star"></i>
                                            @endfor
                                        </span>
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
                <section class="bloque-comentario" aria-labelledby="titulo-comentario-encuesta">
                <h3 class="titulo-seccion-encuesta" id="titulo-comentario-encuesta">Tu opinión sobre el servicio</h3>
                <label for="Comentario"><strong>¿Desea realizar algún comentario adicional?</strong></label>
                <textarea class="comentario-encuesta" id="Comentario" name="Comentario" maxlength="2000" placeholder="Escribe tu comentario (opcional)">{{ old('Comentario') }}</textarea>
                </section>
                <section class="firma-encuesta" data-firma>
                    <h3 class="titulo-seccion-encuesta">Firma de conformidad</h3>
                    <p class="nota-firma" data-nota-borrador hidden></p>
                    <label for="nombre-firma"><strong>Nombre de quien firma</strong></label>
                    <input id="nombre-firma" type="text" name="Firma[nombre]" maxlength="200" value="{{ old('Firma.nombre') }}" required>

                    <input type="hidden" name="Firma[metodo]" value="{{ old('Firma.metodo', 'dibujar') }}" data-metodo-firma>

                    <p><strong>Elija cómo desea firmar</strong></p>
                    <div class="opciones-firma">
                        <button type="button" class="opcion-firma" data-opcion="nombre"><i class="fas fa-signature" aria-hidden="true"></i>Usar mi nombre</button>
                        <button type="button" class="opcion-firma" data-opcion="dibujar">
                            <i class="fas fa-pen"></i>
                            Firmar aquí
                        </button>
                        <button type="button" class="opcion-firma" data-opcion="imagen">
                            <i class="fas fa-image"></i>
                            Subir imagen de mi firma
                        </button>
                    </div>

                    {{-- Opción 1: dibujar --}}
                    <div class="panel-firma" data-panel="dibujar">
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

                    <label class="confirmacion-firma">
                        <input type="checkbox" name="Firma[confirmacion]" value="1" required @checked(old('Firma.confirmacion'))>
                        Confirmo que la información y esta firma son correctas.
                    </label>
                    <small data-error-firma style="color:#991b1b"></small>
                </section>
                <div class="acciones-envio-encuesta">
                    <button class="btn-enviar-encuesta" type="submit">Enviar encuesta <i class="fas fa-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="navegacion-encuesta">
                <button type="button" class="btn-atras" data-atras hidden>← Atrás</button>
                <button type="button" class="btn-atras" data-cerrar-encuesta>En otro momento</button>
            </div>
            <div class="encuesta-recordatorio" data-recordatorio-encuesta hidden role="alertdialog" aria-modal="true" aria-labelledby="titulo-recordatorio-encuesta" aria-describedby="texto-recordatorio-encuesta">
                <div>
                    <h3 id="titulo-recordatorio-encuesta">Nos gustaría conocer tu opinión</h3>
                    <p id="texto-recordatorio-encuesta">Tu respuesta sobre el servicio nos ayuda a mejorar. ¿Deseas continuar con la encuesta?</p>
                    <button class="btn-encuesta" type="button" data-continuar-encuesta>Continuar encuesta</button>
                    <button class="btn-atras" type="button" data-posponer-encuesta>En otro momento</button>
                </div>
            </div>
        </form>
    </div>
@endif

<script>
// Borrador local por cliente y servicio: no realiza solicitudes ni modifica la BD.
(() => {
    const tarjeta = document.querySelector('[data-tarjeta-encuesta]');
    if (!tarjeta) return;
    const clave = tarjeta.dataset.borradorClave;
    if (tarjeta.dataset.encuestaCompletada === '1') {
        try { localStorage.removeItem(clave); } catch (_) {}
        return;
    }
    const modal = document.querySelector('[data-modal-encuesta]');
    const form = modal?.querySelector('form');
    if (!form) return;
    const radios = [...form.querySelectorAll('input[type="radio"]')];
    const comentario = form.querySelector('#Comentario');
    const nombre = form.querySelector('#nombre-firma');
    const aviso = tarjeta.querySelector('[data-avance-encuesta]');
    const boton = tarjeta.querySelector('[data-abrir-encuesta]');
    let borrador;
    try { borrador = JSON.parse(localStorage.getItem(clave)); } catch (_) {}
    if (borrador?.version === 1 && form.dataset.respuestasServidor !== '1') {
        radios.forEach(radio => {
            const valor = borrador.respuestas?.[radio.name];
            if (['1', '2', '3', '4', '5'].includes(String(valor))) radio.checked = radio.value === String(valor);
        });
        if (typeof borrador.comentario === 'string') comentario.value = borrador.comentario.slice(0, 2000);
        if (typeof borrador.nombre === 'string') nombre.value = borrador.nombre.slice(0, 200);
        form.querySelector('[data-nota-borrador]').hidden = false;
        modal.style.display = 'none';
    }
    const guardar = () => {
        const respuestas = {};
        radios.filter(radio => radio.checked).forEach(radio => respuestas[radio.name] = radio.value);
        const cantidad = Object.keys(respuestas).length;
        const hayAvance = cantidad > 0 || comentario.value.trim() !== '' || nombre.value.trim() !== '';
        try {
            if (hayAvance) localStorage.setItem(clave, JSON.stringify({
                version: 1, respuestas, comentario: comentario.value, nombre: nombre.value,
            }));
            else localStorage.removeItem(clave);
            aviso.hidden = !hayAvance;
            aviso.textContent = cantidad === 10
                ? ''
                : 'Continuar: ' + cantidad + ' de 10 preguntas contestadas.';
            boton.textContent = hayAvance ? (cantidad === 10 ? 'Terminar encuesta' : 'Continuar encuesta') : 'Contestar encuesta';
        } catch (_) {
            aviso.hidden = false;
            aviso.textContent = 'Este navegador no permite guardar el avance. Puedes continuar mientras mantengas esta página abierta.';
        }
    };
    form.addEventListener('input', guardar);
    form.addEventListener('change', guardar);
    form.addEventListener('encuesta:pausar', guardar);
    window.addEventListener('pagehide', guardar);
    guardar();
})();
document.querySelectorAll('[data-modal-encuesta]').forEach(modal => {
    const recordatorio = modal.querySelector('[data-recordatorio-encuesta]');
    let origen;
    const continuar = () => {
        recordatorio.hidden = true;
        origen?.focus();
        modal.querySelector('form').dispatchEvent(new Event('encuesta:reanudar'));
    };
    const preguntar = boton => {
        origen = boton;
        modal.querySelector('form').dispatchEvent(new Event('encuesta:pausar'));
        recordatorio.hidden = false;
        recordatorio.querySelector('[data-continuar-encuesta]').focus();
    };
    modal.querySelectorAll('[data-cerrar-encuesta]').forEach(b => b.addEventListener('click', () => preguntar(b)));
    recordatorio.querySelector('[data-continuar-encuesta]').addEventListener('click', continuar);
    recordatorio.querySelector('[data-posponer-encuesta]').addEventListener('click', () => {
        recordatorio.hidden = true;
        modal.style.display = 'none';
        document.querySelector('[data-abrir-encuesta]')?.focus();
    });
    modal.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            e.preventDefault();
            if (!recordatorio.hidden) continuar();
            else preguntar(modal.querySelector('[data-cerrar-encuesta]'));
        }
        if (!recordatorio.hidden && e.key === 'Tab') {
            const botones = [...recordatorio.querySelectorAll('button')];
            const destino = e.shiftKey ? botones[botones.length - 1] : botones[0];
            const extremo = e.shiftKey ? botones[0] : botones[botones.length - 1];
            if (document.activeElement === extremo) { e.preventDefault(); destino.focus(); }
        }
    });
});
document.querySelectorAll('[data-abrir-encuesta]').forEach(b => b.addEventListener('click', () => {
    const modal = document.querySelector('[data-modal-encuesta]');
    if (!modal) return;
    modal.style.display = 'flex';
    modal.querySelector('[data-paso]:not([hidden]) [data-firma]')?.dispatchEvent(new Event('mostrar'));
    modal.querySelector('[data-paso]:not([hidden]) input, [data-paso]:not([hidden]) textarea')?.focus();
}));

// Encuesta por pasos: una pregunta por pantalla, avance automático y logo AICO deslizante
document.querySelectorAll('[data-modal-encuesta] form').forEach(form => {
    const pasos = [...form.querySelectorAll('[data-paso]')];
    const total = pasos.length - 1; // cantidad de preguntas (el último paso es comentario/firma)
    const barra = form.querySelector('[data-barra]');
    const etiqueta = form.querySelector('[data-etiqueta-paso]');
    const atras = form.querySelector('[data-atras]');
    let actual = 0, temporizador;
    form.addEventListener('encuesta:pausar', () => clearTimeout(temporizador));

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
    form.addEventListener('encuesta:reanudar', () => {
        if (actual < total && pasos[actual].querySelector('input[type="radio"]:checked')) ir(actual + 1);
    });

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
    const nombre = seccion.querySelector('#nombre-firma');
    const instruccion = seccion.querySelector('[data-instruccion-firma]');
    let usarNombre = false;
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

    const generarFirmaNombre = () => {
        if (!usarNombre) return;
        tieneFirma = false;
        ajustarLienzo();
        const r = lienzo.getBoundingClientRect();
        if (!r.width) return;
        contexto.clearRect(0, 0, lienzo.width, lienzo.height);
        const texto = nombre.value.trim();
        if (texto) {
            contexto.font = '44px "Segoe Script", "Bradley Hand", "Brush Script MT", cursive';
            contexto.fillStyle = '#003b80';
            contexto.textAlign = 'center';
            contexto.textBaseline = 'middle';
            contexto.fillText(texto, r.width / 2, r.height / 2, Math.max(1, r.width - 32));
            tieneFirma = true;
        }
        imagen.value = tieneFirma ? lienzo.toDataURL('image/png') : '';
        estado.textContent = tieneFirma ? 'Firma generada con su nombre' : 'Escriba su nombre para generar la firma';
    };
    const seleccionar = metodo => {
        usarNombre = metodo === 'nombre';
        campoMetodo.value = usarNombre ? 'dibujar' : metodo;
        error.textContent = '';
        seccion.querySelectorAll('[data-opcion]').forEach(b => b.classList.toggle('activa', b.dataset.opcion === metodo));
        seccion.querySelectorAll('[data-panel]').forEach(p => p.hidden = p.dataset.panel !== campoMetodo.value);
        nombre.classList.toggle('firma-nombre', usarNombre);
        lienzo.classList.toggle('firma-nombre', usarNombre);
        if (instruccion) instruccion.textContent = usarNombre
            ? 'Su nombre se convierte en una firma con estilo manuscrito. Revísela antes de confirmar.'
            : 'Firme dentro del recuadro con el mouse o con el dedo.';
        if (campoMetodo.value === 'dibujar') {
            contexto.clearRect(0, 0, lienzo.width, lienzo.height);
            tieneFirma = false;
            imagen.value = '';
            estado.textContent = 'Firma pendiente';
            ajustarLienzo();
            generarFirmaNombre();
        }
    };
    seccion.querySelectorAll('[data-opcion]').forEach(b => b.addEventListener('click', () => seleccionar(b.dataset.opcion)));

    // El paso final empieza oculto: medir el lienzo cuando se hace visible
    seccion.addEventListener('mostrar', () => {
        if (campoMetodo.value === 'dibujar') { ajustarLienzo(); generarFirmaNombre(); }
    });
    nombre.addEventListener('input', generarFirmaNombre);
    window.addEventListener('resize', () => { if (usarNombre) generarFirmaNombre(); });

    // Dibujo
    const punto = e => { const r = lienzo.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; };
    lienzo.addEventListener('pointerdown', e => { if (usarNombre) return; dibujando = true; lienzo.setPointerCapture(e.pointerId); const p = punto(e); contexto.beginPath(); contexto.moveTo(p.x, p.y); });
    lienzo.addEventListener('pointermove', e => { if (!dibujando) return; const p = punto(e); contexto.lineTo(p.x, p.y); contexto.stroke(); tieneFirma = true; estado.textContent = 'Firma capturada'; });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(ev => lienzo.addEventListener(ev, () => dibujando = false));
    seccion.querySelector('[data-limpiar-firma]').addEventListener('click', () => {
        contexto.clearRect(0, 0, lienzo.width, lienzo.height);
        imagen.value = ''; tieneFirma = false; estado.textContent = 'Firma pendiente';
        if (usarNombre) { nombre.value = ''; nombre.focus(); }
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
            generarFirmaNombre();
            if (!tieneFirma) return bloquear('La firma es obligatoria.');
            imagen.value = lienzo.toDataURL('image/png');
        } else if (metodo === 'imagen') {
            imagen.value = '';
            const input = seccion.querySelector('[data-archivo="imagen"]');
            if (!input.files.length) return bloquear('Seleccione la imagen de su firma.');
        } else {
            return bloquear('Seleccione una opción de firma válida.');
        }
    });

    seleccionar(campoMetodo.value === 'imagen' ? 'imagen' : 'nombre');
});
</script>
