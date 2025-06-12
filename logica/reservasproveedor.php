<?php
session_start();
include '../logica/conectar.php';

if (!isset($_SESSION['cedula_usuario'])) {
    die("<p>No tienes permisos para ver esta página.</p>");
}

if ($_SESSION['tipo_usuario'] === 'cliente') {
     header("Location: ../index.php");
}

$cedula_propietario = $_SESSION['cedula_usuario'];
$reservas = [];
$error_message = '';

// Obtener estados filtrados desde el formulario (GET)
$estados_filtrados = $_GET['estado'] ?? [];

// Base SQL
$sql = "SELECT r.id_reserva, r.fecha_reserva, r.hora_inicio, r.hora_final, r.estado, r.cedula_persona, a.cod_cancha
        FROM reserva r
        JOIN administra a ON r.id_cancha = a.cod_cancha
        WHERE a.cedula_propietario = ?";

// Si hay filtros, agregamos condición
$params = [$cedula_propietario];
$tipos = "s";

if (!empty($estados_filtrados)) {
    $placeholders = implode(',', array_fill(0, count($estados_filtrados), '?'));
    $sql .= " AND r.estado IN ($placeholders)";
    $tipos .= str_repeat("s", count($estados_filtrados));
    $params = array_merge($params, $estados_filtrados);
}

$sql .= " ORDER BY r.fecha_reserva DESC";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($fila = $result->fetch_assoc()) {
            $reservas[] = $fila;
        }
    } else {
        $error_message = "No hay reservas con los filtros seleccionados.";
    }

    $stmt->close();
} else {
    $error_message = "Error al preparar la consulta: " . $conn->error;
}
?>
