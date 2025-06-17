<?php
include 'conectar.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_cancha = $_POST['id'];

    // Paso 1: Obtener IDs de reservas asociadas a esta cancha
    $stmt_reservas = $conn->prepare("SELECT id_reserva FROM reserva WHERE id_cancha = ?");
    $stmt_reservas->bind_param("s", $id_cancha);
    $stmt_reservas->execute();
    $result_reservas = $stmt_reservas->get_result();

    $ids_reservas = [];
    while ($row = $result_reservas->fetch_assoc()) {
        $ids_reservas[] = $row['id_reserva'];
    }
    $stmt_reservas->close();

    if (!empty($ids_reservas)) {
        // Paso 2: Buscar calificaciones asociadas a esas reservas
        $placeholders = implode(',', array_fill(0, count($ids_reservas), '?'));
        $types = str_repeat('s', count($ids_reservas));
        $stmt_califs = $conn->prepare("SELECT id_calificacion FROM calificacion WHERE id_reserva IN ($placeholders)");
        $stmt_califs->bind_param($types, ...$ids_reservas);
        $stmt_califs->execute();
        $result_califs = $stmt_califs->get_result();

        $ids_califs = [];
        while ($row = $result_califs->fetch_assoc()) {
            $ids_califs[] = $row['id_calificacion'];
        }
        $stmt_califs->close();

        if (!empty($ids_califs)) {
            // Paso 3: Eliminar de calfi_usuario
            $placeholders_cu = implode(',', array_fill(0, count($ids_califs), '?'));
            $types_cu = str_repeat('s', count($ids_califs));
            $stmt_cu = $conn->prepare("DELETE FROM calfi_usuario WHERE id_califi IN ($placeholders_cu)");
            $stmt_cu->bind_param($types_cu, ...$ids_califs);
            $stmt_cu->execute();
            $stmt_cu->close();
        }
    }

    // Paso 4: Eliminar de calif_cancha
    $stmt0 = $conn->prepare("DELETE FROM calif_cancha WHERE id_cancha = ?");
    $stmt0->bind_param("s", $id_cancha);
    $stmt0->execute();
    $stmt0->close();

    // Paso 5: Eliminar de administra
    $stmt1 = $conn->prepare("DELETE FROM administra WHERE cod_cancha = ?");
    $stmt1->bind_param("s", $id_cancha);
    $stmt1->execute();
    $stmt1->close();

    // Paso 6: Eliminar la cancha (reserva y calificacion se eliminan en cascada)
    $stmt3 = $conn->prepare("DELETE FROM cancha WHERE id_cancha = ?");
    $stmt3->bind_param("s", $id_cancha);

    if ($stmt3->execute()) {
        echo "✅ Cancha, reservas y calificaciones eliminadas correctamente.";
    } else {
        echo "❌ Error al eliminar la cancha.";
    }
    $stmt3->close();
}


?>
