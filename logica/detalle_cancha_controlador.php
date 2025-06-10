<?php
// detalle_cancha_controller.php (nuevo archivo)
include '../logica/conectar.php';
include '../logica/ruta.php';

// Validación y obtención de datos
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: error.php?mensaje=Parámetro id no obtenido');
    exit;
}

$id_cancha = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
$cancha = obtenerCanchaPorId($conn, $id_cancha);

if (!$cancha) {
    header("Location: error.php?mensaje=No se encontró la cancha con ID: $id_cancha");
    exit;
}

function obtenerCanchaPorId($conn, $id) {
    $sql = "SELECT * FROM cancha WHERE id_cancha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->num_rows > 0 ? $resultado->fetch_assoc() : null;
}
?>