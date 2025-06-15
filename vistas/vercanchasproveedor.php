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

    <form class="form-inline my-2 my-lg-0" method="post" action="#">
      <input class="form-control mr-sm-2" type="search" placeholder="Buscar" name="nombre" autocomplete="off">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
    </form>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
      aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="../vistas/vercanchasproveedor.php">Ver mis canchas</a></li>
        <li class="nav-item"><a class="nav-link" href="../vistas/vistareservasproveedor.php">Ver reservas de mis canchas</a></li>
        <li class="nav-item"><a class="nav-link" href="../vistas/editar_datos_proveedor.php">Editar datos</a></li>
      </ul>
    </div>
  </div>
</nav>

<div id="header"></div>

<?php
include '../logica/iterarcanchasproveedor.php';
include '../logica/conectar.php';
$cedula = $_SESSION['cedula_usuario'] ?? null;

$ingresos = 0;
$cantidad_reservas = 0;
$promedio_calificacion = 0;
$ingresosPorFecha = [];
$reservasPorFecha = [];

if ($cedula) {
  // Ingresos totales
  $sql_ingresos = "
      SELECT SUM(c.valor_hora) AS total
      FROM reserva r
      JOIN cancha c ON r.id_cancha = c.id_cancha
      JOIN administra a ON c.id_cancha = a.cod_cancha
      WHERE a.cedula_propietario = ?
        AND r.estado IN ('finalizada', 'completada', 'calificado')
  ";
  $stmt1 = $conn->prepare($sql_ingresos);
  $stmt1->bind_param("s", $cedula);
  $stmt1->execute();
  $res1 = $stmt1->get_result();
  $ingresos = ($res1->fetch_assoc()['total']) ?? 0;
  $stmt1->close();

  // Reservas totales
  $sql_reservas = "
      SELECT COUNT(*) AS total
      FROM reserva r
      JOIN administra a ON r.id_cancha = a.cod_cancha
      WHERE a.cedula_propietario = ?
  ";
  $stmt2 = $conn->prepare($sql_reservas);
  $stmt2->bind_param("s", $cedula);
  $stmt2->execute();
  $res2 = $stmt2->get_result();
  $cantidad_reservas = ($res2->fetch_assoc()['total']) ?? 0;
  $stmt2->close();

  // Calificación promedio
  $sql_calif = "
      SELECT AVG(c.puntuacion) AS promedio
      FROM calificacion c
      JOIN reserva r ON c.id_reserva = r.id_reserva
      JOIN administra a ON r.id_cancha = a.cod_cancha
      WHERE a.cedula_propietario = ?
        AND c.cedula_calificador NOT IN (SELECT cedula_propietario FROM proveedor)
  ";
  $stmt3 = $conn->prepare($sql_calif);
  $stmt3->bind_param("s", $cedula);
  $stmt3->execute();
  $res3 = $stmt3->get_result();
  $promedio_calificacion = number_format(($res3->fetch_assoc()['promedio']) ?? 0, 1);
  $stmt3->close();

  // Ingresos por fecha
  $sql_ingresos_fecha = "
      SELECT r.fecha_reserva, SUM(c.valor_hora) AS total
      FROM reserva r
      JOIN cancha c ON r.id_cancha = c.id_cancha
      JOIN administra a ON c.id_cancha = a.cod_cancha
      WHERE a.cedula_propietario = ?
        AND r.estado IN ('finalizada', 'completada', 'calificado')
      GROUP BY r.fecha_reserva
      ORDER BY r.fecha_reserva
  ";
  $stmt4 = $conn->prepare($sql_ingresos_fecha);
  $stmt4->bind_param("s", $cedula);
  $stmt4->execute();
  $res4 = $stmt4->get_result();
  while ($row = $res4->fetch_assoc()) {
      $ingresosPorFecha[$row['fecha_reserva']] = $row['total'];
  }
  $stmt4->close();

  // Reservas por fecha
  $sql_reservas_fecha = "
      SELECT r.fecha_reserva, COUNT(*) AS total
      FROM reserva r
      JOIN administra a ON r.id_cancha = a.cod_cancha
      WHERE a.cedula_propietario = ?
      GROUP BY r.fecha_reserva
      ORDER BY r.fecha_reserva
  ";
  $stmt5 = $conn->prepare($sql_reservas_fecha);
  $stmt5->bind_param("s", $cedula);
  $stmt5->execute();
  $res5 = $stmt5->get_result();
  while ($row = $res5->fetch_assoc()) {
      $reservasPorFecha[$row['fecha_reserva']] = $row['total'];
  }
  $stmt5->close();
}

$estadisticas = [
  'ingresos' => $ingresos,
  'reservas' => $cantidad_reservas,
  'calificacion' => $promedio_calificacion,
  'ingresosPorFecha' => $ingresosPorFecha,
  'reservasPorFecha' => $reservasPorFecha
];
?>

<div class="container mt-5">
  <h2 class="text-center mb-4">Estadísticas Generales</h2>

  <?php if ($ingresos == 0 && $cantidad_reservas == 0 && $promedio_calificacion == 0): ?>
    <div class="alert alert-warning text-center" role="alert">
      Aún no tienes datos para mostrar en las estadísticas.
    </div>
  <?php else: ?>
    <div class="row">
      <div class="col-md-4 text-center mb-4">
        <h5>Ingresos por fecha ($)</h5>
        <canvas id="graficoIngresos" width="300" height="300"></canvas>
      </div>
      <div class="col-md-4 text-center mb-4">
        <h5>Solicitudes recibidas por fecha</h5>
        <canvas id="graficoReservas" width="300" height="300"></canvas>
      </div>
      <div class="col-md-4 text-center mb-4">
        <h5>Calificación promedio</h5>
        <canvas id="graficoCalificaciones" width="300" height="300"></canvas>
      </div>
    </div>
    <div class="row">
      <div class="col-12 text-center mt-4">
        <h6>Ingresos totales: $<?php echo number_format($ingresos, 2); ?></h6>
        <h6>Reservas totales: <?php echo $cantidad_reservas; ?></h6>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const estadisticas = <?php echo json_encode($estadisticas); ?>;

  // Gráfico de ingresos por fecha
  if (estadisticas.ingresosPorFecha && Object.keys(estadisticas.ingresosPorFecha).length > 0) {
    const labels = Object.keys(estadisticas.ingresosPorFecha);
    const data = Object.values(estadisticas.ingresosPorFecha);
    new Chart(document.getElementById('graficoIngresos'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Ingresos por fecha ($)',
          data: data,
          backgroundColor: '#4caf50'
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        responsive: true,
        scales: { 
          y: { beginAtZero: true },
          x: { title: { display: true, text: 'Fecha' } }
        }
      }
    });
  }

  // Gráfico de reservas por fecha
  if (estadisticas.reservasPorFecha && Object.keys(estadisticas.reservasPorFecha).length > 0) {
    const labels = Object.keys(estadisticas.reservasPorFecha);
    const data = Object.values(estadisticas.reservasPorFecha);
    new Chart(document.getElementById('graficoReservas'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Reservas por fecha',
          data: data,
          backgroundColor: '#2196f3'
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        responsive: true,
        scales: { 
          y: { beginAtZero: true },
          x: { title: { display: true, text: 'Fecha' } }
        }
      }
    });
  }

  // Gráfico de calificación promedio
  if (estadisticas.calificacion != 0) {
    new Chart(document.getElementById('graficoCalificaciones'), {
      type: 'bar',
      data: {
        labels: ['Calificación'],
        datasets: [{
          label: 'Promedio',
          data: [estadisticas.calificacion],
          backgroundColor: '#ffc107'
        }]
      },
      options: {
        plugins: { legend: { display: false } },
        responsive: true,
        scales: { y: { beginAtZero: true, max: 5 } }
      }
    });
  }
</script>

<div id="footer"></div>

<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../logicaEliminar.js"></script>

</body>
</html>