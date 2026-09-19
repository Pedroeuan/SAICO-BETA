document.addEventListener('DOMContentLoaded', function () {
    const config = {
        updateUrl: updateNotificationUrl, // URL para obtener nuevas notificaciones
        viewAllUrl: viewAllNotificationsUrl, // URL para ver todas las notificaciones
        updatePeriod: 60000,
        previewDuration: 5000,
        // Período de actualización en milisegundos (60 segundos) 
        //si lo agregas aqui, quitarlo del archivo adminlte, porque causa conflicto o viceversa 
        //Manejarlo aqui ya que se actualiza cuando se le da click al icono o cada 60 segundo, en el adminlte se actualiza 
        //cada 60 segundos hagas click en el icono o no, generando carga.
    };

    // Conserva la última entrada al sistema entre sesiones. Así, al iniciar
    // sesión se muestran las notificaciones nuevas desde la visita anterior,
    // mientras que el historial pendiente nunca se recorre como previews.
    const previewSinceKey = 'saico.notificationPreviewSince';
    let previewSince = sessionStorage.getItem(previewSinceKey);
    if (!previewSince) {
        previewSince = localStorage.getItem(previewSinceKey) || new Date().toISOString();
        sessionStorage.setItem(previewSinceKey, previewSince);
        localStorage.setItem(previewSinceKey, new Date().toISOString());
    }

    async function fetchNotifications() {
        try {
            const endpoint = new URL(config.updateUrl, window.location.origin);
            endpoint.searchParams.set('preview_since', previewSince);
            const response = await fetch(endpoint.toString());
            if (!response.ok) {
                throw new Error(`Error al obtener las notificaciones: ${response.statusText}`);
            }
            return await response.json();
        } catch (error) {
            console.error('Error al analizar el JSON:', error);
            showError('No se pudieron cargar las notificaciones.');
            return [];
        }
    }

    function showError(message) {
        const notificationList = document.querySelector('#my-notification .dropdown-menu');
        if (!notificationList) return;
        notificationList.replaceChildren();
        const item = document.createElement('li');
        item.classList.add('dropdown-item', 'text-danger');
        item.textContent = message;
        notificationList.appendChild(item);
    }

    class NotificationPreview {
        constructor({ duration, viewAllUrl }) {
            this.duration = duration;
            this.viewAllUrl = viewAllUrl;
            this.visible = false;
            this.timer = null;
            this.seen = new Set();
            this.installStyles();
        }

        installStyles() {
            const style = document.createElement('style');
            style.textContent = `
                .notification-preview { position: fixed; z-index: 1060; width: min(380px, calc(100vw - 32px)); padding: 0; overflow: hidden; border: 1px solid rgba(31, 78, 121, .18); border-radius: 10px; background: #fff; box-shadow: 0 14px 36px rgba(15, 23, 42, .22); opacity: 0; transform: translateY(-12px) scale(.98); transition: opacity .22s ease, transform .22s ease; }
                .notification-preview.is-visible { opacity: 1; transform: translateY(0) scale(1); }
                .notification-preview__header { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #1f4e79; color: #fff; font-weight: 600; }
                .notification-preview.is-important .notification-preview__header { background: #a16207; }
                .notification-preview__close { margin-left: auto; padding: 0 4px; border: 0; background: transparent; color: #fff; font-size: 22px; line-height: 1; }
                .notification-preview__title, .notification-preview__message, .notification-preview__link { margin-left: 16px; margin-right: 16px; }
                .notification-preview__title, .notification-preview__message { overflow: hidden; text-overflow: ellipsis; }
                .notification-preview__title { margin-top: 14px; margin-bottom: 4px; white-space: nowrap; font-weight: 700; color: #1f2937; }
                .notification-preview__message { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; color: #475569; font-size: .9rem; }
                .notification-preview__link { display: inline-block; margin-top: 12px; margin-bottom: 14px; color: #1f4e79; font-weight: 700; }
                @media (max-width: 576px) { .notification-preview { right: 16px !important; top: 62px !important; } }
            `;
            document.head.appendChild(style);
        }

        show(notification) {
            if (!notification || this.visible || this.seen.has(notification.id)) return;
            this.seen.add(notification.id);
            this.visible = true;

            const bell = document.querySelector('#my-notification');
            const rect = bell ? bell.getBoundingClientRect() : { right: window.innerWidth - 16, bottom: 56 };
            const element = document.createElement('aside');
            element.className = 'notification-preview';
            element.classList.toggle('is-important', ['alta', 'critica'].includes(notification.priority));
            element.setAttribute('role', 'status');
            element.setAttribute('aria-live', 'polite');
            element.setAttribute('aria-label', 'Nueva notificación');
            element.style.top = `${Math.max(16, rect.bottom + 8)}px`;
            element.style.right = `${Math.max(16, window.innerWidth - rect.right)}px`;

            const header = document.createElement('div');
            header.className = 'notification-preview__header';
            header.textContent = '🔔 Nueva notificación';
            const close = document.createElement('button');
            close.type = 'button'; close.className = 'notification-preview__close';
            close.setAttribute('aria-label', 'Cerrar preview'); close.textContent = '×';
            close.addEventListener('click', () => this.hide(element));
            header.appendChild(close);
            const title = document.createElement('div'); title.className = 'notification-preview__title'; title.textContent = notification.title || 'Nueva notificación';
            const message = document.createElement('div'); message.className = 'notification-preview__message'; message.textContent = notification.message || '';
            const link = document.createElement('a'); link.className = 'notification-preview__link'; link.href = this.safeUrl(notification.url); link.textContent = 'Ver →';
            link.addEventListener('click', async (event) => {
                event.preventDefault();
                const destination = link.href;
                try {
                    await fetch(`/notificaciones/marcar-leida/${encodeURIComponent(notification.id)}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                    });
                } catch (error) {
                    console.error('Error al marcar como leída desde el preview:', error);
                }
                window.location.assign(destination);
            });
            element.append(header, title, message, link);
            document.body.appendChild(element);
            requestAnimationFrame(() => element.classList.add('is-visible'));
            this.timer = window.setTimeout(() => this.hide(element), this.duration);
        }

        hide(element) {
            if (!this.visible) return;
            window.clearTimeout(this.timer);
            this.visible = false;
            element.classList.remove('is-visible');
            window.setTimeout(() => element.remove(), 250);
        }

        safeUrl(url) {
            try {
                const parsed = new URL(url, window.location.origin);
                return parsed.origin === window.location.origin ? parsed.href : this.viewAllUrl;
            } catch (_) { return this.viewAllUrl; }
        }
    }

    const notificationPreview = new NotificationPreview({
        duration: config.previewDuration,
        viewAllUrl: config.viewAllUrl,
    });

    function updateNotificationBadge(count) {
        //const notificationBadge = document.querySelector('#my-notification .badge');
        const notificationBadge = document.querySelector('#my-notification .navbar-badge');
        if (notificationBadge) {
            if (count > 0) {
                notificationBadge.textContent = count;
                notificationBadge.style.display = 'inline';
                notificationBadge.style.color = 'black';
                notificationBadge.style.position = 'relative';
                notificationBadge.style.top = '-3px';
            } else {
                notificationBadge.style.display = 'none';
            }
        }
    }
    
        function createNotificationItem(notificacion) {
            const listItem = document.createElement('li');
            listItem.classList.add('dropdown-item', 'd-flex', 'align-items-center');

            const link = document.createElement('a');
            link.href = notificacion.url; // URL asociada a la notificación
            link.classList.add('d-flex', 'align-items-center');
            link.style.textDecoration = 'none';
            link.style.color = 'inherit';

            const icon = document.createElement('i');
            icon.classList.add('mr-2');

            switch (notificacion.type) {
                case 'info':
                    icon.classList.add('fas', 'fa-info-circle', 'text-info');
                    break;
                case 'warning':
                    icon.classList.add('fas', 'fa-exclamation-triangle', 'text-warning');
                    break;
                case 'error':
                    icon.classList.add('fas', 'fa-times-circle', 'text-danger');
                    break;
                default:
                    icon.classList.add('fas', 'fa-bell', 'text-secondary');
            }

            link.appendChild(icon);

            const messageText = document.createElement('span');
            messageText.textContent = shortenText(notificacion.message, 40);
            messageText.style.whiteSpace = 'nowrap';
            messageText.style.overflow = 'hidden';
            messageText.style.textOverflow = 'ellipsis';
            messageText.style.flexGrow = '1';
            link.appendChild(messageText);

            //  NUEVO: Marcar como leída al hacer clic
            link.addEventListener('click', async (e) => {
                e.preventDefault(); // Evita que recargue antes de marcar como leída

                try {
                    const response = await fetch(`/notificaciones/marcar-leida/${notificacion.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        // Eliminar la notificación del DOM
                        listItem.remove();
                        // Actualizar contador
                        const remaining = document.querySelectorAll('#my-notification .dropdown-item').length - 1; // quitamos el "Todas las notificaciones"
                        updateNotificationBadge(remaining);
                    }
                    // Abrir la notificación después de marcarla
                    window.location.href = notificacion.url;

                } catch (error) {
                    console.error('Error al marcar como leída:', error);
                }
            });

            listItem.appendChild(link);

            return listItem;
        }

    /*function createNotificationItem(notificacion) {
        const listItem = document.createElement('li');
        listItem.classList.add('dropdown-item', 'd-flex', 'align-items-center');

        const link = document.createElement('a');
        link.href = notificacion.url; // URL asociada a la notificación
        link.classList.add('d-flex', 'align-items-center');
        link.style.textDecoration = 'none';
        link.style.color = 'inherit';

        const icon = document.createElement('i');
        icon.classList.add('mr-2');

        switch (notificacion.type) {
            case 'info':
                icon.classList.add('fas', 'fa-info-circle', 'text-info');
                break;
            case 'warning':
                icon.classList.add('fas', 'fa-exclamation-triangle', 'text-warning');
                break;
            case 'error':
                icon.classList.add('fas', 'fa-times-circle', 'text-danger');
                break;
            default:
                icon.classList.add('fas', 'fa-bell', 'text-secondary');
        }

        link.appendChild(icon);

        const messageText = document.createElement('span');
        messageText.textContent = shortenText(notificacion.message, 40);
        messageText.style.whiteSpace = 'nowrap';
        messageText.style.overflow = 'hidden';
        messageText.style.textOverflow = 'ellipsis';
        messageText.style.flexGrow = '1';
        link.appendChild(messageText);

        listItem.appendChild(link);

        return listItem;
    }*/

    function shortenText(text, maxLength) {
        return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
    }

    async function updateNotifications() {
        const response = await fetchNotifications();
        const data = Array.isArray(response) ? response : (response.notifications || []);
        //console.log('Notificaciones recibidas:', data); // <-- Aquí ves todo, incluyendo url
        updateNotificationBadge(data.length);

        const notificationList = document.querySelector('#my-notification .dropdown-menu');
        if (notificationList) {
            notificationList.innerHTML = '';

            const fragment = document.createDocumentFragment();
            data.forEach(notificacion => {
                fragment.appendChild(createNotificationItem(notificacion));
            });

            const viewAllItem = document.createElement('li');
            viewAllItem.classList.add('dropdown-item', 'text-center');
            viewAllItem.textContent = "Todas las notificaciones"; // texto visible
            viewAllItem.style.cursor = "pointer"; // indica que es clicable
            viewAllItem.onclick = function() {
                window.open(config.viewAllUrl, "_blank"); // abre la URL en nueva pestaña
            };

            fragment.appendChild(viewAllItem);


            notificationList.appendChild(fragment);
        }

        if (!Array.isArray(response)) {
            notificationPreview.show(response.preview);
        }
    }

    updateNotifications();
    setInterval(updateNotifications, config.updatePeriod);
});
