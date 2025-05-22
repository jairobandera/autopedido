@extends('layouts.app-cocina')

@section('title', 'Dashboard Cocina')

@section('content')
  <div class="container">
    <h2 class="mb-4 text-center">Pedidos</h2>
    <div id="alert-vacio" class="alert alert-info d-none">No hay pedidos en preparación.</div>
    <div id="row-pedidos" class="row g-4">
    {{-- Aquí se inyectarán las cards dinámicamente --}}
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
    // ——— Inyectar keyframes de vibración ———
    const style = document.createElement('style');
    style.innerHTML = `
      @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,80% { transform: translateX(-5px); }
      40%,60% { transform: translateX(5px); }
      }
      .swal2-shake {
      animation: shake 0.1s infinite;
      }
    `;
    document.head.appendChild(style);

    const row = document.getElementById('row-pedidos');
    const alertVacio = document.getElementById('alert-vacio');
    let previousIds = [];

    function renderPedidos(pedidos) {
      row.innerHTML = '';
      if (!pedidos.length) {
      alertVacio.classList.remove('d-none');
      return;
      }
      alertVacio.classList.add('d-none');

      pedidos.forEach((pedido, idx) => {
      const numeroPedido = idx + 1;
      const items = pedido.detalles.map(det => {
        const ingQ = det.ingredientes_quitados.length
        ? `<div class="small text-danger mt-1">
           Sin: ${det.ingredientes_quitados.map(i => i.nombre).join(', ')}
           </div>`
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

      row.insertAdjacentHTML('beforeend', `
        <div class="col-md-4" data-id="${pedido.id}">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-warning text-white">
          Pedido #${numeroPedido}
          </div>
          <div class="card-body" style="max-height:200px;overflow-y:auto;">
          <ul class="list-unstyled mb-0">
            ${items}
          </ul>
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center">
          <small class="text-muted">
            Solicitado: ${new Date(pedido.created_at)
        .toLocaleString('es-AR', {
          hour: '2-digit',
          minute: '2-digit',
          day: '2-digit',
          month: '2-digit'
        })}
          </small>
          <button class="btn btn-sm btn-success btn-listo" data-id="${pedido.id}">
            <i class="bi bi-check-circle"></i> Listo
          </button>
          </div>
        </div>
        </div>`);
      });

      // reasignar eventos “Listo”
      document.querySelectorAll('.btn-listo').forEach(btn => {
      btn.onclick = () => marcarListo(btn.dataset.id);
      });
    }

    function markNew(pedidos) {
      const currentIds = pedidos.map(p => p.id);
      const newIds = currentIds.filter(id => !previousIds.includes(id));

      newIds.forEach(id => {
      const index = pedidos.findIndex(p => p.id === id);
      const numeroPedido = index + 1;
      const card = row.querySelector(`.col-md-4[data-id="${id}"] .card`);
      if (card) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-success position-absolute top-0 end-0 m-2';
        badge.textContent = 'NUEVO';
        card.style.position = 'relative';
        card.appendChild(badge);
        setTimeout(() => badge.remove(), 30000);
      }
      const pedido = pedidos[index];
      if (pedido) {
        const detallesHtml = pedido.detalles.map(det => {
        const ingQ = det.ingredientes_quitados.length
          ? `<div class="small text-danger">Sin: ${det.ingredientes_quitados.map(i => i.nombre).join(', ')}</div>`
          : '';
        return `
          <div style="border-bottom:1px solid #eee;padding:4px 0;">
          <strong>${det.producto.nombre}</strong> x ${det.cantidad}
          ${ingQ}
          </div>`;
        }).join('');
        Swal.fire({
        toast: true,
        position: 'top-end',
        html: `
          <div style="max-height:200px;overflow:auto;text-align:left;">
          <h6>Nuevo Pedido #${numeroPedido}</h6>
          ${detallesHtml}
          </div>`,
        showConfirmButton: false,
        timer: 10000,
        width: 300,
        didOpen: el => el.classList.add('swal2-shake')
        });
      }
      });

      previousIds = currentIds;
    }

    function fetchPedidos() {
      fetch("{{ route('cocina.pedidos.nuevos') }}", { headers: { Accept: 'application/json' } })
      .then(r => r.ok ? r.json() : Promise.reject(r.status))
      .then(pedidos => {
        const hoy = new Date().toISOString().slice(0, 10);
        const pedidosHoy = pedidos.filter(p => p.created_at.slice(0, 10) === hoy);
        renderPedidos(pedidosHoy);
        markNew(pedidosHoy);
      })
      .catch(console.error);
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
      fetch(`{{ url('/cocina/pedidos') }}/${id}/estado`, {
        method: 'PATCH',
        headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
        },
        body: JSON.stringify({ estado: 'Listo' })
      })
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(() => {
        Swal.fire({
          icon: 'success',
          title: 'Pedido LISTO y entregado',
          toast: true,
          position: 'top-end',
          timer: 1200,
          showConfirmButton: false
        });
        fetchPedidos();
        })
        .catch(() => Swal.fire('Error', 'No se pudo actualizar el estado.', 'error'));
      });
    }

    // primera carga y luego cada 10s
    fetchPedidos();
    setInterval(fetchPedidos, 10000);
    });
  </script>
@endsection