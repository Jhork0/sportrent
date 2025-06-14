<?php
include '../logica/conectar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cancha'])) {
    $id_cancha = $_POST['id_cancha'];

    // Obtener datos actuales
    $stmt = $conn->prepare("SELECT * FROM cancha WHERE id_cancha = ?");
    $stmt->bind_param("s", $id_cancha);
    $stmt->execute();
    $result = $stmt->get_result();
    $cancha_actual = $result->fetch_assoc();

    // Nuevos valores del formulario
    $nuevos = [
        'nombre_cancha'     => $_POST['nombre_cancha'],
        'tipo_cancha'       => $_POST['categoria_cancha'],
        'valor_hora'        => $_POST['valor_hora'],
        'hora_apertura'     => $_POST['hora_apertura'],
        'hora_cierre'       => $_POST['hora_cierre'],
        'descripcion'       => $_POST['descripcion'],
        'direccion_cancha'  => $_POST['direccion_cancha'],
    ];

    // Validar que el nuevo nombre no exista en otra cancha (solo si fue modificado)
    if (strtolower($nuevos['nombre_cancha']) !== strtolower($cancha_actual['nombre_cancha'])) {
        $sql_check = "SELECT COUNT(*) AS total FROM cancha WHERE LOWER(nombre_cancha) = LOWER(?) AND id_cancha != ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $nuevos['nombre_cancha'], $id_cancha);
        $stmt_check->execute();
        $resultado = $stmt_check->get_result();
        $fila = $resultado->fetch_assoc();

        if ($fila['total'] > 0) {
            echo "<script>alert('Ya existe otra cancha con ese nombre. Por favor, elige uno diferente.'); window.history.back();</script>";
            $stmt_check->close();
            exit();
        }
        $stmt_check->close();
    }

    // Comparar y construir UPDATE solo con los campos que cambiaron
    $campos_modificados = [];
    $valores = [];
    $tipos = '';

    foreach ($nuevos as $campo => $valor) {
        if ($valor != $cancha_actual[$campo]) {
            $campos_modificados[] = "$campo = ?";
            $valores[] = $valor;
            $tipos .= is_int($valor) ? 'i' : 's';
        }
    }

    // Manejo de la imagen como BLOB
    if (isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
        $tamano_maximo = 1048576; // 1MB
        if ($_FILES['foto']['size'] > $tamano_maximo) {
            echo "<script>alert('La imagen excede el tamaño máximo de 1MB.'); window.history.back();</script>";
            exit();
        }
        
        $foto_contenido = file_get_contents($_FILES['foto']['tmp_name']);
        $campos_modificados[] = "foto = ?";
        $valores[] = $foto_contenido;
        $tipos .= 'b'; // 'b' para BLOB
    }

 if (!empty($campos_modificados)) {
    $sql = "UPDATE cancha SET " . implode(", ", $campos_modificados) . " WHERE id_cancha = ?";
    $stmt = $conn->prepare($sql);
    $valores[] = $id_cancha;
    $tipos .= 's';

    // Vincular parámetros
    $stmt->bind_param($tipos, ...$valores);

    // Si hay una imagen, enviarla como datos largos
    if (isset($foto_contenido)) {
        $stmt->send_long_data(array_search($foto_contenido, $valores), $foto_contenido);
    }

    if ($stmt->execute()) {
        header("Location: ../vistas/vercanchasproveedor.php");
        exit();
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
} else {
    echo "No se detectaron cambios.";
}
}
?>