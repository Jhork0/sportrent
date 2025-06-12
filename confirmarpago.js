function confirmarTransaccion(idReserva) {
    // Mostrar un loader o deshabilitar el botón aquí si es necesario
    
    fetch('../logica/obtener_valor.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id_reserva=' + encodeURIComponent(idReserva)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la red');
        }
        return response.json();
    })
    .then(data => {
        if (data.valor) {
            const confirmar = confirm(`¿Estás seguro de que deseas confirmar la transacción? Esto indica que has recibido el monto de: $${data.valor}`);
            
            if (confirmar) {
                return fetch('../logica/confirmar_pago_reserva.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_reserva=' + encodeURIComponent(idReserva)
                });
            } else {
                return Promise.reject('Transacción cancelada por el usuario');
            }
        } else {
            throw new Error(data.error || 'No se pudo obtener el monto');
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la red');
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            alert('Transacción confirmada con éxito');
            location.reload();
        } else {
            throw new Error(result.error || 'Error desconocido al confirmar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Ocurrió un error al procesar la transacción');
    })
    .finally(() => {
        // Aquí puedes habilitar nuevamente el botón o ocultar el loader
    });
}