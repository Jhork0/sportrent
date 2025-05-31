<?php
include '../logica/conectar.php';

// Depuración: Mostrar parámetros GET recibidos


if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de cancha no proporcionado.");
}

// No usamos intval() ya que el ID es VARCHAR
$id = $_GET['id']; 


$sql = "SELECT id_cancha, nombre_cancha, tipo_cancha, valor_hora, hora_apertura, hora_cierre, descripcion, estado, foto, direccion_cancha FROM cancha WHERE id_cancha = ?";
$stmt = $conn->prepare($sql);

// Depuración: Verificar si la preparación fue exitosa
if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Usamos "s" para string (VARCHAR)
$stmt->bind_param("s", $id); 

// Depuración: Verificar el binding
if (!$stmt->execute()) {
    die("Error al ejecutar la consulta: " . $stmt->error);
}

$result = $stmt->get_result();



if ($result->num_rows === 0) {
    // Depuración adicional: Verificar si existe con consulta directa
    $check_sql = "SELECT COUNT(*) as total FROM cancha WHERE id_cancha = '" . $conn->real_escape_string($id) . "'";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    die("Cancha no encontrada. (Total en BD con este ID: " . $check_row['total'] . ")");
}

$cancha = $result->fetch_assoc();

// Depuración: Mostrar datos obtenidos


// Convertimos la imagen BLOB en una fuente de imagen válida
if (!empty($cancha['foto'])) {
    $foto_base64 = base64_encode($cancha['foto']);
    $foto_url = "data:image/jpeg;base64," . $foto_base64;
} else {
    $foto_url = "../uploads/default.jpg";
}



?>