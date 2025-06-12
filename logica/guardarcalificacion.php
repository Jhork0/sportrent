<?php
include '../logica/conectar.php';
session_start();



$id_reserva = $_POST['id_reserva'] ?? null;
$puntuacion = $_POST['puntuacion'] ?? null;
$comentario = $_POST['comentario'] ?? '';


if (!$id_reserva || !$puntuacion) {
    die("Faltan datos obligatorios.");
}

// Generar ID único
$id_calificacion = uniqid('CAL_', true);

// Insertar calificación
$sql = "INSERT INTO calificacion (id_calificacion, puntuacion, comentario, id_reserva, fecha, cedula_calificador)
        VALUES (?, ?, ?, ?, CURDATE(), ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sisss", $id_calificacion, $puntuacion, $comentario, $id_reserva, $_SESSION['cedula_usuario'] );

if ($stmt->execute()) {
    // Redireccionar o mostrar mensaje
    header("Location: ../vistas/vistareservasproveedor.php?mensaje=calificacion_exitosa");
    exit;
} else {
    echo "Error al guardar calificación: " . $stmt->error;
}
