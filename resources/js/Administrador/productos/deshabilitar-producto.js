import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', function () {
    // Éxito al habilitar producto
    if (window.productoHabilitado) {
        Swal.fire({
            icon: 'success',
            title: '¡Producto habilitado!',
            text: `El producto "${window.productoHabilitado}" fue habilitado correctamente.`,
            confirmButtonColor: '#198754',
        });
    }

    // Error al habilitar producto (producto duplicado)
    if (window.errorHabilitar) {
        Swal.fire({
            icon: 'warning',
            title: 'Ya existe un producto activo',
            text: `No se puede habilitar "${window.errorHabilitar}" porque ya existe activo.`,
            confirmButtonColor: '#dc3545',
        });
    }
});

// Función global para usar en el HTML
window.confirmarHabilitar = function (id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `El producto "${nombre}" será habilitado.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, habilitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-habilitar-' + id).submit();
        }
    });
};
