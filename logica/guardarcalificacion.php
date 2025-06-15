<?php
// Mostrar todos los errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../logica/conectar.php';
session_start();

// Función para generar código aleatorio de letras y números
function generarCodigo($longitud = 6) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $longitud; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}

$id_reserva = $_POST['id_reserva'] ?? null;
$puntuacion = $_POST['puntuacion'] ?? null;
$comentario = $_POST['comentario'] ?? '';

if (!$id_reserva || !$puntuacion) {
    die("Faltan datos obligatorios.");
}

// Generar códigos únicos para calificación y alerta
$id_calificacion = "CAL_" . generarCodigo(6);
$codig_aler = "ALR_" . generarCodigo(6);

// Insertar en calificacion
$sql = "INSERT INTO calificacion (id_calificacion, puntuacion, comentario, id_reserva, fecha, cedula_calificador)
        VALUES (?, ?, ?, ?, CURDATE(), ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error al preparar statement calificacion: " . $conn->error);
}
$stmt->bind_param("sisss", $id_calificacion, $puntuacion, $comentario, $id_reserva, $_SESSION['cedula_usuario']);

if ($stmt->execute()) {
    // Obtener cedula_cliente de la tabla reserva
    $sql_reserva = "SELECT cedula_persona FROM reserva WHERE id_reserva = ?";
    $stmt_reserva = $conn->prepare($sql_reserva);
    if (!$stmt_reserva) {
        die("Error al preparar statement reserva: " . $conn->error);
    }
    $stmt_reserva->bind_param("s", $id_reserva);
    $stmt_reserva->execute();
    $stmt_reserva->bind_result($cedula_cliente);

    // OJO: Solo obtenemos el valor y CERRAMOS el statement antes de seguir
    if ($stmt_reserva->fetch()) {
        if (!$cedula_cliente) {
            $stmt_reserva->close();
            die("Error: La reserva no tiene cedula_persona.");
        }
        $stmt_reserva->close(); // Cierra aquí antes de hacer otra consulta

        // Insertar en calfi_usuario
        $sql_calfi = "INSERT INTO calfi_usuario (codig_aler, id_califi, cedula_cliente)
                      VALUES (?, ?, ?)";
        $stmt_calfi = $conn->prepare($sql_calfi);
        if (!$stmt_calfi) {
            die("Error al preparar statement calfi_usuario: " . $conn->error);
        }
        $stmt_calfi->bind_param("sss", $codig_aler, $id_calificacion, $cedula_cliente);

        if ($stmt_calfi->execute()) {
            $stmt_calfi->close();
            // Redireccionar o mostrar mensaje de éxito
            header("Location: ../vistas/vistareservasproveedor.php?mensaje=calificacion_exitosa");
            exit;
        } else {
            $stmt_calfi->close();
            die("Error al guardar en calfi_usuario: " . $stmt_calfi->error);
        }
    } else {
        $stmt_reserva->close();
        die("No se encontró la reserva para obtener la cédula del cliente.");
    }
} else {
    $stmt->close();
    die("Error al guardar calificación: " . $stmt->error);
}
$stmt->close();
$conn->close();
?>