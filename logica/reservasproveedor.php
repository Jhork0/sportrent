<?php
session_start();
include '../logica/conectar.php';

if (!isset($_SESSION['cedula_usuario'])) {
    die("<p>No tienes permisos para ver esta página.</p>");
}


if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'proveedor') {
    header("Location: ../index.php");
    exit(); // Importante para evitar que el script siga ejecutándose
}

$cedula_propietario = $_SESSION['cedula_usuario'];
$reservas = [];
$error_message = '';

// Obtener estados filtrados desde el formulario (GET)
$estados_filtrados = $_GET['estado'] ?? [];


$sql = "SELECT 
            r.id_reserva, 
            r.fecha_reserva, 
            r.hora_inicio, 
            r.hora_final, 
            r.estado, 
            r.cedula_persona, 
            a.cod_cancha, 
            c.puntuacion, 
            c.comentario, 
            can.nombre_cancha,
            p.primer_nombre,
            p.primer_apellido,
            COALESCE(u.promedio_usuario, 0) AS promedio_usuario_calificacion
        FROM reserva r
        JOIN administra a ON r.id_cancha = a.cod_cancha
        JOIN cancha can ON can.id_cancha = a.cod_cancha
        JOIN persona p ON p.cedula_persona = r.cedula_persona
        LEFT JOIN calificacion c ON r.id_reserva = c.id_reserva
        LEFT JOIN (
            SELECT res.cedula_persona, AVG(cal.puntuacion) AS promedio_usuario
            FROM calificacion cal
            JOIN reserva res ON cal.id_reserva = res.id_reserva
            GROUP BY res.cedula_persona
        ) u ON r.cedula_persona = u.cedula_persona
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
    // Dynamically bind parameters
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($fila = $result->fetch_assoc()) {
            // Format the average rating if it's not null
            if (!is_null($fila['promedio_usuario_calificacion'])) {
                $fila['promedio_usuario_calificacion_formateado'] = number_format($fila['promedio_usuario_calificacion'], 1) . " ⭐";
            } else {
                $fila['promedio_usuario_calificacion_formateado'] = "N/A"; // Or 0 ⭐ or whatever you prefer for no ratings
            }
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