<?php
include 'conectar.php';
session_start();

// Validar sesión y tipo de usuario
if(!isset($_SESSION['correo_usuario']) || !isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'proveedor') {
    echo "No se recibió ningún correo";
    exit();
}

if ($_SESSION['tipo_usuario'] === 'cliente') {
     header("Location: ../index.php");
}

$correo = $_SESSION['correo_usuario'];
$cedula = $_SESSION['cedula_usuario'] ?? null;

// Consulta SQL con JOIN entre administra y cancha
$sql = "
    SELECT c.*
    FROM administra a
    INNER JOIN cancha c ON a.cod_cancha = c.id_cancha
    WHERE a.cedula_propietario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cedula);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<div class="container">
    <header class="jumbotron my-4">
        <h1 class="display-3">Bienvenido a SportRent</h1>
        <p class="lead">Administra tus canchas deportivas de forma segura.</p>
        <p class="text-muted">Sesión iniciada como: <?php echo htmlspecialchars($correo); ?></p>
    </header>

 

    <div class="row text-center">
        <?php 
        if($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) { 
                $nombre = htmlspecialchars($fila['nombre_cancha']);
                $tipo = htmlspecialchars($fila['tipo_cancha']);
                $valor = htmlspecialchars($fila['valor_hora']);
                $id = htmlspecialchars($fila['id_cancha']);
        ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100">
                    <?php if(!empty($fila['foto'])): ?>
                        <img class="card-img-top" src="data:image/jpeg;base64,<?php echo base64_encode($fila['foto']); ?>" alt="<?php echo $nombre; ?>">
                    <?php else: ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                            <span class="text-white">Sin imagen</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $nombre; ?></h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge badge-primary">$<?php echo $valor; ?>/hora</span>
                            <span class="badge badge-secondary"><?php echo $tipo; ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="#" class="btn btn-danger btn-sm mb-2" onclick="eliminarCancha('<?php echo $id; ?>', this)">Eliminar</a>
                        <a href="../vistas/interfazedicion.php?id=<?php echo $id; ?>" class="btn btn-warning btn-sm mb-2">Editar</a>
                        <a href="detalles_cancha.php?id=<?php echo $id; ?>" class="btn btn-info btn-sm mb-2">Detalles</a>
                    </div>
                </div>

               
            </div>
        <?php 
            }
        } else {
            echo '<div class="col-12"><div class="alert alert-info">No tienes canchas registradas aún.</div></div>';
        }
        ?>
    </div>
</div>

<script>
// Aquí podrías incluir tu función eliminarCancha() con confirmación
</script>

<?php
$stmt->close();
$conn->close();
?>
