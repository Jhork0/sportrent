<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

 
        <div class="caja__principal">
            <div>
                <h3>Editar Perfil</h3>
                <p>Modifica tu información personal</p>
            </div>
        </div>

            <div class="marco_perfil">
            <form id="profile-form" class="formulario__login">
                <div class="profile-header">
                    <label for="profile-pic">
                        <img id="preview-img" src="/assets/images/default.jpg" alt="Foto de Perfil">
                    </label>
                    <input type="file" id="profile-pic" accept="image/*" hidden>
                </div>
                
                <div class="contenedor_inputs">
                    <div class="input_edit_usuario_1">
                      <input type="text" placeholder="Primer Nombre" required>
                      <input type="text" placeholder="Segundo Nombre">
                      <input type="text" placeholder="Primer Apellido" required>
                      <input type="text" placeholder="Segundo Apellido">
                    </div>
                    <div class="input_edit_usuario_2">
                      <input type="text" placeholder="Cédula" required>
                      <input type="email" placeholder="Correo Electrónico" required>
                      <input type="tel" placeholder="Teléfono" required>
                     
                    </div>
                    
                  </div>
                  <button type="submit" class="boton_editar_perfil">Guardar</button>
            </div>
        
                
              

            </form>

            
     
   

    <script src="script.js"></script>
</body>
</html>