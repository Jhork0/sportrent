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
?>

<div class="container">
    <header class="jumbotron my-4">
        <h1 class="display-3">Bienvenido a SportRent</h1>
        <p class="lead">Ingresa de forma segura, fácil y rápido tus canchas deportivas.</p>
        <p class="text-muted">Sesión iniciada como: <?php echo htmlspecialchars($correo_recibido); ?></p>
    </header>

    <div class="row text-center" id="canchas-container">
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
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
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="plantillaproveedor.php?id=<?php echo htmlspecialchars($fila['id_cancha']); ?>" class="btn btn-success btn-block">Ver detalles</a>
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
