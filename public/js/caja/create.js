// public/js/caja.js
window.clienteSeleccionado = null;

document.addEventListener('DOMContentLoaded', () => {
    let pagina = 1,
        termino = '';
    const carrito = [];
    // En el Blade dejaremos la URL base en un atributo data- para leerlo aquí:
    const baseProdUrl = document
        .getElementById('base-prod-url')
        .getAttribute('data-url');
    //let clienteSeleccionado = null;
    let nombreClienteSeleccionado = '';

    // 1) Instancia única del modal de “Pedido Creado”
    const modalEl = document.getElementById('modalPedidoCreado');
    const modal = new bootstrap.Modal(modalEl, {
        backdrop: 'static', // impide cierre con click afuera
        keyboard: false     // impide cierre con Esc
    });

    // 2) Escuchar intento de cierre “forzado”
    modalEl.addEventListener('hidePrevented.bs.modal', () => {
        Swal.fire({
            position: 'top-start',
            icon: 'info',
            title: 'Debes hacer clic en "Ver Pedidos" o "Tomar otro pedido"',
            toast: true,
            timer: 2000,
            showConfirmButton: false
        });
    });

    // 3) Función para cargar listado de productos
    function loadProductos() {
        fetch(`${baseProdUrl}?page=${pagina}&q=${encodeURIComponent(termino)}`, {
            headers: { Accept: 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                let html =
                    '<table class="table"><thead><tr><th>Imagen</th><th>Nombre</th><th>Precio</th><th></th></tr></thead><tbody>';
                data.data.forEach(p => {
                    html += `
                        <tr>
                          <td><img src="${p.imagen}" style="height:40px"></td>
                          <td>${p.nombre}</td>
                          <td>$${parseFloat(p.precio).toFixed(2)}</td>
                          <td>
                            <button class="btn btn-sm btn-primary btn-seleccionar" data-id="${p.id}">
                              Seleccionar
                            </button>
                          </td>
                        </tr>`;
                });
                html += '</tbody></table>';
                html += `
                  <div class="d-flex justify-content-center my-2">
                    <nav>${data.links}</nav>
                  </div>`;
                document.getElementById('listado-productos').innerHTML = html;
            });
    }

    // 4) Buscar productos (click en el botón “Buscar”)
    document.getElementById('btn-search').addEventListener('click', () => {
        termino = document.getElementById('search-productos').value.trim();
        pagina = 1;
        loadProductos();
    });

    // 5) Delegación: paginación y selección de producto
    document
        .getElementById('listado-productos')
        .addEventListener('click', e => {
            const link = e.target.closest('a.page-link');
            if (link) {
                e.preventDefault();
                pagina = new URL(link.href).searchParams.get('page') || 1;
                loadProductos();
                return;
            }
            if (e.target.matches('.btn-seleccionar')) {
                const id = e.target.getAttribute('data-id');
                fetch(`${baseProdUrl}/${id}`, {
                    headers: { Accept: 'application/json' }
                })
                    .then(res => res.json())
                    .then(producto => {
                        // Llenar modalDetalle
                        document.getElementById('d-titulo').textContent =
                            producto.nombre;
                        document.getElementById('d-imagen').src = producto.imagen;
                        document.getElementById('d-precio').textContent = parseFloat(
                            producto.precio
                        ).toFixed(2);
                        document.getElementById('d-cantidad').value = 1;

                        const cont = document.getElementById('d-ingredientes');
                        cont.innerHTML = '<label>Quitar ingredientes:</label>';
                        producto.ingredientes.forEach(ing => {
                            cont.innerHTML += `
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox"
                                         value="${ing.id}" id="ing-${ing.id}"
                                         ${ing.es_obligatorio ? 'disabled checked' : ''
                                }>
                                  <label class="form-check-label" for="ing-${ing.id}">
                                    ${ing.nombre} ${ing.es_obligatorio ? '(obligatorio)' : ''
                                }
                                  </label>
                                </div>`;
                        });

                        // Guardar datos en el botón “Agregar”
                        const btnAdd = document.getElementById('d-agregar');
                        btnAdd.setAttribute('data-id', producto.id);
                        btnAdd.setAttribute('data-precio', producto.precio);

                        new bootstrap.Modal(
                            document.getElementById('modalDetalle')
                        ).show();
                    });
            }
        });

    // 6) Agregar ítem al carrito desde modalDetalle
    document.getElementById('d-agregar').addEventListener('click', () => {
        const btn = document.getElementById('d-agregar');
        const id = btn.getAttribute('data-id');
        const nombre = document.getElementById('d-titulo').textContent;
        const precio = parseFloat(btn.getAttribute('data-precio'));
        const cantidad = parseInt(
            document.getElementById('d-cantidad').value,
            10
        );

        // Ingredientes quitados
        const quitados = Array.from(
            document.querySelectorAll(
                '#d-ingredientes .form-check-input:checked'
            )
        )
            .filter(chk => !chk.disabled)
            .map(chk => {
                const ingId = parseInt(chk.value, 10);
                let ingNombre = document
                    .querySelector(`label[for="${chk.id}"]`)
                    .textContent.replace(/\s*\(obligatorio\)$/, '')
                    .trim();
                return { id: ingId, nombre: ingNombre };
            });

        const subtotal = precio * cantidad;
        carrito.push({ id, nombre, precio, cantidad, quitados, subtotal });

        renderCarrito();
        bootstrap.Modal.getInstance(
            document.getElementById('modalDetalle')
        ).hide();
    });

    // 7) Función para renderizar el carrito en la tabla
    function renderCarrito() {
        const tbody = document.querySelector('#tabla-carrito tbody');
        tbody.innerHTML = '';

        carrito.forEach((item, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.nombre}</td>
                <td>${item.quitados.length
                    ? item.quitados.map(q => q.nombre).join(', ')
                    : '<em>ninguno</em>'
                }</td>
                <td>${item.cantidad}</td>
                <td>$${item.subtotal.toFixed(2)}</td>
                <td>
                  <button class="btn btn-sm btn-danger btn-eliminar" data-index="${idx}">
                    &times;
                  </button>
                </td>`;
            tbody.appendChild(tr);
        });

        document.getElementById('btn-finalizar').disabled =
            carrito.length === 0;
    }

    // 8) Eliminar un ítem del carrito (click en “×”)
    document
        .querySelector('#tabla-carrito tbody')
        .addEventListener('click', e => {
            if (!e.target.matches('.btn-eliminar')) return;
            const idx = parseInt(e.target.getAttribute('data-index'), 10);
            carrito.splice(idx, 1);
            renderCarrito();
        });

    // ————————————
    // Selección de cliente
    // ————————————

    // 9) Buscar cliente por cédula
    document
        .getElementById('btn-buscar-cliente')
        .addEventListener('click', () => {
            const cedula = document.getElementById('input-cedula').value.trim();
            if (!cedula) {
                Swal.fire({
                    icon: 'warning',
                    text: 'Debes ingresar una cédula para buscar.'
                });
                return;
            }

            const tbodyCli = document.querySelector('#tabla-clientes tbody');
            tbodyCli.innerHTML =
                '<tr><td colspan="7" class="text-center">Buscando...</td></tr>';

            fetch(
                `/caja/clientes/search?cedula=${encodeURIComponent(cedula)}`,
                {
                    headers: { Accept: 'application/json' }
                }
            )
                .then(res => res.json())
                .then(data => {
                    tbodyCli.innerHTML = '';
                    const activos = data.filter(c => c.activo);
                    const inactivos = data.filter(c => !c.activo);

                    if (inactivos.length > 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Cliente inactivo',
                            text: 'El cliente existe pero está inactivo. Comuníquese con el administrador.'
                        });
                    }

                    if (activos.length === 0) {
                        tbodyCli.innerHTML =
                            '<tr><td colspan="7" class="text-center">No se encontraron clientes activos.</td></tr>';
                        return;
                    }

                    activos.forEach(cliente => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${cliente.cedula}</td>
                            <td>${cliente.nombre}</td>
                            <td>${cliente.apellido}</td>
                            <td>${cliente.telefono}</td>
                            <td>${cliente.puntos}</td>
                            <td>${cliente.activo ? 'Sí' : 'No'}</td>
                            <td>
                              <button class="btn btn-sm btn-primary btn-seleccionar-cliente"
                                      data-id="${cliente.id}"
                                      data-nombre="${cliente.nombre}"
                                      data-apellido="${cliente.apellido}">
                                Seleccionar
                              </button>
                            </td>`;
                        tbodyCli.appendChild(tr);
                    });
                })
                .catch(err => {
                    tbodyCli.innerHTML =
                        '<tr><td colspan="7" class="text-danger text-center">Error al buscar.</td></tr>';
                    console.error(err);
                });
        });

    // 10) Seleccionar cliente (click en “Seleccionar”)
    document
        .querySelector('#tabla-clientes tbody')
        .addEventListener('click', e => {
            if (!e.target.matches('.btn-seleccionar-cliente')) return;
            const id = parseInt(e.target.getAttribute('data-id'), 10);
            const nombre = e.target.getAttribute('data-nombre');
            const apellido = e.target.getAttribute('data-apellido');
            window.clienteSeleccionado = id;
            nombreClienteSeleccionado = `${nombre} ${apellido}`;

            document.getElementById('nombre-cliente').textContent =
                nombreClienteSeleccionado;
            bootstrap.Modal.getInstance(
                document.getElementById('modalClientes')
            ).hide();
        });

    // ————————————
    // Finalizar o reenviar pedido
    // ————————————

    // 11) Finalizar pedido
    document.getElementById('btn-finalizar').addEventListener('click', () => {
        Swal.fire({
            icon: 'question',
            title: 'Confirmar entrega',
            text: '¿Estás seguro de que quieres entregar este pedido?',
            showCancelButton: true,
            confirmButtonText: 'Entregar Pedido',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            if (!result.isConfirmed) return;

            if (!window.clienteSeleccionado) {
                Swal.fire({
                    icon: 'question',
                    text: 'No seleccionaste ningún cliente. ¿Deseas continuar sin asociar cliente?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then(res => {
                    if (res.isConfirmed) enviarPedido(null);
                });
            } else {
                enviarPedido(window.clienteSeleccionado);
            }
        });
    });

    // 12) Función para enviar pedido nuevo vía AJAX
    function enviarPedido(clienteId) {
        // Leemos la ruta (route) desde un atributo data- en el Blade
        const storeUrl = document
            .getElementById('store-pedido-url')
            .getAttribute('data-url');

        const dashboardUrl = document
            .getElementById('dashboard-pedidos-url')
            .getAttribute('data-url');

        const metodo = document.getElementById('metodo-pago-global').value;

        const payload = {
            cliente_id: clienteId,
            items: carrito.map(item => ({
                id: item.id,
                cantidad: item.cantidad,
                quitados: item.quitados.map(q => q.id)
            })),
            metodo_pago: metodo
        };
        console.log('→ PAYLOAD a enviar a store():', payload);

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(json => {
                if (!json.success) {
                    return Swal.fire('Error', json.error || 'Algo salió mal.', 'error');
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Pedido Creado',
                    text: `Código: ${json.codigo}`
                }).then(() => {
                    document.getElementById('modal-codigo-nuevo').textContent =
                        json.codigo;
                    modalEl.setAttribute('data-pedido-id', json.pedido_id);
                    modal.show();
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Error al crear pedido.', 'error');
            });
    }

    // 13) Botón “Ver Pedidos” → redirigir
    document
        .getElementById('btn-ver-pedidos')
        .addEventListener('click', () => {
            modal.hide();
            window.location.href = document
                .getElementById('dashboard-pedidos-url')
                .getAttribute('data-url');
        });

    // Botón "Imprimir Comprobante"
    document.getElementById('btn-imprimir-comprobante').addEventListener('click', () => {
        const pedidoId = document
            .getElementById('modalPedidoCreado')
            .getAttribute('data-pedido-id');
        // Abre la ruta /caja/pedidos/{id}/comprobante en una pestaña nueva
        window.open(`/caja/pedidos/${pedidoId}/comprobante`, '_blank');
    });

    // 14) Botón “Tomar otro pedido”
    document.getElementById('btn-otro-pedido').addEventListener('click', () => {
        carrito.length = 0;
        renderCarrito();
        nombreClienteSeleccionado = '';
        document.getElementById('nombre-cliente').textContent = 'Sin cliente';
        modal.hide();
    });

    // 15) Al abrir modalProductos, cargar página 1
    document
        .getElementById('modalProductos')
        .addEventListener('shown.bs.modal', loadProductos);

    // 1) Registrar Cliente (modalRegistrarCliente)
    const btnGuardarCliente = document.getElementById('btn-guardar-cliente');
    const formRegistrarCliente = document.getElementById('form-registrar-cliente');

    btnGuardarCliente.addEventListener('click', async () => {
        // Limpiar estados previos de validación
        ['rc-cedula', 'rc-nombre', 'rc-apellido', 'rc-telefono'].forEach(id => {
            const input = document.getElementById(id);
            const feedback = document.getElementById('error-' + id.split('-')[1]);
            if (input) input.classList.remove('is-invalid');
            if (feedback) feedback.textContent = '';
        });

        // Obtener valores del formulario
        const cedula = document.getElementById('rc-cedula').value.trim();
        const nombre = document.getElementById('rc-nombre').value.trim();
        const apellido = document.getElementById('rc-apellido').value.trim();
        const telefono = document.getElementById('rc-telefono').value.trim();

        const payload = { cedula, nombre, apellido, telefono };

        try {
            // Tomar la URL desde el atributo action del formulario
            const url = formRegistrarCliente.getAttribute('action');

            // Tomar el token CSRF desde la meta-tag
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (response.status === 422) {
                // Errores de validación: response.json() tendrá { errors: { campo: [ … ] } }
                const errors = await response.json();
                for (const campo in errors.errors) {
                    const inputId = 'rc-' + campo;
                    const feedbackId = 'error-' + campo;
                    const inputElem = document.getElementById(inputId);
                    const feedbackElem = document.getElementById(feedbackId);

                    if (inputElem) inputElem.classList.add('is-invalid');
                    if (feedbackElem) feedbackElem.textContent = errors.errors[campo][0];
                }
                return;
            }

            // Si no hubo error de validación, obtenemos el JSON de respuesta
            const data = await response.json();
            if (data.success) {
                // 1) Cerrar modal
                const modalRegistrar = bootstrap.Modal.getInstance(
                    document.getElementById('modalRegistrarCliente')
                );
                modalRegistrar.hide();

                // 2) Mostrar SweetAlert de confirmación
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente registrado',
                    text: `Se creó el cliente ${data.cliente.nombre} ${data.cliente.apellido}.`,
                    timer: 2000,
                    showConfirmButton: false
                });

                // 3) (Opcional) Asociar este cliente como “seleccionado” en el pedido:
                //window.clienteSeleccionado = data.cliente.id;
                //document.getElementById('nombre-cliente').textContent =
                //   `${data.cliente.nombre} ${data.cliente.apellido}`;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo crear el cliente.',
                });
            }
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo comunicar con el servidor.',
            });
        }
    });

    // 2) Consultar Puntos (modalConsultarPuntos)
    const btnBuscarPuntos = document.getElementById('btn-buscar-puntos');
    const formConsultarPuntos = document.getElementById('form-consultar-puntos');

    // Obtenemos la URL base desde el atributo data-url que definimos en el Blade
    const puntosClienteBaseUrl = document
        .getElementById('puntos-cliente-url')
        .getAttribute('data-url');

    let clienteActualId = null;

    btnBuscarPuntos.addEventListener('click', async () => {
        // Limpiar validación y resultados previos
        document.getElementById('cp-cedula').classList.remove('is-invalid');
        document.getElementById('error-cp-cedula').textContent = '';
        document.getElementById('resultado-puntos').classList.add('d-none');
        document.getElementById('mensaje-puntos').classList.add('d-none');

        // Obtener cédula
        const cedula = document.getElementById('cp-cedula').value.trim();
        if (!cedula) {
            document.getElementById('cp-cedula').classList.add('is-invalid');
            document.getElementById('error-cp-cedula').textContent = 'La cédula es obligatoria.';
            return;
        }

        try {
            // Hacemos GET a /caja/clientes/puntos?cedula=XXXX
            const url = `${puntosClienteBaseUrl}?cedula=${encodeURIComponent(cedula)}`;

            const response = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' }
            });

            if (response.status === 422) {
                const errors = await response.json();
                document.getElementById('cp-cedula').classList.add('is-invalid');
                document.getElementById('error-cp-cedula').textContent = errors.errors.cedula[0];
                return;
            }

            const data = await response.json();
            if (data.success) {
                // Rellenar datos en el modal
                document.getElementById('rp-nombre').textContent = data.cliente.nombre;
                document.getElementById('rp-apellido').textContent = data.cliente.apellido;
                document.getElementById('rp-cedula').textContent = data.cliente.cedula;
                document.getElementById('rp-telefono').textContent = data.cliente.telefono;
                document.getElementById('rp-puntos').textContent = data.cliente.puntos;

                document.getElementById('resultado-puntos').classList.remove('d-none');
                clienteActualId = data.cliente.id;
            } else {
                // data.success === false → “no encontrado”
                document.getElementById('mensaje-puntos').textContent =
                    data.message || 'No se pudo obtener los puntos.';
                document.getElementById('mensaje-puntos').classList.remove('d-none');
                document.getElementById('btn-editar-cliente').classList.add('d-none');
                clienteActualId = null;
            }
        } catch (err) {
            console.error(err);
            document.getElementById('mensaje-puntos').textContent = 'Error de red al intentar consultar puntos.';
            document.getElementById('mensaje-puntos').classList.remove('d-none');
        }
    });

    // 3) Al hacer clic en “Editar Cliente” dentro del modal de puntos
    const btnEditarCliente = document.getElementById('btn-editar-cliente');
    const modalEditarClienteEl = document.getElementById('modalEditarCliente');
    const modalEditarCliente = new bootstrap.Modal(modalEditarClienteEl, {
        backdrop: 'static',
        keyboard: false
    });

    // Form inputs en “Editar Cliente”
    const inputEcId = document.getElementById('ec-id');
    const inputEcCedula = document.getElementById('ec-cedula');
    const inputEcNombre = document.getElementById('ec-nombre');
    const inputEcApellido = document.getElementById('ec-apellido');
    const inputEcTelefono = document.getElementById('ec-telefono');

    // Botón de guardar edición
    const btnGuardarEdicion = document.getElementById('btn-guardar-edicion');

    // Escuchar clic en “Editar Cliente”
    btnEditarCliente.addEventListener('click', () => {
        if (!clienteActualId) return;

        // Rellenar formulario con los datos que ya están visibles en el modal “Consultar Puntos”
        inputEcId.value = clienteActualId;
        inputEcCedula.value = document.getElementById('rp-cedula').textContent;
        inputEcNombre.value = document.getElementById('rp-nombre').textContent;
        inputEcApellido.value = document.getElementById('rp-apellido').textContent;
        inputEcTelefono.value = document.querySelector('#rp-telefono').textContent;
        // Limpiar validaciones previas en el formulario de edición
        ['ec-nombre', 'ec-apellido', 'ec-telefono'].forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
            document.getElementById('error-' + id).textContent = '';
        });

        // Abrir modal de edición
        modalEditarCliente.show();
    });

    // 4) Guardar cambios de cliente (clic en “Guardar Cambios”)
    btnGuardarEdicion.addEventListener('click', async () => {
        // Limpiar validaciones previas
        ['ec-nombre', 'ec-apellido', 'ec-telefono'].forEach(id => {
            const input = document.getElementById(id);
            const feedback = document.getElementById('error-' + id);
            if (input) input.classList.remove('is-invalid');
            if (feedback) feedback.textContent = '';
        });

        const id = inputEcId.value;
        const nombre = inputEcNombre.value.trim();
        const apellido = inputEcApellido.value.trim();
        const telefono = inputEcTelefono.value.trim();

        // Validar campos obligatorios
        if (!nombre) {
            inputEcNombre.classList.add('is-invalid');
            document.getElementById('error-ec-nombre').textContent = 'El nombre es obligatorio.';
            return;
        }
        if (!apellido) {
            inputEcApellido.classList.add('is-invalid');
            document.getElementById('error-ec-apellido').textContent = 'El apellido es obligatorio.';
            return;
        }

        // Armar payload para PATCH
        const payload = { nombre, apellido, telefono };

        try {
            // Tomar CSRF token
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

            // Construir la URL de actualización reemplazando __ID__ por el id real
            let updateUrlTemplate = document
                .getElementById('update-cliente-url')
                .getAttribute('data-url');
            // Ejemplo: "/caja/clientes/__ID__" → reemplazar __ID__
            const updateUrl = updateUrlTemplate.replace('__ID__', encodeURIComponent(id));

            const response = await fetch(updateUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (response.status === 422) {
                // Errores de validación de Laravel
                const errors = await response.json();
                for (const campo in errors.errors) {
                    const inputId = 'ec-' + campo;
                    const feedbackId = 'error-ec-' + campo;
                    const inputElem = document.getElementById(inputId);
                    const feedbackEl = document.getElementById(feedbackId);
                    if (inputElem) inputElem.classList.add('is-invalid');
                    if (feedbackEl) feedbackEl.textContent = errors.errors[campo][0];
                }
                return;
            }

            const data = await response.json();
            if (data.success) {
                // 1) Cerrar modal de edición
                modalEditarCliente.hide();

                // 2) Actualizar el contenido del modal “Consultar Puntos” con los nuevos valores
                document.getElementById('rp-nombre').textContent = data.cliente.nombre;
                document.getElementById('rp-apellido').textContent = data.cliente.apellido;
                // cedula no cambia
                // teléfono no se muestra en el modal de puntos, pero si quisieras agregarlo, aquí lo actualizarías
                document.getElementById('rp-telefono').textContent = data.cliente.telefono;

                // 3) Mostrar notificación de éxito (opcional)
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente actualizado',
                    text: `Datos de ${data.cliente.nombre} actualizados.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo actualizar el cliente.'
                });
            }
        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error de red',
                text: 'No se pudo comunicar con el servidor.'
            });
        }
    });
});
