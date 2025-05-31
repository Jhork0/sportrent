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

    // Verificar si se subió una nueva foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $nombreFoto = basename($_FILES['foto']['name']);
        $ruta = "../uploads/" . $nombreFoto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta);
        $nuevos['foto'] = $nombreFoto;
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

    if (!empty($campos_modificados)) {
        $sql = "UPDATE cancha SET " . implode(", ", $campos_modificados) . " WHERE id_cancha = ?";
        $stmt = $conn->prepare($sql);
        $valores[] = $id_cancha;
        $tipos .= 's';

        $stmt->bind_param($tipos, ...$valores);

        if ($stmt->execute()) {
             header("Location: ../vistas/vercanchasproveedor.php");
        } else {
            echo "Error al actualizar: " . $stmt->error;
        }
    } else {
        echo "No se detectaron cambios.";
    }
}
?>
