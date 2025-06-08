<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/forgot.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">
            <div class="caja__informacion">
                <h3>¿Has olvidado tu contraseña?</h3>
                <p>Escribe tu correo electrónico registrado, enviaremos un código de confirmación</p>      
            </div>

            <form action="../logica/enviarcodigo.php" method="POST" class="formulario__recuperacion">
            <h2>Recuperación de contraseña</h2>
            <input type="email" placeholder="Correo Electrónico" name="correoi" id="email" required>
            <button type="submit" onclick="">Enviar código</button>


            </form>
        </div>
    </main>

      <!-- <script src="../logicaInputrecuperar.js"></script> -->
</body>
</html>