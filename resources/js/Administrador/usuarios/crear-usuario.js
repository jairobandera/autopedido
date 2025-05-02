import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    // Éxito al crear usuario
    if (window.usuarioCreado) {
        Swal.fire({
            icon: 'success',
            title: '¡Usuario creado!',
            text: `El usuario "${window.usuarioCreado}" se ha creado exitosamente.`,
            confirmButtonColor: '#198754',
        });
    }

    // Error al crear usuario (nombre duplicado)
    if (window.usuarioDuplicado) {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre duplicado',
            text: `Ya existe un usuario con el nombre "${window.usuarioDuplicado}".`,
            confirmButtonColor: '#dc3545',
        });
    }
});
