<?php
session_start(); // Asegura que la sesión está iniciada
include 'conectar.php';

if(isset($_SESSION['correo_usuario'])) {
    $correo_recibido = $_SESSION['correo_usuario'];
} else {
    echo "No se recibió ningún correo";
}

// Paso 1: Validar y sanitizar entradas
$nombre_cancha   = filter_var($_POST['nombre_cancha'] ?? '', FILTER_SANITIZE_STRING);
$tipo_cancha     = filter_var($_POST['categoria_cancha'] ?? '', FILTER_SANITIZE_STRING);
$descripcion     = filter_var($_POST['descripcion'] ?? '', FILTER_SANITIZE_STRING);
$valor_hora      = isset($_POST['valor_hora']) ? (int) $_POST['valor_hora'] : 0;
$hora_apertura   = filter_var($_POST['hora_apertura'] ?? '', FILTER_SANITIZE_STRING);
$hora_cierre     = filter_var($_POST['hora_cierre'] ?? '', FILTER_SANITIZE_STRING);
$direccion_cancha = filter_var($_POST['direccion_cancha'] ?? '', FILTER_SANITIZE_STRING);
$estado          = 'Disponible';

// Paso 2: Validar si se subió una imagen
$foto_binaria = null;
$tamano_maximo = 1048576; // 1MB

if (isset($_FILES['foto']) && is_uploaded_file($_FILES['foto']['tmp_name'])) {
    // Obtener el tamaño del archivo
    $tamano_archivo = $_FILES['foto']['size'];

    if ($tamano_archivo > $tamano_maximo) {
        echo "<script>alert('El tamaño de la imagen excede el límite de 1MB.'); window.history.back();</script>";
        exit();
    }

    $foto_binaria = file_get_contents($_FILES['foto']['tmp_name']);
} else {
    echo "<script>alert('Error al subir la imagen.'); window.history.back();</script>";
    exit();
}

// Paso 3: Obtener cédula del proveedor desde sesión
if (isset($_SESSION['cedula_usuario'])) {
    $cedula_proveedor = $_SESSION['cedula_usuario'];
} else {
    echo "<script>alert('No se encontró el proveedor.'); window.history.back();</script>";
    exit();
}


function generarUUIDv4IdCancha() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff), random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0x0fff) | 0x4000,
        random_int(0, 0x3fff) | 0x8000,
        random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
    );
}

// Ahora, generar el ID para la cancha
$id_cancha = 'cancha_' . generarUUIDv4IdCancha();




function generarUUIDv4IdAdminstra() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        random_int(0, 0xffff), random_int(0, 0xffff),
        random_int(0, 0xffff),
        random_int(0, 0x0fff) | 0x4000,
        random_int(0, 0x3fff) | 0x8000,
        random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
    );
}
$id_admin = generarUUIDv4IdAdminstra();


// Insertar en la tabla administra
$sqlAdministra = "INSERT INTO administra (id_admin, cedula_propietario, cod_cancha) VALUES (?, ?, ?)";
$stmtAdmin = $conn->prepare($sqlAdministra);
$stmtAdmin->bind_param("sss", $id_admin, $cedula_proveedor, $id_cancha);

if (!$stmtAdmin->execute()) {
    echo "<script>alert('Error al registrar la relación en administra: " . $stmtAdmin->error . "'); window.history.back();</script>";
    exit();
}

$stmtAdmin->close();






$sqlInsert = "INSERT INTO cancha 
    (id_cancha, nombre_cancha, tipo_cancha, descripcion, valor_hora, hora_apertura, hora_cierre, estado, foto, direccion_cancha) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmtInsert = $conn->prepare($sqlInsert);
$stmtInsert->bind_param(
    "ssssisssss",
    $id_cancha,
    $nombre_cancha,
    $tipo_cancha,
    $descripcion,
    $valor_hora,
    $hora_apertura,
    $hora_cierre,
    $estado,
    $foto_binaria,
    $direccion_cancha
);




// Ejecutar la consulta
if ($stmtInsert->execute()) {
    echo "<script>alert('Cancha registrada con éxito.'); window.location.href='../vistas/proveedor.php';</script>";
} else {
    echo "<script>alert('Error al registrar la cancha: " . $stmtInsert->error . "'); window.history.back();</script>";
}

// Cerrar conexiones
$stmtInsert->close();
$conn->close();
?>