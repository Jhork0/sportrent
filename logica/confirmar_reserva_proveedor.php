<?php
include '../logica/conectar.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];

    // Prepara la consulta para actualizar el estado de la reserva a "Cancelada"
    $sql = "UPDATE reserva SET estado = 'confirmada' WHERE id_reserva = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id_reserva);
        if ($stmt->execute()) {
            echo "<script>alert('Reserva confirmada exitosamente.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
        } else {
            echo "<script>alert('Error al confirmar la reserva.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error en la consulta SQL.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
    }

    $conn->close();
} else {
    echo "<script>alert('Solicitud inválida.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
}
?>