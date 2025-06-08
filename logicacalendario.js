document.addEventListener("DOMContentLoaded", function () {
    let fechaReserva = document.getElementById("fecha_reserva");
    let hoy = new Date().toISOString().split("T")[0];
    fechaReserva.setAttribute("min", hoy);
});