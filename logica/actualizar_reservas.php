<?php
include '../logica/conectar.php'; // Asegúrate de tener la conexión correctamente establecida

date_default_timezone_set('America/Bogota');
$fecha_actual = date('Y-m-d H:i:s');

try {
    // Consulta SQL con `?` en lugar de `:fecha_actual`
    $query = "UPDATE reserva 
              SET estado = 'caducada' 
              WHERE STR_TO_DATE(CONCAT(fecha_reserva, ' ', hora_final), '%Y-%m-%d %H:%i') < ? 
              AND LOWER(estado) NOT IN ('completada', 'cancelada', 'caducada', 'finalizada', 'calificado')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $fecha_actual); // `s` indica un string
    $stmt->execute();

    $filasAfectadas = $stmt->affected_rows;


    $query = "UPDATE reserva 
              SET estado = 'finalizada' 
              WHERE STR_TO_DATE(CONCAT(fecha_reserva, ' ', hora_final), '%Y-%m-%d %H:%i') < ? 
              AND LOWER(estado) NOT IN ( 'cancelada', 'caducada', 'finalizada', 'calificada')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $fecha_actual); // `s` indica un string
    $stmt->execute();

    $filasAfectadas = $stmt->affected_rows;




} catch (Exception $e) {
    // Manejo de errores en `mysqli`
    $response = [
        "status" => "error",
        "message" => "Error en la base de datos",
        "details" => [
            "error" => $e->getMessage(),
            "code" => $e->getCode()
        ]
    ];

    header('Content-Type: application/json');
    http_response_code(500); // Código de error del servidor
    echo json_encode($response);
    $conn->close();
}