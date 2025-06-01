function loadCanchas() {
    var selectedTypes = [];
    $('.filtro-cancha:checked').each(function() {
        selectedTypes.push($(this).val());
    });

    var selectedHorarios = [];
    $('.filtro-horario:checked').each(function() {
        selectedHorarios.push($(this).val());
    });

    var precioMin = $('#precio-min').val();
    var precioMax = $('#precio-max').val();

    var busqueda = $('#inputSearch').val(); // corregido aquí

    $.ajax({
        url: '../logica/iterarcanchageneral.php',
        type: 'GET',
        data: {
            tipos_cancha: selectedTypes.join(','),
            horarios: selectedHorarios.join(','),
            precio_min: precioMin,
            precio_max: precioMax,
            busqueda: busqueda
        },
        success: function(response) {
            $('#canchas-container').html($(response).find('#canchas-container').html());
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + error);
            $('#canchas-container').html("<p>Error al cargar las canchas.</p>");
        }
    });
}

// Ejecutar al cambiar cualquier filtro o al escribir en el buscador
$('.filtro-cancha, .filtro-horario, #precio-min, #precio-max, #inputSearch').on('input change', function() {
    loadCanchas();
});
