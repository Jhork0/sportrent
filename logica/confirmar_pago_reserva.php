<?php
include '../logica/conectar.php';

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

if (!$conn) {
    echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
    exit;
}

if (!isset($_POST['id_reserva'])) {
    echo json_encode(['error' => 'ID de reserva no proporcionado.']);
    exit;
}

$idReserva = $_POST['id_reserva'];
$stmt = $conn->prepare("UPDATE reserva SET estado = 'completada' WHERE id_reserva = ?");
$stmt->bind_param("s", $idReserva);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Error al confirmar la transacción: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>