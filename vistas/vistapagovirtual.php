<?php
include '../logica/conectar.php';
include '../logica/autentificar_usuario.php';


if (!$_SESSION['tipo_usuario'] === 'cliente') {
     header("Location: ../index.php");
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_reserva"])) {
    $id_reserva = intval($_POST["id_reserva"]);
    $_SESSION['pago_id_reserva'] = $id_reserva; // Guardamos temporalmente el ID
} else {
    // Si se accede sin datos POST válidos, redirigir
    header("Location: ../vistas/mis_reservas.php");
    exit;
}
?>

