// public/js/session-handler.js
document.addEventListener('DOMContentLoaded', function() {
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
                    text: 'Tu sesión ha expirado debido a inactividad. Serás redirigido a la página de inicio de sesión.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }else{
                        window.location.href = '/login';
                    }
                });
            }
        }, 60000); // Incrementar el contador de inactividad cada minuto (60000 ms)
    }

    // Reiniciar el tiempo de inactividad al detectar actividad del usuario
    window.onmousemove = resetIdleTime;
    window.onkeypress = resetIdleTime;
    window.onscroll = resetIdleTime;
    window.onclick = resetIdleTime;

    startIdleTimer();
});