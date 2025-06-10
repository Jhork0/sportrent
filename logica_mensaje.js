import { cambiarhorarios } from './logicacalendario.js';

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formReserva");
    const toast = document.getElementById("toast");
    const fechaReservaInput = document.getElementById('fecha_reserva');

    // Obtener la fecha guardada o usar null para que use la fecha actual
    const ultimaFechaReserva = localStorage.getItem("ultimaFechaReserva");
    
    // Llamar a cambiarhorarios con la fecha guardada (o null si no existe)
    cambiarhorarios(ultimaFechaReserva);

    if (form && toast) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch("../logica/procesar_reserva.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(response => {
                if (response.includes("Reserva Confirmada")) {
                    toast.classList.remove("hidden");
                    toast.classList.add("opacity-100");

                    // Guardar la fecha seleccionada en localStorage
                    const fechaSeleccionada = fechaReservaInput.value;
                    localStorage.setItem("ultimaFechaReserva", fechaSeleccionada);

                    setTimeout(() => {
                        toast.classList.add("hidden");
                        toast.classList.remove("opacity-100");
                        window.location.reload();
                    }, 3000);
                } else {
                    alert("Error al registrar la reserva: " + response);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error al intentar reservar.");
            });
        });
    }
});