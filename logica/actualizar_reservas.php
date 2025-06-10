<?php
session_start();

date_default_timezone_set('America/Bogota');
$fecha_actual = date('Y-m-d H:i');

try {
    // Establecer conexión con PDO
    $pdo = new PDO("mysql:host=tu_host;dbname=tu_base_datos", "usuario", "contraseña", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Consulta para actualizar reservas caducadas
    $query = "UPDATE reserva 
              SET estado = 'caducada' 
              WHERE STR_TO_DATE(CONCAT(fecha_reserva, ' ', hora_final), '%Y-%m-%d %H:%i') < :fecha_actual 
              AND estado NOT IN ('completada', 'cancelada', 'rechazada', 'caducada')";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":fecha_actual", $fecha_actual);
    $stmt->execute();

    // Validar si se actualizaron filas
    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Reservas caducadas actualizadas correctamente."]);
    } else {
        echo json_encode(["status" => "warning", "message" => "No se encontraron reservas para actualizar."]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error al actualizar reservas: " . $e->getMessage()]);
}
?>