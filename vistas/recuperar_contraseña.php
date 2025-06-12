<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../vendor/bootstrap/css/recuperar.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <div class="caja__informacion text-center mb-4">
                <h3>¿Has olvidado tu contraseña?</h3>
                <p>Escribe tu número de identificación registrado, enviaremos un código de confirmación</p>      
            </div>
            <!-- Formulario para enviar código -->
           
            <form id="formRecuperacion">
    <h5 class="mb-3 text-center">Recuperación de contraseña</h5>
    <input type="text" class="form-control" placeholder="Número de identificación" name="cedula" id="identificacion" required>
    <button type="submit" class="btn btn-primary w-100" id="btnEnviarCodigo">Enviar código</button>
    <div id="progresoEnvio" class="mt-2 hidden text-center">
        <div class="spinner-border text-primary" role="status"></div>
        <div class="mt-2" id="textoProgreso">Enviando código...</div>
    </div>
    <div id="timerEnvio" class="mt-2 text-center text-muted hidden"></div>
</form>

            <div id="mensaje" class="mensaje-exito hidden text-center"></div>

            <!-- Formulario para validar código -->
            <form id="formValidar" class="hidden">
                <h5 class="mb-3 text-center">Validar código</h5>
                <input type="text" class="form-control" placeholder="Código recibido" name="codigo" id="codigo" required>
                <button type="submit" class="btn btn-warning w-100">Validar código</button>
            </form>
            <div id="mensajeValidar" class="mensaje-error hidden text-center"></div>

            <!-- Formulario para cambiar contraseña -->
            <form id="formCambiar" class="hidden">
                <h5 class="mb-3 text-center">Establecer nueva contraseña</h5>
                <input type="password" class="form-control" placeholder="Nueva contraseña" name="nueva" id="nueva" required>
                <input type="password" class="form-control" placeholder="Repite la nueva contraseña" name="repite" id="repite" required>
                <button type="submit" class="btn btn-success w-100">Cambiar contraseña</button>
            </form>
            <div id="mensajeCambiar" class="mensaje-error hidden text-center"></div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/bootstrap/js/recuperar.js"></script>
</body>
</html>