document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('FOR-PIMP-04_02') || document.getElementById('FOR-PIMP-04_03') || document.getElementById('FOR-PIMP-03_B_01');
    if (!form) return;
    const canvas = document.createElement('canvas'); canvas.width = 1000; canvas.height = 700;
    const ctx = canvas.getContext('2d'); ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, 1000, 700);
    ctx.strokeStyle = '#111'; ctx.lineWidth = 5; ctx.strokeRect(3, 3, 994, 694);
    ctx.beginPath(); ctx.moveTo(3, 3); ctx.lineTo(997, 697); ctx.moveTo(997, 3); ctx.lineTo(3, 697); ctx.stroke();
    const imagenX = canvas.toDataURL('image/png');
    const indice = base64 => base64.name.match(/\[(\d+)\]/)?.[1] ?? [...form.querySelectorAll('input[name^="images_base64["]')].indexOf(base64);
    const iniciar = () => form.querySelectorAll('[id^="image-container-"]').forEach(card => {
        if (card.querySelector('[data-sin-fotografia]')) return;
        const base64 = card.querySelector('input[name^="images_base64["]');
        const preview = card.querySelector('.image-preview');
        if (!base64 || !preview) return;
        const label = document.createElement('label'); label.className = 'd-flex align-items-center border rounded p-2 mt-2 bg-light'; label.style.gap = '8px';
        const check = document.createElement('input'); check.type = 'checkbox'; check.dataset.sinFotografia = '1';
        const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.dataset.sinFotografiaValor = '1';
        const texto = document.createElement('span'); texto.textContent = 'Sin fotografía: mostrar recuadro con X';
        label.append(check, texto); preview.before(label, hidden);
        let anterior = null;
        const aplicar = () => {
            hidden.name = `foto_sin_imagen[${indice(base64)}]`;
            hidden.value = check.checked ? '1' : '0';
            if (check.checked) {
                if (!anterior) anterior = { valor: base64.value, nodos: [...preview.childNodes], controles: [] };
                base64.value = imagenX;
                const img = document.createElement('img'); img.src = imagenX; img.alt = 'Espacio sin fotografía, marcado con una X'; img.className = 'img-fluid img-thumbnail';
                preview.replaceChildren(img);
                card.querySelectorAll('input[type=file], .foto-texto-checkbox, .foto-disparo-checkbox').forEach(input => {
                    if (!anterior.controles.some(item => item.input === input)) anterior.controles.push({ input, disabled: input.disabled });
                    input.disabled = true;
                });
            } else if (anterior) {
                base64.value = anterior.valor; preview.replaceChildren(...anterior.nodos);
                anterior.controles.forEach(item => item.input.disabled = item.disabled); anterior = null;
            }
        };
        check.checked = card.dataset.fotoSinImagen === '1';
        check.addEventListener('change', () => {
            if (check.checked) {
                const texto = card.querySelector('.foto-texto-checkbox');
                if (texto?.checked) { texto.checked = false; texto.dispatchEvent(new Event('change', { bubbles: true })); }
                const disparo = card.querySelector('.foto-disparo-checkbox');
                if (disparo?.checked) { disparo.checked = false; disparo.dispatchEvent(new Event('change', { bubbles: true })); }
            }
            aplicar();
        });
        aplicar();
    });
    iniciar();
    new MutationObserver(iniciar).observe(form, { childList: true, subtree: true });
    form.addEventListener('submit', () => {
        form.querySelectorAll('[data-sin-fotografia]').forEach(check => {
            const card = check.closest('[id^="image-container-"]');
            const base64 = card.querySelector('input[name^="images_base64["]');
            card.querySelector('[data-sin-fotografia-valor]').name = `foto_sin_imagen[${indice(base64)}]`;
            if (check.checked) base64.value = imagenX;
        });
    }, true);
});
