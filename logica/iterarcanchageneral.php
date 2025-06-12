<?php

include '../logica/conectar.php';

if (isset($_SESSION['correo_usuario'])) {
    $correo_recibido = $_SESSION['correo_usuario'];
}



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

// Filtro por horario (mañana o noche)
if (isset($_GET['horarios']) && !empty($_GET['horarios'])) {
    $horarios = explode(',', $_GET['horarios']);
    $horario_conditions = [];

    foreach ($horarios as $horario) {
        $horario = $conn->real_escape_string($horario);
        if ($horario === 'manana') {
            $horario_conditions[] = "(hora_apertura <= '12:00' AND hora_cierre >= '06:00')";
        } elseif ($horario === 'noche') {
            $horario_conditions[] = "(hora_apertura <= '23:59' AND hora_cierre >= '18:00')";
        }
    }

    if (!empty($horario_conditions)) {
        $where_clauses[] = '(' . implode(' OR ', $horario_conditions) . ')';
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

// Puedes descomentar esta línea para depuración:
// echo "SQL Query: " . $sql;

$resultado = $conn->query($sql);
if (!$resultado) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit();
}

$promedio_recibido = "Sin calificaciones";

if (isset($_SESSION['cedula_usuario'])) {
    $cedula_usuario = $_SESSION['cedula_usuario'];

    $sql = "
        SELECT AVG(c.puntuacion) AS promedio
        FROM calificacion c
        JOIN reserva r ON c.id_reserva = r.id_reserva
        WHERE r.cedula_persona = ?
    ";

    $stmt = $conn->prepare($sql);
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


$comentarios = [];

$sql_comentarios = "
    SELECT c.comentario, c.puntuacion, c.fecha
    FROM calificacion c
    JOIN reserva r ON c.id_reserva = r.id_reserva
    WHERE r.id_cancha = ?
    ORDER BY c.fecha DESC
    LIMIT 3
";

$stmt_com = $conn->prepare($sql_comentarios);
$stmt_com->bind_param("s", $id_cancha);
$stmt_com->execute();
$result_com = $stmt_com->get_result();

while ($comentario_row = $result_com->fetch_assoc()) {
    $comentarios[] = $comentario_row;
}

$stmt_com->close();




?>

<div class="container">
    <header class="jumbotron my-4">
        <h1 class="display-3">Bienvenido a SportRent</h1>
        <p class="lead">Ingresa de forma segura, fácil y rápido tus canchas deportivas.</p>
        <p class="text-muted">Sesión iniciada como: <?php echo htmlspecialchars($correo_recibido); ?></p>
        <p class="text-muted">Promedio general de calificaciones hacia ti: <?php echo $promedio_recibido; ?></p>


        
    </header>

<div class="row text-center" id="canchas-container">
    <?php
    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            // Cálculo del promedio para cada cancha
            $id_cancha = $fila['id_cancha'];
            $promedio_cancha = "Sin calificaciones";
            
            $sql_prom = "
                SELECT AVG(c.puntuacion) AS promedio
                FROM calificacion c
                JOIN reserva r ON c.id_reserva = r.id_reserva
                WHERE r.id_cancha = ?
            ";
            
            $stmt = $conn->prepare($sql_prom);
            $stmt->bind_param("s", $id_cancha);
            $stmt->execute();
            $result_prom = $stmt->get_result();
            
            if ($row = $result_prom->fetch_assoc()) {
                if (!is_null($row['promedio'])) {
                    $promedio_cancha = number_format($row['promedio'], 1) . " ⭐";
                }
            }
            
            $stmt->close();
    ?>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <img class="card-img-top" src="data:image/jpeg;base64,<?php echo base64_encode($fila['foto']); ?>" alt="<?php echo htmlspecialchars($fila['nombre_cancha']); ?>">
                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($fila['nombre_cancha']); ?></h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge badge-primary">$<?php echo htmlspecialchars($fila['valor_hora']); ?>/hora</span>
                        <span class="badge badge-secondary"><?php echo htmlspecialchars($fila['tipo_cancha']); ?></span>
                    </div>
                    <div class="mt-2">
                        <span class="badge badge-info">Calificación: <?php echo $promedio_cancha; ?></span>
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
