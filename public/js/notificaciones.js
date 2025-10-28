document.addEventListener('DOMContentLoaded', function () {
    const config = {
        updateUrl: updateNotificationUrl, // URL para obtener nuevas notificaciones
        viewAllUrl: viewAllNotificationsUrl, // URL para ver todas las notificaciones
        updatePeriod: 60000 
        // Período de actualización en milisegundos (60 segundos) 
        //si lo agregas aqui, quitarlo del archivo adminlte, porque causa conflicto o viceversa 
        //Manejarlo aqui ya que se actualiza cuando se le da click al icono o cada 60 segundo, en el adminlte se actualiza 
        //cada 60 segundos hagas click en el icono o no, generando carga.
    };

    async function fetchNotifications() {
        try {
            const response = await fetch(config.updateUrl);
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
        notificationList.innerHTML = `<li class="dropdown-item text-danger">${message}</li>`;
    }

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

        listItem.appendChild(link);

        return listItem;
    }

    function shortenText(text, maxLength) {
        return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
    }

    async function updateNotifications() {
        const data = await fetchNotifications();
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
    }

    updateNotifications();
    setInterval(updateNotifications, config.updatePeriod);
});
