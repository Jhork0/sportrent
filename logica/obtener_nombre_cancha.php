<?php
// obtener_nombre_cancha.php
function obtenerNombreCancha($conn, $id_cancha) {
    $query = "SELECT nombre_cancha FROM cancha WHERE id_cancha = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id_cancha);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_cancha'];
    }
    return "Cancha no disponible";
}