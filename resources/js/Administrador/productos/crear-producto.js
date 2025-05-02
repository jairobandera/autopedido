document.addEventListener('DOMContentLoaded', function () {
    //inicializamos Select2
    const categoriaSelect = $('#categoria_ids').select2({
        placeholder: 'Seleccione una o más categorías',
        allowClear: true,
        width: '100%'
    });

    const ingredienteSelect = $('#ingrediente_ids').select2({
        placeholder: 'Seleccione uno o más ingredientes',
        allowClear: true,
        width: '100%',
        tags: false
    });

    //referencia a la tabla
    const ingredientesTable = $('#ingredientes-table tbody');

    //funcion para actualizar la tabla
    function updateIngredientesTable() {
        const selectedValues = ingredienteSelect.val() || [];
        console.log('Selected Values:', selectedValues);

        ingredientesTable.empty();

        if (selectedValues.length === 0) {
            ingredientesTable.append('<tr><td colspan="3" class="text-center">No hay ingredientes seleccionados</td></tr>');
            return;
        }

        selectedValues.forEach(function (id) {
            //aseguramos de que el ID sea numérico
            if (!/^\d+$/.test(id)) {
                console.warn(`ID no válido: ${id}`);
                return;
            }

            const option = ingredienteSelect.find(`option[value="${id}"]`);
            const nombre = option.length ? option.text() : 'Desconocido';
            const isChecked = oldIngredientesObligatorios.includes(id.toString()) ? 'checked' : '';

            console.log('Adding:', { id, nombre, isChecked }); 

            ingredientesTable.append(`
                <tr data-id="${id}">
                    <td>${nombre}</td>
                    <td>
                        <input type="checkbox" name="ingrediente_obligatorio[${id}]" value="1" ${isChecked}>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-ingrediente">Eliminar</button>
                    </td>
                </tr>
            `);
        });
    }

    //ingredientes obligatorios desde old()
    const oldIngredientesObligatorios = window.oldIngredientesObligatorios || [];
    console.log('Old Obligatorios:', oldIngredientesObligatorios);

    //actualizamos tabla al cambiar la selección
    ingredienteSelect.on('change', function () {
        console.log('Select2 Changed');
        updateIngredientesTable();
    });

    //eliminamos ingrediente al hacer clic en el botón
    ingredientesTable.on('click', '.remove-ingrediente', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        console.log('Removing:', id);
        ingredienteSelect.find(`option[value="${id}"]`).prop('selected', false);
        ingredienteSelect.trigger('change');
    });

    //forzar actualización inicial
    setTimeout(() => {
        console.log('Initial Update');
        updateIngredientesTable();
        ingredienteSelect.trigger('change');
    }, 100);

    //previsualizacion de imagen
    const imagenInput = document.getElementById('imagen');
    const imagenPreview = document.getElementById('imagen-preview');
    imagenInput.addEventListener('input', function () {
        const url = imagenInput.value.trim();
        imagenPreview.src = url || 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';
    });

    //notificaciones de SweetAlert
    if (window.productoDuplicado) {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre duplicado',
            text: `Ya existe un producto con el nombre "${window.productoDuplicado}".`,
            confirmButtonColor: '#dc3545',
        });
    }

    if (window.productoCreado) {
        Swal.fire({
            icon: 'success',
            title: '¡Producto creado!',
            text: `El producto "${window.productoCreado}" se ha creado exitosamente.`,
            confirmButtonColor: '#198754',
        });
    }
});