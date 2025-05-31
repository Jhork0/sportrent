<?php
include 'conectar.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_cancha = $_POST['id'];

    // Borra primero de administra
    $stmt1 = $conn->prepare("DELETE FROM administra WHERE cod_cancha = ?");
    $stmt1->bind_param("s", $id_cancha);
    $stmt1->execute();

    // Luego borra de cancha
    $stmt2 = $conn->prepare("DELETE FROM cancha WHERE id_cancha = ?");
    $stmt2->bind_param("s", $id_cancha);

    if ($stmt2->execute()) {
        echo "Cancha eliminada correctamente.";
    } else {
        echo "Error al eliminar la cancha.";
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();
}
?>
