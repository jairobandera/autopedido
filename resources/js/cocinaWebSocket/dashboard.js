import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// —————————————————————————————————————————————
// 0) Inyectar CSS para la vibración
const style = document.createElement('style');
style.textContent = `
  @keyframes vibrar {
    0% { transform: translate(0, 0) rotate(0); }
    25% { transform: translate(-2px, 2px) rotate(-1deg); }
    50% { transform: translate(2px, -2px) rotate(1deg); }
    75% { transform: translate(-2px, -2px) rotate(1deg); }
    100% { transform: translate(2px, 2px) rotate(0); }
  }
  .vibrar {
    animation: vibrar 0.1s infinite;
  }
`;
+document.head.appendChild(style);
+// —————————————————————————————————————————————

    document.addEventListener('DOMContentLoaded', () => {
        // Variables inyectadas desde Blade
        //const baseCocinaUrl = window.cocinaBaseUrl;
        const baseCocinaUrl = window.baseCocinaUrl;
        const csrfToken = window.csrfToken;

        console.log('→ dashboard.js usando baseCocinaUrl =', baseCocinaUrl);

        const row = document.getElementById('row-pedidos');
        const alertVacio = document.getElementById('alert-vacio');
        let previousIds = [];

        function agregarPedido(pedido) {
            // Si no hay más pedidos, quita alerta
            alertVacio.classList.add('d-none');

            const numero = row.children.length + 1;
            const itemsHtml = pedido.detalles.map(det => {
                const ingQ = det.ingredientes_quitados.length
                    ? `<div class="small text-danger mt-1">Sin: ${det.ingredientes_quitados.map(i => i.nombre).join(', ')}</div>`
                    : '';
                return `
        <li class="mb-3 pb-2" style="border-bottom:1px solid rgba(0,0,0,0.1);">
          <div class="d-flex justify-content-between">
            <strong>${det.producto.nombre}</strong>
            <span>x ${det.cantidad}</span>
          </div>
          ${ingQ}
        </li>`;
            }).join('');

            const created = new Date(pedido.created_at)
                .toLocaleString('es-AR', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit' });

            row.insertAdjacentHTML('beforeend', `
      <div class="col-md-4" data-id="${pedido.id}">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-warning text-white">Pedido #${numero}</div>
          <div class="card-body" style="max-height:200px;overflow-y:auto;">
            <ul class="list-unstyled mb-0">
              ${itemsHtml}
            </ul>
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Solicitado: ${created}</small>
            <button class="btn btn-sm btn-success btn-listo" data-id="${pedido.id}">
              <i class="bi bi-check-circle"></i> Listo
            </button>
          </div>
        </div>
      </div>`);

            // Re-asigna el evento “Listo”
            const btn = row.querySelector(`.col-md-4[data-id="${pedido.id}"] .btn-listo`);
            if (btn) btn.onclick = () => marcarListo(pedido.id);
        }

        function markNew(pedidos) {
            const currentIds = pedidos.map(p => p.id);
            const newIds = currentIds.filter(id => !previousIds.includes(id));
            newIds.forEach(id => {
                const card = row.querySelector(`.col-md-4[data-id="${id}"] .card`);
                if (!card) return;

                //const badge = document.createElement('span');
                // 1) Crear y mostrar badge “Nuevo”
                const badge = document.createElement('span')
                badge.className = 'badge bg-success position-absolute top-0 end-0 m-2';
                badge.textContent = 'NUEVO';
                card.style.position = 'relative';
                card.appendChild(badge);
                // 2) Aplicar efecto vibrar y programar su retirada a los 4s
                card.classList.add('vibrar');
                setTimeout(() => {
                    card.classList.remove('vibrar');
                    badge.remove();
                }, 2000);

                // toast opcional
                const pedido = pedidos.find(p => p.id === id);
                if (pedido) {
                    const detallesHtml = pedido.detalles.map(det => {
                        const ingQ = det.ingredientes_quitados.length
                            ? `<div class="small text-danger">Sin: ${det.ingredientes_quitados.map(i => i.nombre).join(', ')}</div>`
                            : '';
                        return `<div style="border-bottom:1px solid #eee;padding:4px 0;">
            <strong>${det.producto.nombre}</strong> x ${det.cantidad}${ingQ}
          </div>`;
                    }).join('');
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        html: `<div style="max-height:200px;overflow:auto;text-align:left;">
                   <h6>Nuevo Pedido</h6>${detallesHtml}
                 </div>`,
                        showConfirmButton: false,
                        timer: 8000,
                        width: 300,
                        didOpen: el => el.classList.add('swal2-shake')
                    });
                }
            });
            previousIds = currentIds;
        }

        function marcarListo(id) {
            Swal.fire({
                title: 'Marcar como listo?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, Listo',
                cancelButtonText: 'Cancelar'
            }).then(({ isConfirmed }) => {
                if (!isConfirmed) return;
                fetch(`${baseCocinaUrl}/${id}/estado`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ estado: 'Listo' })
                })
                    .then(r => r.ok ? r.json() : Promise.reject())
                    .then(() => {
                        Swal.fire({ icon: 'success', title: 'Pedido listo', toast: true, position: 'top-end', timer: 1200, showConfirmButton: false });
                        // 2) Remover la card del pedido que ya está listo
                        const cardCol = row.querySelector(`.col-md-4[data-id="${id}"]`);
                        if (cardCol) cardCol.remove();

                        // (Opcional) Si no queda ninguna, muestra de nuevo la alerta
                        if (!row.children.length) {
                            document.getElementById('alert-vacio').classList.remove('d-none');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'No se pudo actualizar', 'error'));
            });
        }

        // Inicializa Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            forceTLS: true,
            encrypted: true,
            disableStats: true,
        });

        // ESCUCHA solo los pedidos que cambian a 'En Preparacion'
        // …
        window.Echo.channel('pedidos')
            .listen('PedidoEstadoActualizado', async e => {
                if (e.estado !== 'En Preparacion') return;

                try {
                    console.log('Pidiendo detalles ➔', `$${window.baseCocinaUrl}/${e.id}`);
                    const res = await fetch(`${window.baseCocinaUrl}/${e.id}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!res.ok) throw new Error('No pude cargar detalles de pedido');
                    const pedido = await res.json();

                    // Agrega y marca nuevo
                    agregarPedido(pedido);
                    markNew([pedido]);
                } catch (err) {
                    console.error(err);
                }
            });

        document.querySelectorAll('.btn-listo').forEach(btn => {
            const id = btn.dataset.id;
            btn.onclick = () => marcarListo(id);
        });

    });
