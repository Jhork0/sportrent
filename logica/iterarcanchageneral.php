<?php
session_start();
include 'conectar.php'; // Make sure this path is correct

if(isset($_SESSION['correo_usuario'])) {
    $correo_recibido = $_SESSION['correo_usuario'];
} else {
    // Handle cases where the user might not be logged in or session isn't set
    $correo_recibido = "Invitado";
}

// Initialize SQL query
$sql = "SELECT * FROM cancha";
$where_clauses = [];

// Check if filter types are sent via GET request (e.g., from AJAX)
if (isset($_GET['tipos_cancha']) && !empty($_GET['tipos_cancha'])) {
    $tipos_cancha = $_GET['tipos_cancha']; // This will be a comma-separated string
    $tipos_array = explode(',', $tipos_cancha); // Convert string to array

    // Sanitize and quote each type for the SQL query
    $sanitized_types = array_map(function($type) use ($conn) {
        return "'" . $conn->real_escape_string($type) . "'";
    }, $tipos_array);

    if (!empty($sanitized_types)) {
        $where_clauses[] = "tipo_cancha IN (" . implode(',', $sanitized_types) . ")";
    }
}

// Add WHERE clauses if any filters are applied
if (!empty($where_clauses)) {
    $sql .= " WHERE " . implode(' AND ', $where_clauses); // Use AND if you had multiple filter types
}

// For debugging, you can uncomment this line to see the generated query
// echo "SQL Query: " . $sql . "<br>";

$resultado = $conn->query($sql);

if (!$resultado) {
    // Handle SQL query error
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
                            <span class="badge badge-primary d-flex justify-content-between align-items-center ">$<?php echo htmlspecialchars($fila['valor_hora']); ?>/hora</span>
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
// Cierra la conexión
$conn->close();
?>