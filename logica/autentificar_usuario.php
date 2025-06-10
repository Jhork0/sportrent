
<?php

session_start();

$correolog = $_SESSION['cedula_usuario'] ?? null;
if (!$correolog) {
    echo "<p class='text-red-500 text-center mt-4'>Usuario no autenticado.</p>";
    exit();
}

$query = "SELECT * FROM reserva WHERE cedula_persona = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $correolog);
$stmt->execute();
$result = $stmt->get_result();

?>