<?php
session_start();  // Esto debe ser lo PRIMERO en el archivo
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'proveedor') {
    header("Location: ../index.php");
    exit(); // Importante para evitar que el script siga ejecutándose
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registro de Cancha</title>

  <!-- Estilos -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/heroic-features.css">
</head>
<body>

<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h3>Registrar Cancha</h3>
      <p class="mb-0">Agrega la información de la cancha</p>
    </div>

    <div class="card-body">
      <!-- Área para mostrar notificaciones -->
      <div id="alert-container" class="mb-3"></div>
      
      <!-- Formulario unificado -->
      <form id="cancha-form" action="../logica/guardarcancha.php" method="post" enctype="multipart/form-data" novalidate>

        <div class="row">
          <!-- Columna izquierda -->
          <div class="col-md-6">
            <div class="form-group mb-3">
              <input type="text" class="form-control" id="nombre_cancha" name="nombre_cancha" placeholder="Nombre de la Cancha" required>
              <div class="invalid-feedback">Por favor ingresa el nombre de la cancha</div>
            </div>
          
            <div class="form-group mb-3">
              <label for="hora-apertura">Hora de apertura</label>
              <input type="time" class="form-control" id="hora-apertura" name="hora_apertura" required step="3600">
              <div class="invalid-feedback">Por favor selecciona la hora de apertura</div>
            </div>

            <div class="form-group mb-3">
              <label for="hora-cierre">Hora de cierre</label>
              <input type="time" class="form-control" id="hora-cierre" name="hora_cierre" required step="3600">
              <div class="invalid-feedback">Por favor selecciona la hora de cierre</div>
            </div>

            <div class="form-group mb-3">
              <label for="foto">Foto de la cancha</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg, image/jpg, image/gif" required>
              <div class="invalid-feedback">Por favor selecciona una imagen para la cancha</div>
            </div>
          </div>

          <!-- Columna derecha -->
          <div class="col-md-6">
            <div class="form-group mb-3">
              <input type="text" id="precio-hora" name="valor_hora" class="form-control" placeholder="Precio (por hora)" required inputmode="numeric" pattern="[0-9]+" title="Solo se permiten números enteros">
              <div class="invalid-feedback">Por favor ingresa un precio válido (solo números)</div>
            </div>

            <div class="form-group mb-3">
              <select class="form-select" id="categoria_cancha" name="categoria_cancha" required>
                <option value="" selected disabled>Selecciona el tipo de cancha</option>
                <option value="Fútbol">Fútbol</option>
                <option value="Béisbol">Béisbol</option>
                <option value="Pádel">Pádel</option>
                <option value="Tenis">Tenis</option>
                <option value="Baloncesto">Baloncesto</option>
                <option value="Otra">Otra</option>
              </select>
              <div class="invalid-feedback">Por favor selecciona una categoría</div>
            </div>

            <div class="form-group mb-3">
              <input type="text" class="form-control" id="direccion_cancha" name="direccion_cancha" placeholder="Dirección de la cancha" required>
              <div class="invalid-feedback">Por favor ingresa la dirección de la cancha</div>
            </div>

            <div class="form-group mb-3">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
              <div class="invalid-feedback">Por favor ingresa una descripción</div>
            </div>
          </div>
        </div>

        <!-- Botón de guardar -->
        <div class="text-center p-3">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts necesarios de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Tu JS personalizado -->
<script src="../logicaInsertar.js"></script>

</body>
</html>
