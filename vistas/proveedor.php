<?php 
session_start();  // Esto debe ser lo PRIMERO en el archivo
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SportRent</title>
  <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/heroic-features.css">
  <link rel="stylesheet" href="../css/custom-checkbox.css"> 
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../vistas/proveedor.php">SportRent</a>

      <form class="form-inline my-2 my-lg-0" id="searchForm">
        <input class="form-control mr-sm-2" type="search" 
               placeholder="Buscar" aria-label="Search" name="nombre"
               id="inputSearch" autocomplete="off">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
      </form>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="../vistas/vercanchasproveedor.php">Ver mis canchas</a></li>
          <li class="nav-item"><a class="nav-link" href="../vistas/insertarcancha.php">Ver solicitudes</a></li>
          <li class="nav-item"><a class="nav-link" href="../vistas/insertarcancha.php">Ver reservas activas</a></li>
          <li class="nav-item"><a class="nav-link" href="../vistas/editar_datos_proveedor.php">Editar datos</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Contenedor principal para el contenido -->
  <div class="container mt-5 pt-4">
  
    <!-- Sección de filtros -->
    <div class="filtros-container mb-4">
      <div class="container  ">
        <label class="form-label ajuste">Filtrar por tipo de cancha:</label>
        <div class="d-flex flex-wrap">
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Fútbol" id="futbol">
            <label class="form-check-label custom-checkbox-label" for="futbol">Fútbol</label>
          </div>
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Béisbol" id="beisbol">
            <label class="form-check-label custom-checkbox-label" for="beisbol">Béisbol</label>
          </div>
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Pádel" id="padel">
            <label class="form-check-label custom-checkbox-label" for="padel">Pádel</label>
          </div>
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Tenis" id="tenis">
            <label class="form-check-label custom-checkbox-label" for="tenis">Tenis</label>
          </div>
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Baloncesto" id="baloncesto">
            <label class="form-check-label custom-checkbox-label" for="baloncesto">Baloncesto</label>
          </div>
          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="Otra" id="otra">
            <label class="form-check-label custom-checkbox-label" for="otra">Otra</label>
          </div>
        </div>
      </div>
    </div>

       <div class="filtros-container mb-4">
      <div class="container">
        <label class="form-label">Filtrar por horario de apertura:</label>
        <div class="d-flex flex-wrap">


          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="manana" id="horario-manana">
            <label class="form-check-label custom-checkbox-label" for="horario-manana">Mañana (6:00 - 12:00)</label>
          </div>


          <div class="form-check mx-2">
            <input type="checkbox" class="form-check-input filtro-cancha" value="noche" id="horario-noche">
            <label class="form-check-label custom-checkbox-label" for="horario-noche">Noche (18:00 - 23:59)</label>
          </div>



        </div>
      </div>
       </div>

      <div class="container">
        <label class="form-label">Filtrar por precio:</label>
        <div class="d-flex flex-wrap align-items-center">
          <div class="form-group mx-2">
            <input type="number" class="form-control filtro-precio" id="precio-min" placeholder="Precio mínimo">
          </div>
          <span class="mx-1">a</span>
          <div class="form-group mx-2">
            <input type="number" class="form-control filtro-precio" id="precio-max" placeholder="Precio máximo">
          </div>
        </div>
      </div>

    <!-- Aquí deberías incluir el contenido principal -->
    <?php include '../logica/iterarcanchageneral.php'; ?>

  </div> <!-- Cierre del contenedor principal -->

  <div id="footer"></div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../logicabuscador.js"></script>
</body>
</html>