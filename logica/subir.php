<?php
include 'conectar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se subió correctamente la imagen
    if (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
        die("Error al subir la imagen.");
    }

    // Obtener la imagen en formato binario
    $imagen = file_get_contents($_FILES["foto"]["tmp_name"]);

    // Preparar la consulta SQL para actualizar todas las filas con "cancha ejemplo"
    $sql = "UPDATE cancha SET foto = ? WHERE nombre_cancha = 'cancha ejemplo'";
    $stmt = $conn->prepare($sql);
    
    // Cambiar "b" por "s" (string) para datos binarios
    $stmt->bind_param("s", $imagen);

    if ($stmt->execute()) {
        echo "Imagen subida y guardada en la base de datos correctamente.";
    } else {
        echo "Error al guardar la imagen: " . $stmt->error;
    }

    // Cerrar conexión
    $stmt->close();
    $conn->close();
}