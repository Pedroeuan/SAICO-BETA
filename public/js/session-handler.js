document.addEventListener('DOMContentLoaded', function () {
    let idleTime = 0;
    const sessionLifetime = 120; // Duración de la sesión en minutos

    function resetIdleTime() {
        idleTime = 0;
    }

    function startIdleTimer() {
        setInterval(() => {
            idleTime++;
            if (idleTime >= sessionLifetime) {
                Swal.fire({
                    title: 'Sesión Expirada',
                    text: 'Tu sesión ha expirado debido a inactividad. La sesión se cerrará automáticamente.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cerrarSesion(); // Llamar a la función para cerrar sesión
                    }else{
                        cerrarSesion(); // Llamar a la función para cerrar sesión
                    }
                });
            }
        }, 60000); // Incrementar el contador cada minuto (60000 ms)
    }

    function cerrarSesion() {
        fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => {
            if (response.ok) {
                window.location.reload(); // Recargar la página o redirigir a una pantalla específica
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cerrar la sesión correctamente.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            }
        })
        .catch(error => {
            console.error('Error al cerrar la sesión:', error);
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al intentar cerrar la sesión.',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
        });
    }

    // Detectar actividad del usuario
    window.onmousemove = resetIdleTime;
    window.onkeypress = resetIdleTime;
    window.onscroll = resetIdleTime;
    window.onclick = resetIdleTime;

    startIdleTimer();
});
