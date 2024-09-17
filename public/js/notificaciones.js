document.addEventListener('DOMContentLoaded', function () {
    function updateNotifications() {
        fetch(updateNotificationUrl)  // Usa la URL correcta aquí
            .then(response => {
                //console.log('Respuesta completa:', response);
                return response.json();  // Intenta convertir a JSON aquí
            })
            .then(data => {
                //console.log('Datos recibidos:', data);
                const notificationBadge = document.querySelector('#my-notification .badge');
                const notificationList = document.querySelector('#my-notification .dropdown-menu');

                if (notificationBadge && notificationList) {
                    if (data.length > 0) {
                        notificationBadge.textContent = data.length;
                        notificationBadge.style.display = 'inline';
                        notificationBadge.style.color = 'black';
                        notificationList.innerHTML = '';

                        data.forEach(notificacion => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('dropdown-item', 'd-flex', 'align-items-center');

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

                            listItem.appendChild(icon);

                            const messageText = document.createElement('span');
                            messageText.textContent = shortenText(notificacion.message, 40);
                            messageText.style.whiteSpace = 'nowrap';
                            messageText.style.overflow = 'hidden';
                            messageText.style.textOverflow = 'ellipsis';
                            messageText.style.flexGrow = '1';
                            listItem.appendChild(messageText);

                            notificationList.appendChild(listItem);
                        });

                        const viewAllItem = document.createElement('li');
                        viewAllItem.classList.add('dropdown-item', 'text-center');
                        viewAllItem.innerHTML = `<a href="${viewAllNotificationsUrl}">Todas las notificaciones</a>`;
                        notificationList.appendChild(viewAllItem);
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error al analizar el JSON:', error));
    }

    function shortenText(text, maxLength) {
        return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
    }

    updateNotifications();
    setInterval(updateNotifications, 10000); // 10 segundos
});

/*Notificaciones*/
/*document.addEventListener('DOMContentLoaded', function () {
    function updateNotifications() {
        fetch('{{ url("notificaciones/update") }}')
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data); // Verifica los datos recibidos en la consola
                const notificationBadge = document.querySelector('#my-notification .badge'); 
                const notificationList = document.querySelector('#my-notification .dropdown-menu'); 

                if (notificationBadge && notificationList) { // Verifica si los elementos existen
                    if (data.length > 0) {
                        notificationBadge.textContent = data.length;
                        notificationBadge.style.display = 'inline';
                        notificationList.innerHTML = '';

                        // Agregar notificaciones a la lista
                        data.forEach(notificacion => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('dropdown-item', 'd-flex', 'align-items-center'); // Flexbox para alineación

                            // Crear el ícono
                            const icon = document.createElement('i');
                            icon.classList.add('mr-2'); // Margen derecho para separación del texto
                            
                            // Asignar el ícono según el tipo de notificación
                            switch (notificacion.type) {  // Asegúrate de que el objeto 'notificacion' tenga una propiedad 'type'
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

                            // Agregar el ícono al elemento de lista
                            listItem.appendChild(icon);

                            // Crear el contenedor del mensaje de notificación con estilo para limitar texto
                            const messageText = document.createElement('span');
                            messageText.textContent = shortenText(notificacion.message, 40); // Ajusta la longitud del texto
                            messageText.style.whiteSpace = 'nowrap';
                            messageText.style.overflow = 'hidden';
                            messageText.style.textOverflow = 'ellipsis';
                            messageText.style.flexGrow = '1'; // Para usar el espacio restante
                            listItem.appendChild(messageText);

                            // Agregar el elemento de lista completo a la lista de notificaciones
                            notificationList.appendChild(listItem);
                        });

                        // Agregar un elemento para ver todas las notificaciones
                        const viewAllItem = document.createElement('li');
                        viewAllItem.classList.add('dropdown-item', 'text-center'); // Centrado
                        viewAllItem.innerHTML = `<a href="{{ url('notificacion/index') }}">Ver todas las notificaciones</a>`;
                        notificationList.appendChild(viewAllItem);

                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para acortar texto si es muy largo
    function shortenText(text, maxLength) {
        return text.length > maxLength ? text.slice(0, maxLength) + '...' : text;
    }

    // Llamada inicial y periódica
    updateNotifications();
    setInterval(updateNotifications, 10000); // 10 segundos
});*/
/*Notificaciones-Fin*/
