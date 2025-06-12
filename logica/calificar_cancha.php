<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_SESSION['cedula_usuario'])) {
    echo "Debes iniciar sesión para calificar.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $_SESSION['id_reserva'] = $_POST['id_reserva'];

    echo "<script>console.log('DEBUG: id_reserva recibido = " . $_POST['id_reserva'] . "');</script>";
    header("Location: ../vistas/calificar_cancha_cliente.php");
    exit;
} else {
    die("El ID de la reserva no llegó correctamente.");
}
?>

