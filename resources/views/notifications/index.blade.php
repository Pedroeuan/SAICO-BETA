@extends('adminlte::page')

@section('title', 'Notificaciones')
<br>
<br>
<br>

@section('content_header')
    <h1>Notificaciones</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Notificaciones
        </div>
        <div class="card-body">
            @forelse ($notifications as $notification)
                <div class="alert alert-info">
                    {{ $notification->data['mensaje'] }} - <small>{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p>No hay notificaciones</p>
            @endforelse

            {{ $notifications->links() }} <!-- Paginación -->
        </div>
    </div>
@stop

<script>
/*
document.addEventListener('DOMContentLoaded', function () {
    function fetchNotifications() {
        fetch('/notifications/fetch')
            .then(response => response.json())
            .then(data => {
                let notificationIcon = document.getElementById('my-notification');
                let notificationBadge = notificationIcon.querySelector('.badge');
                let dropdownMenu = notificationIcon.querySelector('.dropdown-menu');

                // Guardar el botón de "Todas las notificaciones"
                let allNotificationsButton = dropdownMenu.querySelector('.dropdown-item-all');

                // Limpiar el menú, excepto el botón de "Todas las notificaciones"
                dropdownMenu.innerHTML = '';
                if (allNotificationsButton) {
                    dropdownMenu.appendChild(allNotificationsButton);
                }

                if (data.length > 0) {
                    notificationBadge.textContent = data.length;

                    data.forEach(notification => {
                        let item = document.createElement('a');
                        item.href = '#'; // Cambia esto si tienes URLs para cada notificación
                        item.className = 'dropdown-item';
                        item.innerHTML = `
                            <i class="${notification.icon} mr-2"></i> ${notification.text}
                            <small class="float-right text-muted">${notification.time}</small>
                        `;
                        dropdownMenu.appendChild(item);
                    });

                    // Mostrar el icono de notificación
                    notificationIcon.style.display = 'block';
                } else {
                    notificationBadge.textContent = 0;
                    dropdownMenu.innerHTML = '<a href="#" class="dropdown-item">No new notifications</a>';

                    // Ocultar el icono de notificación si no hay notificaciones
                    notificationIcon.style.display = 'none';
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    // Configura el intervalo para obtener nuevas notificaciones.
    setInterval(fetchNotifications, 5000); // Cada 5 segundos
});
*/

</script>
