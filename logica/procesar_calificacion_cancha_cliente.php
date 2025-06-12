<?php
session_start();
include '../logica/conectar.php';

if (!isset($_SESSION['cedula_usuario'])) {
    die("Debes iniciar sesión para calificar.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['puntuacion'], $_POST['comentario'], $_POST['id_reserva'])) {
    die("Faltan datos del formulario.");
}

$idReserva = trim($_POST['id_reserva']);
$puntuacion = (int) $_POST['puntuacion'];
$comentario = htmlspecialchars($_POST['comentario']);
$cedula_calificador = $_SESSION['cedula_usuario'];
$fecha_actual = date("Y-m-d");

// Verify that the reservation belongs to the user and is not already 'calificado'
$checkReserva = $conn->prepare("SELECT id_reserva, estado FROM reserva WHERE id_reserva = ? AND cedula_persona = ?");
if (!$checkReserva) {
    die("Error en la verificación de la reserva: " . $conn->error);
}
$checkReserva->bind_param("ss", $idReserva, $cedula_calificador);
$checkReserva->execute();
$checkReserva->store_result();
$checkReserva->bind_result($reserva_id, $reserva_estado); // Bind the 'estado' column

if ($checkReserva->num_rows === 0) {
    die("❌ No tienes una reserva válida con ese ID o no te pertenece.");
}

$checkReserva->fetch(); // Fetch the result to get the 'estado'

if ($reserva_estado === 'calificado') {
    die("❌ Esta reserva ya ha sido calificada.");
}
$checkReserva->close();

$id_calificacion = substr(uniqid("CAL"), 0, 10); // Keeps the 10-character limit

$stmtCalificacion = $conn->prepare("INSERT INTO calificacion (id_calificacion, puntuacion, comentario, id_reserva, fecha, cedula_calificador)
                                     VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmtCalificacion) {
    die("❌ Error preparando la inserción de calificación: " . $conn->error);
}
$stmtCalificacion->bind_param("sissss", $id_calificacion, $puntuacion, $comentario, $idReserva, $fecha_actual, $cedula_calificador);

if ($stmtCalificacion->execute()) {
    // Now, update the 'estado' in the 'reserva' table
    $updateReserva = $conn->prepare("UPDATE reserva SET estado = 'calificado' WHERE id_reserva = ? AND estado != 'calificado'");
    if (!$updateReserva) {
        // Log the error but don't die, as the rating was successful
        error_log("Error preparando la actualización de la reserva: " . $conn->error);
    } else {
        $updateReserva->bind_param("s", $idReserva);
        if ($updateReserva->execute()) {
            echo "✅ ¡Calificación registrada y reserva actualizada exitosamente!";
        } else {
            // Log the error but don't die
            error_log("Error al actualizar el estado de la reserva: " . $updateReserva->error);
            echo "✅ ¡Calificación registrada exitosamente! Sin embargo, hubo un problema al actualizar el estado de la reserva.";
        }
        $updateReserva->close();
    }
    header("Location: ../vistas/vista_reservas.php");
} else {
    die("❌ Error al guardar la calificación: " . $stmtCalificacion->error);
}

$stmtCalificacion->close();
$conn->close();
?>