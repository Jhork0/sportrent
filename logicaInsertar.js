document.addEventListener("DOMContentLoaded", function () {
    const aperturaInput = document.getElementById("hora-apertura");
    const cierreInput = document.getElementById("hora-cierre");
    const alertContainer = document.getElementById("alert-container");
    const form = document.getElementById("cancha-form");

    function ajustarHora(input) {
        let horaIngresada = input.value;
        let match = horaIngresada.match(/^([0-9]{2}):([0-5][0-9])$/);

        if (match) {
            let horas = match[1];
            let minutos = match[2];

            if (minutos !== "00") {
                input.value = `${horas}:00`;
                mostrarAlerta(`Hora no permitida. Se ha ajustado automáticamente a ${horas}:00`, "warning");
            }
        }
    }

    aperturaInput.addEventListener("change", function () {
        ajustarHora(aperturaInput);
        validarOrdenHoras();
    });

    cierreInput.addEventListener("change", function () {
        ajustarHora(cierreInput);
        validarOrdenHoras();
    });

    function validarOrdenHoras() {
        const apertura = aperturaInput.value;
        const cierre = cierreInput.value;

        // Permitir 00:00 como valor válido para canchas 24h
        if (apertura === "00:00" && cierre === "00:00") {
            return; // Si es 24 horas, no validar restricciones
        }

        if (apertura && cierre && apertura >= cierre) {
            mostrarAlerta("La hora de apertura debe ser anterior a la de cierre. Se han reiniciado los valores.", "danger");
            aperturaInput.value = "";
            cierreInput.value = "";
        }
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        }

        // Validar horas antes de enviar el formulario
        const apertura = aperturaInput.value;
        const cierre = cierreInput.value;

        // Permitir canchas abiertas 24h
        if (apertura === "00:00" && cierre === "00:00") {
            form.submit();
            return;
        }

        if (!apertura || !cierre || apertura >= cierre) {
            mostrarAlerta("La hora de apertura debe ser anterior a la de cierre. Por favor corrige los valores.", "danger");
            return;
        }

        form.submit();
    });

    function mostrarAlerta(mensaje, tipo) {
        alertContainer.innerHTML = `<div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        setTimeout(() => {
            alertContainer.innerHTML = "";
        }, 3000);
    }
});