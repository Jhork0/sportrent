<?php
include '../logica/conectar.php';

session_start();
if (!isset($_SESSION['cedula_usuario'])) {
    die("Error: Usuario no autenticado.");
}
$cedula_persona = $_SESSION['cedula_usuario'];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_cancha = $_POST["id_cancha"];
    $fecha_reserva = $_POST["fecha_reserva"];
    $horario = $_POST["horario"]; // Recibimos el valor "hora_inicio - hora_final"
    
    // Separar la hora de inicio y fin
    list($hora_inicio, $hora_final) = explode(" - ", $horario);

    // Generar un ID único para la reserva
    $id_reserva = uniqid("res_");

    // Estado inicial de la reserva
    $estado = "pendiente";

    // Asumiendo que la cédula del usuario está en sesión
    $cedula_persona = $_SESSION["cedula_usuario"] ?? "desconocida";


    $mensaje = "Esto es un mensaje desde PHP";
    echo "<script>console.log('PHP dice: " . addslashes($mensaje) .  " + " . addslashes($cedula_persona) ." ');</script>";




    // Insertar en la base de datos
    $sql = "INSERT INTO reserva (id_reserva, fecha_reserva, hora_inicio, hora_final, estado, cedula_persona, id_cancha) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $id_reserva, $fecha_reserva, $hora_inicio, $hora_final, $estado, $cedula_persona, $id_cancha);

    if ($stmt->execute()) {
        echo "<h1>Reserva Confirmada</h1><p>Tu reserva ha sido guardada exitosamente, y proxima a confirmacion por parte del proveedor de la cancha.</p>";
    } else {
        echo "<h1>Error</h1><p>No se pudo registrar la reserva. Inténtalo nuevamente.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>