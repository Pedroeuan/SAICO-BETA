document.addEventListener('DOMContentLoaded', function () {
    // El script solo se inicializa cuando la página contiene el editor de perfil.
    const form = document.getElementById('profile-photo-form');
    if (!form) return;
    const input = document.getElementById('photo');
    const preview = document.getElementById('profile-preview');
    const caption = document.getElementById('profile-preview-caption');
    const panel = document.getElementById('profile-crop');
    const canvas = document.getElementById('profile-crop-canvas');
    const context = canvas.getContext('2d');
    const zoom = document.getElementById('profile-zoom');
    const horizontal = document.getElementById('profile-x');
    const vertical = document.getElementById('profile-y');
    const save = document.getElementById('profile-save');
    const cancel = document.getElementById('profile-cancel');
    const message = document.getElementById('profile-photo-message');
    const removeForm = document.getElementById('profile-remove-form');
    const original = preview.src;
    const saveLabel = save.innerHTML;
    let image = null;
    let revision = 0;
    let saving = false;
    let drag = null;

    // Herramientas de acercamiento y restablecimiento del encuadre.
    const clamp = (value, min, max) => Math.min(max, Math.max(min, value));
    function changeZoom(delta) {
        if (saving || !image) return;
        zoom.value = clamp(Number(zoom.value) + delta, 1, 3);
        draw();
    }
    document.getElementById('profile-zoom-out').addEventListener('click', () => changeZoom(-0.15));
    document.getElementById('profile-zoom-in').addEventListener('click', () => changeZoom(0.15));
    document.getElementById('profile-recenter').addEventListener('click', function () {
        if (saving || !image) return;
        zoom.value = '1';
        horizontal.value = vertical.value = '50';
        draw();
    });
    // Pointer Events permite arrastrar la foto tanto con mouse como con pantalla táctil.
    canvas.addEventListener('pointerdown', function (event) {
        if (saving || !image || !event.isPrimary || event.button !== 0) return;
        drag = { id: event.pointerId, x: event.clientX, y: event.clientY };
        canvas.setPointerCapture(event.pointerId);
    });
    canvas.addEventListener('pointermove', function (event) {
        if (!drag || drag.id !== event.pointerId || saving || !image) return;
        const bounds = canvas.getBoundingClientRect();
        const baseScale = Math.min(canvas.width / image.naturalWidth, canvas.height / image.naturalHeight);
        const drawnWidth = image.naturalWidth * baseScale * Number(zoom.value);
        const drawnHeight = image.naturalHeight * baseScale * Number(zoom.value);
        const overflowX = Math.max(0, drawnWidth - canvas.width);
        const overflowY = Math.max(0, drawnHeight - canvas.height);
        const moveX = (event.clientX - drag.x) * canvas.width / bounds.width;
        const moveY = (event.clientY - drag.y) * canvas.height / bounds.height;
        if (overflowX > 0) {
            horizontal.value = clamp(Number(horizontal.value) - moveX / overflowX * 100, 0, 100);
        }
        if (overflowY > 0) {
            vertical.value = clamp(Number(vertical.value) - moveY / overflowY * 100, 0, 100);
        }
        drag.x = event.clientX;
        drag.y = event.clientY;
        draw();
    });
    ['pointerup', 'pointercancel', 'lostpointercapture'].forEach(name => {
        canvas.addEventListener(name, () => { drag = null; });
    });
    // Las flechas ofrecen una alternativa accesible para ajustar la posición.
    canvas.addEventListener('keydown', function (event) {
        if (saving || !image) return;
        const offsets = { ArrowLeft: [-3, 0], ArrowRight: [3, 0], ArrowUp: [0, -3], ArrowDown: [0, 3] };
        const offset = offsets[event.key];
        if (!offset) return;
        event.preventDefault();
        horizontal.value = clamp(Number(horizontal.value) - offset[0], 0, 100);
        vertical.value = clamp(Number(vertical.value) - offset[1], 0, 100);
        draw();
    });

    function reset() {
        revision++;
        image = null;
        input.value = '';
        input.setCustomValidity('');
        panel.hidden = true;
        cancel.hidden = true;
        save.disabled = true;
        message.textContent = '';
        preview.src = original;
        caption.textContent = 'Foto actual de tu cuenta';
    }

    function draw() {
        if (!image || !context) return;
        // El nivel 1 muestra la fotografía completa y conserva fondo blanco
        // cuando su proporción no es cuadrada.
        const baseScale = Math.min(canvas.width / image.naturalWidth, canvas.height / image.naturalHeight);
        const scale = baseScale * Number(zoom.value);
        const width = image.naturalWidth * scale;
        const height = image.naturalHeight * scale;
        const overflowX = Math.max(0, width - canvas.width);
        const overflowY = Math.max(0, height - canvas.height);
        const x = overflowX > 0
            ? -overflowX * Number(horizontal.value) / 100
            : (canvas.width - width) / 2;
        const y = overflowY > 0
            ? -overflowY * Number(vertical.value) / 100
            : (canvas.height - height) / 2;
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, canvas.width, canvas.height);
        context.drawImage(image, x, y, width, height);
        preview.src = canvas.toDataURL('image/jpeg', 0.9);
    }

    // Valida el archivo y prepara la vista previa sin enviarlo todavía al servidor.
    input.addEventListener('change', async function () {
        const file = input.files[0];
        const currentRevision = ++revision;
        image = null;
        save.disabled = true;
        panel.hidden = true;
        cancel.hidden = !file;
        message.textContent = '';
        preview.src = original;
        input.setCustomValidity('');
        if (!file) return;
        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
            message.textContent = 'Selecciona una imagen JPG, PNG o WebP de hasta 2 MB.';
            input.setCustomValidity(message.textContent);
            return;
        }
        const url = URL.createObjectURL(file);
        const candidate = new Image();
        candidate.src = url;
        try {
            await candidate.decode();
            if (currentRevision !== revision) return;
            if (candidate.naturalWidth > 4096 || candidate.naturalHeight > 4096) {
                throw new Error('La imagen debe medir como máximo 4096 × 4096 píxeles.');
            }
            if (!context) throw new Error('Tu navegador no permite ajustar esta imagen.');
            image = candidate;
            zoom.value = '1';
            horizontal.value = vertical.value = '50';
            panel.hidden = false;
            draw();
            save.disabled = false;
            caption.textContent = 'Vista previa del recorte, pendiente de guardar';
        } catch (error) {
            if (currentRevision !== revision) return;
            image = null;
            message.textContent = error instanceof DOMException ? 'No se pudo abrir la imagen. Selecciona otra.' : error.message;
        } finally {
            URL.revokeObjectURL(url);
        }
    });

    [zoom, horizontal, vertical].forEach(control => control.addEventListener('input', draw));
    cancel.addEventListener('click', reset);

    // Convierte el lienzo a JPEG y guarda exactamente la composición mostrada.
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        if (saving || !image || !form.reportValidity()) return;
        saving = true;
        message.textContent = '';
        const data = new FormData(form);
        const controls = [input, zoom, horizontal, vertical, cancel, save, ...panel.querySelectorAll('button')];
        if (removeForm) controls.push(removeForm.querySelector('button'));
        controls.forEach(control => { control.disabled = true; });
        save.textContent = 'Guardando…';
        form.setAttribute('aria-busy', 'true');
        try {
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.9));
            if (!blob) throw new Error('No se pudo preparar la foto. Intenta de nuevo.');
            data.set('photo', blob, 'profile.jpg');
            const response = await fetch(form.action, {
                method: 'POST', body: data, headers: { Accept: 'application/json' },
            });
            const result = await response.json().catch(() => null);
            if (!response.ok || !result || !result.saved) {
                throw new Error(result?.errors?.photo?.[0] || (response.status === 419
                    ? 'Tu sesión venció. Recarga la página e intenta de nuevo.'
                    : 'No se pudo guardar la foto. Intenta de nuevo.'));
            }
            window.location.reload();
        } catch (error) {
            message.textContent = error.message || 'No se pudo conectar con el servidor.';
            saving = false;
            controls.forEach(control => { control.disabled = false; });
            save.innerHTML = saveLabel;
            form.removeAttribute('aria-busy');
        }
    });

    // La confirmación reduce eliminaciones accidentales de una foto ya guardada.
    if (removeForm) {
        removeForm.addEventListener('submit', function (event) {
            if (saving || !window.confirm('¿Quitar tu foto y usar el avatar predeterminado?')) {
                event.preventDefault();
                return;
            }
            saving = true;
            const button = removeForm.querySelector('button');
            button.disabled = true;
            button.textContent = 'Quitando…';
            save.disabled = true;
        });
    }
});
