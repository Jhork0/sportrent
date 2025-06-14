<?php
include '../logica/conectar.php';


$correo_recibido = isset($_SESSION['correo_usuario']) ? $_SESSION['correo_usuario'] : 'Invitado';

// Construcción de la consulta SQL
$sql = "SELECT * FROM cancha";
$where_clauses = [];

// Filtro por tipo de cancha
if (isset($_GET['tipos_cancha']) && !empty($_GET['tipos_cancha'])) {
    $tipos_array = explode(',', $_GET['tipos_cancha']);
    $sanitized_types = array_map(function($type) use ($conn) {
        return "'" . $conn->real_escape_string($type) . "'";
    }, $tipos_array);
    
    if (!empty($sanitized_types)) {
        $where_clauses[] = "tipo_cancha IN (" . implode(',', $sanitized_types) . ")";
    }
}

// Filtro por precio mínimo
if (isset($_GET['precio_min']) && is_numeric($_GET['precio_min'])) {
    $precio_min = $conn->real_escape_string($_GET['precio_min']);
    $where_clauses[] = "valor_hora >= $precio_min";
}

// Filtro por precio máximo
if (isset($_GET['precio_max']) && is_numeric($_GET['precio_max'])) {
    $precio_max = $conn->real_escape_string($_GET['precio_max']);
    $where_clauses[] = "valor_hora <= $precio_max";
}

// Filtro por horario (mañana/tarde)
if (isset($_GET['horarios']) && !empty($_GET['horarios'])) {
    $horarios = explode(',', $_GET['horarios']);
    $horarioConditions = [];
    
    foreach ($horarios as $horario) {
        if ($horario === 'manana') {
            $horarioConditions[] = "hora_cierre <= '11:00:00'  AND hora_cierre != hora_apertura ";
        } elseif ($horario === 'tarde') {
            $horarioConditions[] = "hora_cierre <= '23:00:00' AND hora_cierre > '12:00:00'  ";
        }
    }
    
    if (!empty($horarioConditions)) {
        $where_clauses[] = "(" . implode(' OR ', $horarioConditions) . ")";
    }
}

// Filtro por nombre de búsqueda (si se proporciona)
if (isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))) {
    $busqueda = $conn->real_escape_string(trim($_GET['busqueda']));
    $where_clauses[] = "nombre_cancha LIKE '%$busqueda%'";
}

// Agregar cláusulas WHERE si hay filtros
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

$resultado = $conn->query($sql);
if (!$resultado) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit();
}

$promedio_recibido = "Sin calificaciones";

// Obtener promedios de calificación para todas las canchas
$promedios = [];
$sql_promedios = "
    SELECT r.id_cancha, AVG(c.puntuacion) AS promedio
    FROM calificacion c
    JOIN reserva r ON c.id_reserva = r.id_reserva
    GROUP BY r.id_cancha
";
$result_promedios = $conn->query($sql_promedios);
if ($result_promedios) {
    while ($row = $result_promedios->fetch_assoc()) {
        $promedios[$row['id_cancha']] = number_format($row['promedio'], 1) . " ⭐";
    }
}

// Obtener promedio de calificaciones para el usuario actual
if (isset($_SESSION['cedula_usuario'])) {
    $cedula_usuario = $_SESSION['cedula_usuario'];

    $sql = "
        SELECT AVG(c.puntuacion) AS promedio
        FROM calificacion c
        JOIN reserva r ON c.id_reserva = r.id_reserva
        WHERE r.cedula_persona = ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $cedula_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (!is_null($row['promedio'])) {
                $promedio_recibido = number_format($row['promedio'], 1) . " ⭐";
            }
        }
        $stmt->close();
    }
}
?>

<div class="container">
    <header class="jumbotron my-4">
        <h1 class="display-3">Bienvenido a SportRent</h1>
        <p class="lead">Arrienda de forma segura, fácil y rápido tus canchas deportivas.</p>
        <p class="text-muted">Sesión iniciada como: <?php echo htmlspecialchars($correo_recibido); ?></p>
        <p class="text-muted">Promedio general de calificaciones hacia ti: <?php echo $promedio_recibido; ?></p>
    </header>

    <div class="row text-center" id="canchas-container">
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $promedio_cancha = isset($promedios[$fila['id_cancha']]) ? $promedios[$fila['id_cancha']] : "Sin calificaciones";
        ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" src="data:image/jpeg;base64,<?php echo base64_encode($fila['foto']); ?>" alt="<?php echo htmlspecialchars($fila['nombre_cancha']); ?>">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo htmlspecialchars($fila['nombre_cancha']); ?></h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-primary">$<?php echo htmlspecialchars($fila['valor_hora']); ?>/hora</span>
                                <?php $horario_completo = $fila['hora_apertura'] . ' - ' . $fila['hora_cierre']; ?>
                                <span class="badge badge-secondary horario-cancha" data-hora-apertura="<?php echo htmlspecialchars($fila['hora_apertura']); ?>" data-hora-cierre="<?php echo htmlspecialchars($fila['hora_cierre']); ?>">
                                    <?php echo $horario_completo; ?>
                                </span>
                            </div>
                            <div class="mt-2">
                                <span class="badge badge-info">Calificación: <?php echo $promedio_cancha; ?></span>
                            </div>
                            <div class="mt-2">
                                <span class="badge badge-secondary"><?php echo htmlspecialchars($fila['tipo_cancha']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="plantilla.php?id=<?php echo htmlspecialchars($fila['id_cancha']); ?>" class="btn btn-success btn-block">Ver detalles</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><p>No se encontraron canchas con los filtros seleccionados.</p></div>";
        }
        ?>
    </div>
</div>

<?php
$conn->close();
?>