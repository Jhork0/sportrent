<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Register </title>
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>

        <main>

            <div class="contenedor__todo">
                <div class="caja__trasera">
                    <div class="caja__trasera-login">
                        <h3>¿Ya tienes una cuenta?</h3>
                        <p>Inicia sesión para entrar en la página</p>
                        <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                    </div>
                    <div class="caja__trasera-register">
                        <h3>¿Aún no tienes una cuenta?</h3>
                        <p>Regístrate para que puedas iniciar sesión</p>
                        <button id="btn__registrarse">Regístrarse</button>
                    </div>
                </div>

                <!--Formulario de Login y registro-->
                <div class="contenedor__login-register">
                    <!--Login-->
                    <form action="./logica/iniciosesion.php" method="POST" class="formulario__login">
                        <h2>Iniciar Sesión</h2>
                        <input type="text" placeholder="Correo Electronico" name="correoi">
                        <input type="password" placeholder="Contraseña" name="passwordi">
                        <button>Entrar</button>
                        <a class="forgot" href="./vistas/recuperar_contraseña.php">¿Olvidaste tu contraseña?</a>
                    </form>

                    <!--Register-->
                    <form action="./logica/registrousuario.php" method="POST" class="formulario__register">
                        <h2>Regístrarse</h2>
                        <div class="escritos">
                            <input type="text" placeholder="Cedula" name="cedula_persona"  required>
                            <input type="text" placeholder="Nombre completo" name="nombre_completo"  required>
                            <input type="text" placeholder="Correo Electronico" name="correo" required value="<?php echo $_SESSION['correo'] ?? ''; ?>">
                            <input type="text" placeholder="Direccion" name="direccion">
                            <input type="text" placeholder="Telefono" name="telefono" required>
                            <input type="password" placeholder="Contraseña" name="password" required>
                        </div>
                        

                        

                        <h4 class="texto_seleccion">Seleccione su tipo de perfil</h4>
                        <div class="contenedor_radios">
                            <input type="radio" name="tipo" id="radios-cliente" value="cliente" required>
                            <label for="radios-cliente">Cliente</label>
                        
                            <input type="radio" name="tipo" id="radios-proveedor" value="proveedor" required>
                            <label for="radios-proveedor">Proveedor</label>
                        </div>
                        
                        
                        

                        <button type="submit">Regístrarse</button>
                    </form>
        

            </div>

        </main>

        <script src="./main.js"></script>
</body>
</html>