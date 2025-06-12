let cedulaActual = '';
let timerInterval, tiempoRestante = 60;

// Mostrar solo un formulario a la vez
function mostrarForm(id) {
    document.getElementById('formRecuperacion').classList.add('hidden');
    document.getElementById('formValidar').classList.add('hidden');
    document.getElementById('formCambiar').classList.add('hidden');
    document.getElementById(id).classList.remove('hidden');
}

// Timer para reenviar código
function iniciarTimerEnvio() {
    const btn = document.getElementById('btnEnviarCodigo');
    const timer = document.getElementById('timerEnvio');
    btn.disabled = true;
    tiempoRestante = 60;
    timer.classList.remove('hidden');
    timer.textContent = `Puedes reenviar el código en ${tiempoRestante}s`;

    timerInterval = setInterval(() => {
        tiempoRestante--;
        if (tiempoRestante > 0) {
            timer.textContent = `Puedes reenviar el código en ${tiempoRestante}s`;
        } else {
            clearInterval(timerInterval);
            timer.classList.add('hidden');
            btn.disabled = false;
            btn.textContent = "Enviar código";
        }
    }, 1000);
}

// Enviar código
document.getElementById('formRecuperacion').addEventListener('submit', function(e) {
    e.preventDefault();
    const cedula = document.getElementById('identificacion').value.trim();
    if (!cedula) return;
    const btn = document.getElementById('btnEnviarCodigo');
    const progreso = document.getElementById('progresoEnvio');
    btn.disabled = true;
    progreso.classList.remove('hidden');
    btn.textContent = "Enviando...";

    fetch('../logica/solicitar.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({cedula: cedula})
    })
    .then(res => res.json())
    .then(data => {
        progreso.classList.add('hidden');
        if(data.ok){
            document.getElementById('mensaje').textContent = 'Código enviado al correo.';
            document.getElementById('mensaje').classList.remove('hidden', 'mensaje-error');
            document.getElementById('mensaje').classList.add('mensaje-exito');
            cedulaActual = cedula;
            mostrarForm('formValidar');
            iniciarTimerEnvio();
        }else{
            alert(data.error || 'Error al enviar el código.');
            document.getElementById('mensaje').textContent = data.error || 'Error al enviar el código.';
            document.getElementById('mensaje').classList.remove('hidden', 'mensaje-exito');
            document.getElementById('mensaje').classList.add('mensaje-error');
            btn.disabled = false;
            btn.textContent = "Enviar código";
        }
    })
    .catch(err => {
        progreso.classList.add('hidden');
        document.getElementById('mensaje').textContent = 'Error de red o servidor.';
        document.getElementById('mensaje').classList.remove('hidden', 'mensaje-exito');
        document.getElementById('mensaje').classList.add('mensaje-error');
        btn.disabled = false;
        btn.textContent = "Enviar código";
    });
});

// Validar código
document.getElementById('formValidar').addEventListener('submit', function(e) {
    e.preventDefault();
    const codigo = document.getElementById('codigo').value.trim();
    const mensajeValidar = document.getElementById('mensajeValidar');
    mensajeValidar.classList.add('hidden');

    if(!codigo){
        mensajeValidar.textContent = 'Ingrese el código recibido.';
        mensajeValidar.classList.remove('hidden');
        return;
    }

    fetch('../logica/verificar.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            cedula: cedulaActual,
            codigo: codigo
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.ok){
            mensajeValidar.textContent = '¡Código válido! Ahora puedes cambiar tu contraseña.';
            mensajeValidar.classList.remove('mensaje-error');
            mensajeValidar.classList.add('mensaje-exito');
            mensajeValidar.classList.remove('hidden');
            document.getElementById('formCambiar').dataset.codigo = codigo;
            mostrarForm('formCambiar');
        }else{
            mensajeValidar.textContent = data.error || 'Código incorrecto o expirado.';
            mensajeValidar.classList.remove('mensaje-exito');
            mensajeValidar.classList.add('mensaje-error');
            mensajeValidar.classList.remove('hidden');
        }
    })
    .catch(err => {
        mensajeValidar.textContent = 'Error de red o servidor.';
        mensajeValidar.classList.remove('mensaje-exito');
        mensajeValidar.classList.add('mensaje-error');
        mensajeValidar.classList.remove('hidden');
    });
});

// Cambiar contraseña
document.getElementById('formCambiar').addEventListener('submit', function(e) {
    e.preventDefault();
    const nueva = document.getElementById('nueva').value;
    const repite = document.getElementById('repite').value;
    const codigo = document.getElementById('formCambiar').dataset.codigo;
    const mensajeCambiar = document.getElementById('mensajeCambiar');
    mensajeCambiar.classList.add('hidden');

    if(nueva !== repite){
        mensajeCambiar.textContent = 'Las contraseñas no coinciden.';
        mensajeCambiar.classList.remove('hidden');
        mensajeCambiar.classList.add('mensaje-error');
        mensajeCambiar.classList.remove('mensaje-exito');
        return;
    }
    if(!nueva){
        mensajeCambiar.textContent = 'Ingrese la nueva contraseña.';
        mensajeCambiar.classList.remove('hidden');
        mensajeCambiar.classList.add('mensaje-error');
        mensajeCambiar.classList.remove('mensaje-exito');
        return;
    }

    fetch('../logica/cambiar.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            cedula: cedulaActual,
            codigo: codigo,
            nueva: nueva
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.ok){
            mensajeCambiar.textContent = '¡Contraseña cambiada exitosamente! Redirigiendo...';
            mensajeCambiar.classList.remove('mensaje-error');
            mensajeCambiar.classList.add('mensaje-exito');
            mensajeCambiar.classList.remove('hidden');
            document.getElementById('formCambiar').reset();
            setTimeout(function(){
                window.location.href = "../index.php";
            }, 2000);
        }else{
            mensajeCambiar.textContent = data.error || 'Error al cambiar la contraseña.';
            mensajeCambiar.classList.add('mensaje-error');
            mensajeCambiar.classList.remove('mensaje-exito');
            mensajeCambiar.classList.remove('hidden');
        }
    })
    .catch(err => {
        mensajeCambiar.textContent = 'Error de red o servidor.';
        mensajeCambiar.classList.add('mensaje-error');
        mensajeCambiar.classList.remove('mensaje-exito');
        mensajeCambiar.classList.remove('hidden');
    });
});

mostrarForm('formRecuperacion');