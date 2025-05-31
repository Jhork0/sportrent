


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
    
</head>
<body>

	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
	   <div class="container">
	     <a class="navbar-brand" href="../vistas/proveedor.php">SportRent</a>

	     <form class="form-inline my-2 my-lg-0" method="post"
	     action="#">
	     <input class="form-control mr-sm-2" type="search"
	     placeholder="Buscar" aria-label="Search" name="nombre"
	     autocomplete="off">
	     <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
	   </form>


	   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
	     <span class="navbar-toggler-icon"></span>
	   </button>
	   <div class="collapse navbar-collapse" id="navbarResponsive">
	     <ul class="navbar-nav ml-auto">
	       <li class="nav-item"><a class="nav-link"
	         href="../vistas/insertarcancha.php">Ingresar cancha</a></li>
           	       <li class="nav-item"><a class="nav-link"
	         href="../vistas/vercanchasproveedor.php">Ver mis canchas</a></li>
            <li class="nav-item"><a class="nav-link"
	         href="../vistas/insertarcancha.php">Ver solicitudes</a></li>
            <li class="nav-item"><a class="nav-link"
	         href="../vistas/insertarcancha.php">Ver reservas activas</a></li>
	         </ul>
           
	       </div>
	     </div>
	   </nav>

  <!-- Header -->
  <div id="header"></div>

<?php
include '../logica/iterarcanchasproveedor.php';
?>

  <!-- /.container -->

  <!-- Footer -->
  <div id="footer"></div>

  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../logicaEliminar.js"></script>


</body>
</html>