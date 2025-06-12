function loadCanchas() {
    // Obtener tipos de cancha seleccionados
    var selectedTypes = [];
    $('.filtro-cancha:checked').each(function() {
        selectedTypes.push($(this).val());
    });

    // Obtener horarios seleccionados
    var selectedHorarios = [];
    $('.filtro-horario:checked').each(function() {
        selectedHorarios.push($(this).val());
    });

    // Obtener valores de los filtros
    var precioMin = $('#precio-min').val() 
    var precioMax = $('#precio-max').val() // Valor alto para representar "sin máximo"
    
    var busqueda = $('#inputSearch').val().toLowerCase();

    $.ajax({
        url: '../logica/iterarcanchageneral.php',
        type: 'GET',
        data: {
            tipos_cancha: selectedTypes.join(','),
            precio_min: precioMin,
            precio_max: precioMax,
            busqueda: busqueda,
            horarios: selectedHorarios.join(',') // Enviamos los horarios seleccionados
        },
        beforeSend: function() {
            $('#canchas-container').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Cargando...</span></div></div>');
        },
        success: function(response) {
            $('#canchas-container').html($(response).find('#canchas-container').html());
            
            // Mostrar mensaje si no hay resultados
            if ($('#canchas-container').children().length === 0) {
                $('#canchas-container').html('<p class="text-muted">No se encontraron canchas con los filtros aplicados</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error: " + status + " - " + error);
            $('#canchas-container').html("<p class='text-danger'>Error al cargar las canchas. Por favor, inténtelo de nuevo.</p>");
        }
    });
}

// Event listeners para todos los filtros
$('.filtro-cancha, .filtro-horario').on('change', loadCanchas);
$('#precio-min, #precio-max').on('change', loadCanchas);

// Debounce para el campo de búsqueda
var debounceTimer;
$('#inputSearch').on('input', function() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadCanchas, 300);
});

// Cargar canchas al iniciar la página
$(document).ready(function() {
    loadCanchas();
});