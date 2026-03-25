document.addEventListener('DOMContentLoaded', function () {
    let idleTime = 0;
    const sessionLifetime = 300; // 5 horas (en minutos)
    let interval;

    function resetIdleTime() {
        idleTime = 0;
    }

    function startIdleTimer() {
        interval = setInterval(() => {
            idleTime++;

            if (idleTime >= sessionLifetime) {
                clearInterval(interval);

                Swal.fire({
                    title: 'Sesión Expirada',
                    text: 'Tu sesión ha expirado debido a inactividad.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    cerrarSesion();
                });
            }
        }, 60000);
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
                window.location.reload();
            } else {
                Swal.fire('Error', 'No se pudo cerrar la sesión correctamente.', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Ocurrió un error al intentar cerrar la sesión.', 'error');
        });
    }

    window.addEventListener('mousemove', resetIdleTime);
    window.addEventListener('keypress', resetIdleTime);
    window.addEventListener('scroll', resetIdleTime);
    window.addEventListener('click', resetIdleTime);
    window.addEventListener('touchstart', resetIdleTime);

    startIdleTimer();
});