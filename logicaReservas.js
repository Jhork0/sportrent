document.addEventListener("DOMContentLoaded", function() {
    fetch("../logica/obtener_reservas.php")
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let reservaContainer = document.getElementById("reservas-list");
            reservaContainer.innerHTML = "";
            data.reservas.forEach(reserva => {
                reservaContainer.innerHTML += `
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h2 class="text-2xl font-semibold">${reserva.id_reserva}</h2>
                        <p>Estado: ${reserva.estado}</p>
                        <button onclick="actualizarEstado()" class="bg-red-600 text-white px-4 py-2 rounded">Caducar</button>
                    </div>
                `;
            });
        } else {
            console.error(data.message);
        }
    });
});

function actualizarEstado() {
    fetch(`../logica/actualizar_reservas.php?id_reserva=res_684657564e1a5`)
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload(); // Recargar la página para ver los cambios
    });
}
