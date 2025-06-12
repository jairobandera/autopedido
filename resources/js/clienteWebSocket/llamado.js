// resources/js/clienteWebSocket/llamado.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const STORAGE_KEY = 'removedPedidos';

// 1) Calcula ms que faltan para la próxima medianoche
function msUntilEndOfDay() {
    const now = new Date();
    const end = new Date(now);
    end.setHours(24, 0, 0, 0);
    return end.getTime() - now.getTime();
}

// 2) Gestion LocalStorage -------------------------------------
// Lee y parsea el array de removidos [{id, expires, remaining}, ...]
function getRemoved() {
    try {
        return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    } catch {
        return [];
    }
}

// Guarda el array completo
function setRemoved(arr) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(arr));
}

// Filtra los que aún no expiraron y los devuelve
function pruneRemoved() {
    const now = Date.now();
    const valid = getRemoved().filter(item => item.expires > now);
    setRemoved(valid);
    return valid;
}

// Añade un ID con expiración a medianoche y su `remaining`
function addRemoved(id) {
    const arr = pruneRemoved();
    const ttl = msUntilEndOfDay();
    arr.push({
        id,
        expires: Date.now() + ttl,
        remaining: ttl
    });
    setRemoved(arr);
}

// 3) DOMContentLoaded -----------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const row = document.getElementById('row-pedidos');
    if (!row) return;

    // 3a) Al arrancar quita del DOM los removidos aún no expirados
    pruneRemoved().forEach(item => {
        const col = row.querySelector(`.col-md-4[data-id="${item.id}"]`);
        if (col) col.remove();
    });

    // 4) Función para agregar cards -------------------------------
    function agregarPedido(p) {
        const removedIds = pruneRemoved().map(i => i.id);
        if (removedIds.includes(p.id)) return;

        const col = document.createElement('div');
        col.className = 'col-md-4 mb-4';
        col.dataset.id = p.id;

        let clienteHtml = '';
        if (p.cliente) {
            const last4 = p.cliente.cedula.slice(-4);
            clienteHtml = `
        <p><strong>Cliente:</strong> ${p.cliente.nombre} ${p.cliente.apellido}</p>
        <p><strong>Cédula:</strong> ****${last4}</p>
      `;
        } else {
            clienteHtml = `<p class="text-muted">Cliente: —</p>`;
        }

        const hora = new Date(p.updated_at).toLocaleString('es-AR', {
            day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
        });

        col.innerHTML = `
      <div class="card shadow-sm h-100">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
          <span>Pedido ${p.codigo}</span>
          <button type="button" class="btn-close btn-close-white remove-card" aria-label="Quitar"></button>
        </div>
        <div class="card-body">
          ${clienteHtml}
        </div>
        <div class="card-footer text-end">
          <small class="text-muted">Entregado: ${hora}</small>
        </div>
      </div>
    `;

        // listener “X”
        col.querySelector('.remove-card').addEventListener('click', () => {
            col.remove();
            addRemoved(p.id);
        });

        row.prepend(col);
    }

    // 5) Inicializa Pusher/Echo -----------------------------------
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted: true,
        disableStats: true,
    });

    window.Echo
        .channel('pedidos')
        .listen('PedidoEstadoActualizado', async e => {
            if (e.estado !== 'Entregado') return;
            try {
                const res = await fetch(`${window.baseShowUrl}${e.id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('no encontrado');
                const p = await res.json();
                agregarPedido(p);
            } catch (err) {
                console.error('No pude cargar detalles público de pedido', e.id, err);
            }
        });

    // 6) Delegación de clicks “X” (por si hay cards estáticas)
    document.addEventListener('click', e => {
        if (e.target.matches('.remove-card')) {
            const col = e.target.closest('.col-md-4');
            if (!col) return;
            const id = parseInt(col.dataset.id, 10);
            addRemoved(id);
            col.remove();
        }
    });

    // 7) Cada minuto, limpia el DOM de expirados sin recargar
    setInterval(() => {
        pruneRemoved().forEach(item => {
            const col = row.querySelector(`.col-md-4[data-id="${item.id}"]`);
            if (col) col.remove();
        });
    }, 60_000 /* ms */);
});
