<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Cancha</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/heroic-features.css">
</head>
<body>

<?php include '../logica/editarcancha.php'; ?>

<div class="container mt-5">
  <div class="card">
    <div class="card-header bg-dark text-white">
      <h3>Editar Cancha</h3>
      <p class="mb-0">Edita la información de la cancha</p>
    </div>

    <div class="card-body">
      <div id="alert-container" class="mb-3"></div>

      <form id="cancha-form" action="../logica/edicion_cancha.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_cancha" value="<?= htmlspecialchars($cancha['id_cancha']) ?>">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-3">
              <input type="text" class="form-control" id="nombre_cancha" name="nombre_cancha" placeholder="Nombre de la Cancha" required value="<?= htmlspecialchars($cancha['nombre_cancha']) ?>">
            </div>
          
            <div class="form-group mb-3">
              <label for="hora-apertura">Hora de apertura</label>
              <input type="time" class="form-control" id="hora-apertura" name="hora_apertura" required value="<?= htmlspecialchars($cancha['hora_apertura']) ?>">
            </div>

            <div class="form-group mb-3">
              <label for="hora-cierre">Hora de cierre</label>
              <input type="time" class="form-control" id="hora-cierre" name="hora_cierre" required value="<?= htmlspecialchars($cancha['hora_cierre']) ?>">
            </div>

            <div class="form-group mb-3">
              <label for="foto">Foto de la cancha (deja vacío si no la vas a cambiar)</label>
              <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <p class="mt-2"><strong>Actual:</strong> <img src="<?= $foto_url ?>" alt="Foto actual" width="100"></p>            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group mb-3">
              <input type="text" id="precio-hora" name="valor_hora" class="form-control" placeholder="Precio (por hora)" required value="<?= htmlspecialchars($cancha['valor_hora']) ?>">
            </div>

            <div class="form-group mb-3">
              <select class="form-select form-select-sm" id="categoria_cancha" name="categoria_cancha" required>
                <option disabled>Selecciona el tipo de cancha</option>
                <?php
                $categorias = ['Fútbol', 'Béisbol', 'Pádel', 'Tenis', 'Baloncesto', 'Otra'];
                foreach ($categorias as $cat) {
                    $selected = ($cancha['tipo_cancha'] == $cat) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-group mb-3">
              <input type="text" class="form-control" id="direccion_cancha" name="direccion_cancha" placeholder="Dirección de la cancha" required value="<?= htmlspecialchars($cancha['direccion_cancha']) ?>">
            </div>

            <div class="form-group mb-3">
              <label for="descripcion">Descripción</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?= htmlspecialchars($cancha['descripcion']) ?></textarea>
            </div>
          </div>
        </div>

        <div class="text-center p-3">
          <button type="submit" class="btn btn-success">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../logicaInsertar.js"></script>


</body>
</html>