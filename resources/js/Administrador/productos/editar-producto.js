document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Select2
    const categoriaSelect = $('#categoria_ids').select2({
        placeholder: 'Seleccione una o más categorías',
        allowClear: true,
        width: '100%'
    });

    const ingredienteSelect = $('#ingrediente_ids').select2({
        placeholder: 'Seleccione uno o más ingredientes',
        allowClear: true,
        width: '100%',
        tags: false // No permitir crear nuevos ingredientes
    });

    // Referencia a la tabla
    const ingredientesTable = $('#ingredientes-table tbody');

    // Función para actualizar la tabla
    function updateIngredientesTable() {
        const selectedValues = ingredienteSelect.val() || [];
        console.log('Selected Values:', selectedValues); // Depuración

        ingredientesTable.empty();

        if (selectedValues.length === 0) {
            ingredientesTable.append('<tr><td colspan="3" class="text-center">No hay ingredientes seleccionados</td></tr>');
            return;
        }

        selectedValues.forEach(function (id) {
            const option = ingredienteSelect.find(`option[value="${id}"]`);
            const nombre = option.length ? option.text() : 'Desconocido';
            const isChecked = (oldIngredientesObligatorios.includes(id.toString()) || existingObligatorios.includes(parseInt(id))) ? 'checked' : '';

            console.log('Adding:', {
                id,
                nombre,
                isChecked
            }); // Depuración

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

    // Ingredientes obligatorios desde old() o existentes
    // Reemplaza las líneas con @json por estas
    const oldIngredientesObligatorios = window.oldIngredientesObligatorios || [];
    const existingObligatorios = window.existingObligatorios || [];
    console.log('Old Obligatorios:', oldIngredientesObligatorios); // Depuración
    console.log('Existing Obligatorios:', existingObligatorios); // Depuración

    // Actualizar tabla al cambiar la selección
    ingredienteSelect.on('change', function () {
        console.log('Select2 Changed'); // Depuración
        updateIngredientesTable();
    });

    // Eliminar ingrediente al hacer clic en el botón
    ingredientesTable.on('click', '.remove-ingrediente', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');
        console.log('Removing:', id); // Depuración
        ingredienteSelect.find(`option[value="${id}"]`).prop('selected', false);
        ingredienteSelect.trigger('change');
    });

    // Forzar actualización inicial
    setTimeout(() => {
        console.log('Initial Update'); // Depuración
        updateIngredientesTable();
        ingredienteSelect.trigger('change');
    }, 100);

    // Previsualización de imagen
    const imagenInput = document.getElementById('imagen');
    const imagenPreview = document.getElementById('imagen-preview');
    imagenInput.addEventListener('input', function () {
        const url = imagenInput.value.trim();
        imagenPreview.src = url || 'https://cdn-icons-png.flaticon.com/512/10446/10446694.png';
    });
});