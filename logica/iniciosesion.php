
<?php
session_start();
include 'conectar.php';

// Sanitizar entrada
$correo = filter_var($_POST['correoi'] ?? '', FILTER_SANITIZE_EMAIL);
$password = trim($_POST['passwordi'] ?? '');

// Validar que no esté vacío
if (empty($correo) || empty($password)) {
    echo "<script>alert('Debe ingresar su correo y contraseña.'); window.history.back();</script>";
    exit();
}

// Paso 1: Obtener cédula asociada al correo
$sqlCedula = "SELECT cedula_persona FROM persona WHERE correo = ?";
$stmtCedula = $conn->prepare($sqlCedula);
$stmtCedula->bind_param("s", $correo);
$stmtCedula->execute();
$stmtCedula->store_result();

if ($stmtCedula->num_rows > 0) {
    $stmtCedula->bind_result($cedula);
    $stmtCedula->fetch();
    $_SESSION['cedula_usuario'] = $cedula;

    // Paso 2: Verificar tipo de usuario
    $sqlUsuario = "SELECT id_credencial FROM usuario WHERE cedula_persona = ?";
    $stmtUsuario = $conn->prepare($sqlUsuario);
    $stmtUsuario->bind_param("s", $cedula);
    $stmtUsuario->execute();
    $stmtUsuario->store_result();

    $sqlProveedor = "SELECT id_credencial FROM proveedor WHERE cedula_propietario = ?";
    $stmtProveedor = $conn->prepare($sqlProveedor);
    $stmtProveedor->bind_param("s", $cedula);
    $stmtProveedor->execute();
    $stmtProveedor->store_result();

    // Determinar tipo
    if ($stmtUsuario->num_rows > 0) {
        $stmtUsuario->bind_result($id_credencial);
        $stmtUsuario->fetch();
        $tipo_usuario = 'cliente';
        $redirectUrl = "../vistas/home.php";
    } elseif ($stmtProveedor->num_rows > 0) {
        $stmtProveedor->bind_result($id_credencial);
        $stmtProveedor->fetch();
        $tipo_usuario = 'proveedor';
        $redirectUrl = "../vistas/proveedor.php";
    } else {
        echo "<script>alert('El usuario no tiene perfil asignado.'); window.history.back();</script>";
        exit();
    }

    $_SESSION['correo_usuario'] = $correo;
    $_SESSION['tipo_usuario'] = $tipo_usuario;

    // Paso 3: Validar contraseña
    $sqlCred = "SELECT contraseña FROM credencial WHERE id_credencial = ?";
    $stmtCred = $conn->prepare($sqlCred);
    $stmtCred->bind_param("s", $id_credencial);
    $stmtCred->execute();
    $stmtCred->store_result();

    if ($stmtCred->num_rows > 0) {
        $stmtCred->bind_result($hashed_password);
        $stmtCred->fetch();

        if (password_verify($password, $hashed_password)) {
            header("Location: $redirectUrl");
            exit();
        }
    }

    echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
    exit();

} else {
    echo "<script>alert('Correo no encontrado.'); window.history.back();</script>";
    exit();
}

$conn->close();
?>
