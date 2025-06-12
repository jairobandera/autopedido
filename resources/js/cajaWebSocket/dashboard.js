// resources/js/dashboard.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

document.addEventListener('DOMContentLoaded', () => {
  if (!document.getElementById('tablaPedidos')) {
    return;
  }
    // 0) Variables inyectadas en tu Blade
    const baseShowUrl = window.baseShowUrl;
    const basePagoUrl = window.basePagoUrl;
    const csrfToken = window.csrfToken;

    const tabla = document.getElementById('tablaPedidos');

    // 2) Delegación de CLICK (Ver y Cocina)
    tabla.addEventListener('click', e => {
        // --- Ver detalles ---
        if (e.target.matches('.btn-ver')) {
            const id = e.target.dataset.id;
            fetch(`${baseShowUrl}/${id}`, {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            })
                .then(res => res.ok ? res.json() : Promise.reject())
                .then(p => {
                    document.getElementById('modal-codigo').textContent = p.codigo;
                    document.getElementById('modal-total').textContent = parseFloat(p.total).toFixed(2);
                    const tbody = document.getElementById('modal-detalles');
                    tbody.innerHTML = '';
                    p.detalles.forEach(d => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
            <td>${d.producto.nombre}</td>
            <td>${d.cantidad}</td>
            <td>$${parseFloat(d.subtotal).toFixed(2)}</td>
          `;
                        tbody.appendChild(tr);
                    });
                    document.getElementById('btn-editar-pedido').onclick = () => {
                        window.location.href = `${baseShowUrl}/${id}/edit`;
                    };
                    new bootstrap.Modal(document.getElementById('modalVerPedido')).show();
                    document.getElementById('btn-imprimir-modal').onclick = () => {
                        window.open(`/caja/pedidos/${id}/comprobante`, '_blank');
                    };
                })
                .catch(() => Swal.fire('Error', 'No pude cargar los detalles', 'error'));
        }

        // --- Enviar a cocina ---
        if (e.target.matches('.btn-cocina')) {
            const id = e.target.dataset.id;
            Swal.fire({
                title: '¿Enviar a cocina?',
                text: '¿Estás seguro de que querés marcar este pedido como "En Preparación"?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then(({ isConfirmed }) => {
                if (!isConfirmed) return;
                fetch(`${baseShowUrl}/${id}/estado`, {
                    method: 'PATCH',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ estado: 'En Preparacion' })
                })
                    .then(res => {
                        if (!res.ok) throw 0;
                        const tr = e.target.closest('tr');
                        tr.querySelector('.td-estado').innerHTML =
                            `<span class="badge bg-warning">En Preparacion</span>`;
                        const sel = tr.querySelector('.estado-cambio');
                        sel.value = 'En Preparacion';
                        sel.setAttribute('data-original', 'En Preparacion');
                        e.target.disabled = true;
                        Swal.fire('Listo', 'Pedido en preparación', 'success');
                    })
                    .catch(() => {
                        Swal.fire('Error', 'No se pudo enviar a cocina', 'error');
                    });
            });
        }
    });

    // 3) Delegación de CHANGE (Estado pedido y Estado pago)
    tabla.addEventListener('change', e => {
        // --- Cambio de estado del pedido ---
        if (e.target.matches('.estado-cambio')) {
            const sel = e.target;
            const nuevo = sel.value;
            const id = sel.dataset.id;

            Swal.fire({
                title: `Confirmar "${nuevo}"`,
                text: `¿Deseas marcar este pedido como "${nuevo}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then(({ isConfirmed }) => {
                if (!isConfirmed) {
                    sel.value = sel.getAttribute('data-original');
                    return;
                }
                fetch(`${baseShowUrl}/${id}/estado`, {
                    method: 'PATCH',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ estado: nuevo })
                })
                    .then(res => {
                        if (!res.ok) throw 0;
                        return res.json();
                    })
                    .then(() => {
                        const tr = sel.closest('tr');
                        const tdBadge = tr.querySelector('.td-estado');
                        const color = {
                            Cancelado: 'bg-danger',
                            Recibido: 'bg-secondary',
                            'En Preparacion': 'bg-warning',
                            Listo: 'bg-info',
                            Entregado: 'bg-success'
                        }[nuevo] || 'bg-secondary';
                        tdBadge.innerHTML = `<span class="badge ${color}">${nuevo}</span>`;
                        const cookBtn = tr.querySelector('.btn-cocina');
                        if (cookBtn) cookBtn.disabled = (nuevo === 'En Preparacion');
                        if (nuevo === 'Entregado') tr.remove();
                        else sel.setAttribute('data-original', nuevo);
                        Swal.fire('Hecho', `Estado cambiado a "${nuevo}"`, 'success');
                    })
                    .catch(() => {
                        sel.value = sel.getAttribute('data-original');
                        Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                    });
            });
        }

        // --- Cambio de estado de pago ---
        if (e.target.matches('.pago-cambio')) {
            const sel = e.target;
            const nuevo = sel.value;
            const pagoId = sel.dataset.id;

            // Opcional: muestra aquí tu loading si quieres

            fetch(`${basePagoUrl}/${pagoId}/estado`, {
                method: 'PATCH',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ estado: nuevo })
            })
                .then(res => {
                    // Opcional: oculta tu loading
                    if (!res.ok) throw 0;
                    return res.json();
                })
                .then(() => {
                    // 1) Mapa de colores
                    const colorMap = {
                        Completado: 'bg-success',
                        Pendiente: 'bg-warning',
                        Fallido: 'bg-danger'
                    };

                    // 2) Elimino **todos** los bg-* posibles
                    Object.values(colorMap).forEach(bgClass => {
                        sel.classList.remove(bgClass);
                    });

                    // 3) Aplico la nueva clase
                    const clase = colorMap[nuevo] || 'bg-secondary';
                    sel.classList.add(clase);

                    // 4) Aseguro que el texto sea blanco
                    sel.classList.add('text-white');
                    sel.style.color = '#fff';

                    // 5) Actualizo el atributo original
                    sel.setAttribute('data-original', nuevo);
                })
                .catch(() => {
                    // Opcional: oculta tu loading
                    sel.value = sel.getAttribute('data-original');
                    Swal.fire('Error', 'No se pudo actualizar', 'error');
                });
        }
    });

    // 4) Inicializa Pusher/Echo y escucha nuevos pedidos
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted: true,
        disableStats: true,
    });

    window.Echo.channel('pedidos')
        .listen('PedidoCreado', async (e) => {
            try {
                const sonido = document.getElementById('new-order-sound');
                sonido.play().catch(() => {/* Ignorar si el navegador bloquea autoplay */ });

                const res = await fetch(`${baseShowUrl}/${e.id}/fila`, {
                    headers: { 'Accept': 'text/html' }
                });
                if (!res.ok) throw new Error('No pude cargar la fila');
                const html = await res.text();
                const fila = document.createElement('tr');
                fila.classList.add('pedido-card');
                fila.dataset.id = e.id;
                fila.innerHTML = html;
                tabla.querySelector('tbody').prepend(fila);
            } catch (err) {
                console.error(err);
            }
        })
        .listen('PedidoEstadoActualizado', e => {
            if (e.estado !== 'Listo') return;

            // 1) Localizar la fila de la tabla
            const tr = tabla.querySelector(`tr.pedido-card[data-id="${e.id}"]`);
            if (!tr) return;

            // 2) Actualizar la celda de estado
            const tdEstado = tr.querySelector('.td-estado');
            if (tdEstado) {
                tdEstado.innerHTML = `<span class="badge bg-info">Listo</span>`;
            }

            // 3) Deshabilitar el botón “Enviar a Cocina”
            const btnCocina = tr.querySelector('.btn-cocina');
            if (btnCocina) btnCocina.disabled = true;

            // 4) **Actualizar el <select> de Acciones**
            const sel = tr.querySelector('select.estado-cambio');
            if (sel) {
                sel.value = 'Listo';             // Ajusta al valor exacto de la opción
                sel.setAttribute('data-original', 'Listo');
                //sel.disabled = true;             // Si quieres bloquear cambios futuros
            }

            //5) Mostrar un toast informativo
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: `Pedido #${e.id} listo`,
                showConfirmButton: false,
                timer: 2000
            });
        });

});
