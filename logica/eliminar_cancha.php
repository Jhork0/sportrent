<?php
include 'conectar.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id_cancha = $_POST['id'];

   // 0. Eliminar de calif_cancha
$stmt0 = $conn->prepare("DELETE FROM calif_cancha WHERE id_cancha = ?");
$stmt0->bind_param("s", $id_cancha);
$stmt0->execute();
$stmt0->close();

// 1. Eliminar de administra
$stmt1 = $conn->prepare("DELETE FROM administra WHERE cod_cancha = ?");
$stmt1->bind_param("s", $id_cancha);
$stmt1->execute();
$stmt1->close();

// 2. (Opcional) Eliminar reservas (si permites cascada)
$stmt2 = $conn->prepare("DELETE FROM reserva WHERE id_cancha = ?");
$stmt2->bind_param("s", $id_cancha);
$stmt2->execute();
$stmt2->close();

// 3. Finalmente eliminar la cancha
$stmt3 = $conn->prepare("DELETE FROM cancha WHERE id_cancha = ?");
$stmt3->bind_param("s", $id_cancha);

if ($stmt3->execute()) {
    echo "✅ Cancha y registros asociados eliminados correctamente.";
} else {
    echo "❌ Error al eliminar la cancha.";
}
$stmt3->close();

}



?>
