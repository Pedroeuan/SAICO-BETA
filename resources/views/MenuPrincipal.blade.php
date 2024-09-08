
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')  
<!-- form start -->
<br>
<br>
<br>
@php 
//dd($user);
//dd($certificados);
//dd($notificaciones);
@endphp

@stop

@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>

<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateNotifications() {
        fetch('{{ url("notificaciones/update") }}')
            .then(response => response.json())
            .then(data => {
                const notificationBadge = document.querySelector('#my-notification .badge'); 
                const notificationList = document.querySelector('#my-notification .dropdown-menu'); 

                if (notificationBadge && notificationList) { // Verifica si los elementos existen
                    if (data.length > 0) {
                        notificationBadge.textContent = data.length;
                        notificationBadge.style.display = 'inline';
                        notificationList.innerHTML = '';

                        data.forEach(notificacion => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('dropdown-item');
                            listItem.textContent = notificacion.message; // Ajusta según el formato de notificación
                            notificationList.appendChild(listItem);
                        });
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Llamada inicial y periódica
    updateNotifications();
    setInterval(updateNotifications, 30000); // 30 segundos
});
</script>
@endsection


