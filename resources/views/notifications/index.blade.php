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
    @php 
    //dd($notifications);
    @endphp
@stop

@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">-->
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>

<script>
    $(document).ready(function () {
    function fetchNotifications() {
        $.ajax({
            url: '{{ route('notifications.fetch') }}',
            method: 'GET',
            success: function (data) {
                // Actualizar el ícono de notificaciones con el número de nuevas notificaciones
                $('#my-notification').data('label', data.length);

                // Llenar el menú de notificaciones
                let dropdown = $('#my-notification-dropdown');
                dropdown.empty();

                data.forEach(function (notification) {
                    dropdown.append(`
                        <a href="${notification.url}" class="dropdown-item">
                            <i class="fas fa-exclamation-circle"></i> ${notification.message}
                        </a>
                    `);
                });
            },
            error: function (error) {
                console.error('Error al obtener notificaciones:', error);
            }
        });
    }

    // Llamar a la función cada 30 segundos (o como esté configurado)
    setInterval(fetchNotifications, 30000);
});

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

@endsection


