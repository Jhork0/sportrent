<?php

session_start();
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
      <a class="navbar-brand" href="../vistas/home.php">SportRent</a>

      <form class="form-inline my-2 my-lg-0" id="searchForm">
        <input class="form-control mr-sm-2" type="search" 
               placeholder="Buscar" aria-label="Search" name="nombre"
               id="inputSearch" autocomplete="off">

      </form>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="../vistas/editar_datos_cliente.php">Editar mis datos</a></li>
          <li class="nav-item"><a class="nav-link" href="../vistas/vista_reservas.php">Ver mis reservas</a></li>
          <li class="nav-item"><a class="nav-link" href="../logica/cerrar_sesion.php">Cerrar sesion</a></li>
        </ul>
      </div>
    </div>
  </nav>

   <div class="container mt-3 pt-3">
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

<div class="container ">
  <label class="form-label ">Filtrar por precio :</label>
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

<div class="container">
  <label class="form-label">Filtrar por horario:</label>
  <div class="d-flex flex-wrap">
    <div class="form-check mx-2">
      <input type="checkbox" class="form-check-input filtro-horario" value="manana" id="manana">
      <label class="form-check-label custom-checkbox-label" for="manana">Mañana (hasta las 11:00 AM)</label>
    </div>
    <div class="form-check mx-2">
      <input type="checkbox" class="form-check-input filtro-horario" value="tarde" id="tarde">
      <label class="form-check-label custom-checkbox-label" for="tarde">Tarde (hasta las 11:00 PM)</label>
    </div>
  </div>
</div>

  <!-- Header -->
  <div id="header"></div>


<?php
include '../logica/iterarcanchageneral.php';
?>
  <!-- Footer -->
  <div id="footer"></div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../logicabuscadorcliente.js"></script>

</body>
</html>