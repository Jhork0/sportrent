<?php
// obtener_valor.php

include '../logica/conectar.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_reserva'])) {
    $id_reserva = $conn->real_escape_string($_POST['id_reserva']);

    // Consulta para obtener el valor de la reserva
    $sql = "SELECT c.valor_hora FROM reserva r JOIN cancha c ON r.id_cancha = c.id_cancha WHERE r.id_reserva = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $id_reserva);
        $stmt->execute();
        $stmt->bind_result($valor);
        
        if ($stmt->fetch()) {
            echo json_encode(["valor" => $valor]); // Devolver el valor en formato JSON
        } else {
            echo json_encode(["error" => "No se encontró la reserva"]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error en la consulta"]);
    }

    $conn->close();
} else {
    echo json_encode(["error" => "Solicitud inválida"]);
}
?>