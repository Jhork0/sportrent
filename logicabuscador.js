$(document).ready(function() {
    // Function to load canchas based on selected filters
    function loadCanchas() {
        var selectedTypes = [];
        $('.filtro-cancha:checked').each(function() {
            selectedTypes.push($(this).val());
        });

        // Convert the array of selected types to a comma-separated string
        var typesString = selectedTypes.join(',');

        $.ajax({
            url: '../logica/iterarcanchageneral.php', // Path to your PHP file
            type: 'GET',
            data: { tipos_cancha: typesString }, // Send selected types as a GET parameter
            success: function(response) {
                // Update only the canchas container
                $('#canchas-container').html($(response).find('#canchas-container').html());
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: " + status + error);
                // Optionally display an error message to the user
                $('#canchas-container').html("<p>Error al cargar las canchas. Intenta de nuevo más tarde.</p>");
            }
        });
    }

    // Attach change event listener to all checkboxes with class 'filtro-cancha'
    $('.filtro-cancha').on('change', function() {
        loadCanchas(); // Reload canchas when a checkbox changes
    });

    // Initial load of canchas when the page loads (optional, if you want to apply default filters or just load all initially)
    // loadCanchas();
});