// ../logicacalendario.js

// Lógica para establecer la fecha mínima en el input de fecha (se ejecuta al cargar el módulo)
document.addEventListener("DOMContentLoaded", function () {
    let fechaReserva = document.getElementById("fecha_reserva");
    if (fechaReserva) {
        let hoy = new Date().toISOString().split("T")[0];
        fechaReserva.setAttribute("min", hoy);
        fechaReserva.dispatchEvent(new Event('change'));
    } else {
        console.error("El elemento 'fecha_reserva' no se encontró en el DOM al cargar.");
    }
});


// Función que se encarga de la lógica de actualización de horarios
// y que será exportada.
// Función que se encarga de la lógica de actualización de horarios
// y que será exportada.
const cambiarhorarios = function (fechaInicial = null) {
    const fechaReservaInput = document.getElementById('fecha_reserva');
    const horariosContainer = document.getElementById('horarios-container');
    const idCanchaInput = document.querySelector('input[name="id_cancha"]');

    if (fechaReservaInput && horariosContainer && idCanchaInput) {
        // Si se proporciona una fecha inicial, establecerla en el input
        if (fechaInicial) {
            fechaReservaInput.value = fechaInicial;
        }

        // Función para cargar los horarios
        const cargarHorarios = () => {
            const fechaSeleccionada = fechaReservaInput.value;
            const idCancha = idCanchaInput.value;
            
            fetch('../logica/obtener_horarios.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `fecha_reserva=${fechaSeleccionada}&id_cancha=${idCancha}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('La respuesta de la red no fue correcta ' + response.statusText);
                }
                return response.text();
            })
            .then(html => {
                horariosContainer.innerHTML = html;
            })
            .catch(error => console.error('Error al obtener los horarios:', error));
        };

        // Cargar horarios inicialmente
        cargarHorarios();

        // Añadir el listener para futuros cambios de fecha
        fechaReservaInput.addEventListener('change', cargarHorarios);
    } else {
        console.error("No se encontraron los elementos DOM requeridos para la actualización dinámica de horarios.");
    }
};

export { cambiarhorarios };