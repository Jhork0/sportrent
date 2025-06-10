<?php

include '../logica/conectar.php';
// 1. Verifica si se pasó el parámetro 'id'
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<h1>Error</h1><p><strong>Parámetro 'id' no obtenido.</strong></p>";
    exit;
}

$id_cancha = $_GET['id'];

// 2. Consulta a la base de datos para obtener los detalles de la cancha
$sql = "SELECT * FROM cancha WHERE id_cancha = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_cancha);
$stmt->execute();
$resultado = $stmt->get_result();

// 3. Verifica si se encontró la cancha
if ($resultado->num_rows === 0) {
    echo "<h1>Error</h1><p><strong>No se encontró la cancha con ID: " . htmlspecialchars($id_cancha) . "</strong></p>";
    exit;
}

$fila = $resultado->fetch_assoc(); // Trae los datos de la cancha
?>