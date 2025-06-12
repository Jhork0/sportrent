document.addEventListener("DOMContentLoaded", function () {
    const fechaReserva = document.getElementById("fecha_reserva");
    if (fechaReserva) {
        const hoy = new Date().toISOString().split("T")[0];
        fechaReserva.setAttribute("min", hoy);
        // No es necesario disparar el evento change aquí
    } else {
        console.error("El elemento 'fecha_reserva' no se encontró en el DOM al cargar.");
    }
});

const cambiarhorarios = (function() {
    let eventListenerAdded = false;
    
    return function(fechaInicial = null) {
        const fechaReservaInput = document.getElementById('fecha_reserva');
        const horariosContainer = document.getElementById('horarios-container');
        const idCanchaInput = document.querySelector('input[name="id_cancha"]');

        if (!fechaReservaInput || !horariosContainer || !idCanchaInput) {
            console.error("Elementos DOM requeridos no encontrados");
            return;
        }

        if (fechaInicial) {
            fechaReservaInput.value = fechaInicial;
        }

// Modificar la función cargarHorarios para enviar la hora actual
const cargarHorarios = () => {
    const fechaSeleccionada = fechaReservaInput.value;
    const idCancha = idCanchaInput.value;
    const ahora = new Date();
    const horaActual = ahora.getHours().toString().padStart(2, '0') + ':' + ahora.getMinutes().toString().padStart(2, '0');
    
    fetch('../logica/obtener_horarios.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `fecha_reserva=${encodeURIComponent(fechaSeleccionada)}&id_cancha=${encodeURIComponent(idCancha)}&hora_actual=${encodeURIComponent(horaActual)}`
    })
    .then(response => {
        if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
        return response.text();
    })
    .then(html => {
        horariosContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Error al obtener horarios:', error);
        horariosContainer.innerHTML = '<p class="text-red-500">Error al cargar horarios</p>';
    });
};

        // Cargar horarios inicialmente
        cargarHorarios();

        // Añadir listener solo si no se ha añadido antes
        if (!eventListenerAdded) {
            fechaReservaInput.addEventListener('change', cargarHorarios);
            eventListenerAdded = true;
        }
    };
})();

export { cambiarhorarios };