import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    // Error al editar usuario (nombre duplicado)
    if (window.usuarioDuplicado) {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre duplicado',
            text: `Ya existe un usuario con el nombre "${window.usuarioDuplicado}".`,
            confirmButtonColor: '#dc3545',
        });
    }
});

// Función para pasar los mensajes de session al JS
window.usuarioDuplicado = @json(session('usuario_duplicado'));
