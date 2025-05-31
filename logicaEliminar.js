

function eliminarCancha(idCancha, boton) {
    if (confirm("¿Estás seguro de que deseas eliminar esta cancha?")) {
        fetch('../logica/eliminar_cancha.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(idCancha)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            // Elimina visualmente la tarjeta si el backend respondió éxito
            const card = boton.closest('.col-lg-3');
            if (card) card.remove();
        })
        .catch(error => {
            alert("Error al eliminar: " + error);
        });
    }
}

