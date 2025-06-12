<?php
// get_reservation_info.php

// Conexión a la base de datos
include '../logica/conectar.php'; 


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];

    // Prepara la consulta para actualizar el estado de la reserva a "transaccion"
    $sql = "UPDATE reserva SET estado = 'transaccion' WHERE id_reserva = ? AND id_cancha NOT IN (
        SELECT id_cancha FROM reserva WHERE estado = 'transaccion'
    )";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id_reserva);
        if ($stmt->execute()) {

        } else {
            echo "<script>alert('Error al realizar la transacción.'); window.location.href='../vistas/vista_reservas.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error en la consulta SQL.'); window.location.href='../vistas/vista_reservas.php';</script>";
    }
} else {
    echo "<script>alert('Solicitud inválida.'); window.location.href='../vistas/vista_reservas.php';</script>";
}






$reservation_found = false;
$direccion = '';
$telefono = '';
$nombre_completo_dueno = '';
$error_message = '';

if (!isset($_POST['id_reserva'])) {
    $error_message = "No se recibió el ID de la reserva.";
} else {
    $id_reserva = $_POST['id_reserva'];

    // Verificar conexión
    if ($conn->connect_error) {
        $error_message = "Conexión fallida: " . $conn->connect_error;
    } else {
        // Consulta SQL para obtener la dirección de la cancha, el teléfono y el nombre del dueño
        $sql = "
            SELECT c.direccion_cancha, p.telefono, p.primer_nombre , p.segundo_nombre, p.primer_apellido, p.segundo_apellido
            FROM reserva r
            INNER JOIN cancha c ON r.id_cancha = c.id_cancha
            INNER JOIN administra a ON c.id_cancha = a.cod_cancha
            INNER JOIN persona p ON a.cedula_propietario = p.cedula_persona
            WHERE r.id_reserva = ?
        ";

        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $id_reserva);
        $stmt->execute();
        $stmt->bind_result($direccion, $telefono, $nombre_p, $nombre_s, $apellido_p, $apellido_s);

        if ($stmt->fetch()) {
            $reservation_found = true;
            $nombre_completo_dueno = htmlspecialchars($nombre_p) . " " . htmlspecialchars($nombre_s) . " " . htmlspecialchars($apellido_p) . " " . htmlspecialchars($apellido_s);
            $direccion = htmlspecialchars($direccion);
            $telefono = htmlspecialchars($telefono);
        } else {
            $error_message = "No se encontraron datos para la reserva con ID: " . htmlspecialchars($id_reserva);
        }

        $stmt->close();
    }
    $conn->close();
}
?>