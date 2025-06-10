<?php
include '../logica/conectar.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['correo'] = $_POST['correo'] ?? '';
    $_SESSION['cedula'] = $_POST['cedula_persona'] ?? '';
    $_SESSION['direccion'] = $_POST['direccion'] ?? '';
    $_SESSION['telefono'] = $_POST['telefono'] ?? '';
    $_SESSION['nombre_completo'] = $_POST['nombre_completo'] ?? '';
    $_SESSION['tipo'] = $_POST['tipo'] ?? '';
}


$cedula = $_POST['cedula_persona'] ?? '';
$verificarSql = "SELECT cedula_persona FROM persona WHERE cedula_persona = ?";
$verificarStmt = $conn->prepare($verificarSql);
$verificarStmt->bind_param("s", $cedula);
$verificarStmt->execute();
$verificarStmt->store_result();

if ($verificarStmt->num_rows > 0) {
    echo "
    <script>
        alert('¡La cédula ya existe en el sistema! Por favor ingresa una diferente.');
        window.history.back(); 
    </script>";
    exit();
}

$verificarStmt->close();



$correo_electronico = $_POST['correo'] ?? '';

$verificarSqlc = "SELECT correo FROM persona WHERE correo = ?";
$verificarStmtc = $conn->prepare($verificarSqlc);
$verificarStmtc->bind_param("s", $correo_electronico);
$verificarStmtc->execute();
$verificarStmtc->store_result();

if ($verificarStmtc->num_rows > 0) {
    echo "
    <script>
        alert('¡La direccion de correo electronico ya existe en el sistema! Por favor ingresa una diferente.');
        window.history.back(); 
    </script>";
    exit();
}





$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$cedula = $_POST['cedula_persona'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$nombreCompleto = $_POST["nombre_completo"] ?? '';
$tipo_perfil = $_POST["tipo"] ?? '';
$estado = "activo";
$tipo_documento = "cedula";
$credencial = generarIdCredencial($conn);




// Dividir nombre
function dividirNombre($nombreCompleto) {
    $partes = explode(" ", trim($nombreCompleto));
    return [
        'primer_nombre' => $partes[0] ?? 'Desconocido',
        'segundo_nombre' => $partes[1] ?? '',
        'primer_apellido' => $partes[2] ?? '',
        'segundo_apellido' => $partes[3] ?? ''
    ];
}
$datosNombre = dividirNombre($nombreCompleto);

// Función para generar ID de credencial
function generarIdCredencial($conexion) {
    $sql = "SELECT id_credencial FROM credencial ORDER BY id_credencial DESC LIMIT 1";
    $resultado = mysqli_query($conexion, $sql);
    if ($fila = mysqli_fetch_assoc($resultado)) {
        $numero = intval(substr($fila['id_credencial'], 4)) + 1;
    } else {
        $numero = 1;
    }
    return 'cred' . str_pad($numero, 3, '0', STR_PAD_LEFT);
}

// Insertar en persona
$sql1 = "INSERT INTO persona (cedula_persona, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, correo, direccion, telefono) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ssssssss", $cedula, $datosNombre['primer_nombre'], $datosNombre['segundo_nombre'], $datosNombre['primer_apellido'], $datosNombre['segundo_apellido'], $correo_electronico, $direccion, $telefono);




if ($stmt1->execute()) {
    // Insertar en credencial
    $sql2 = "INSERT INTO credencial (id_credencial, usuario, contraseña) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("sss", $credencial, $correo_electronico, $password);

    if ($stmt2->execute()) {
        // Insertar en propietario o usuario según tipo_perfil
        if ($tipo_perfil == "proveedor") {
            $sql3 = "INSERT INTO proveedor (cedula_propietario, tipo_documento, id_credencial) VALUES (?, ?, ?)";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("sss", $cedula, $tipo_documento, $credencial);
        } else {
            $sql3 = "INSERT INTO usuario (cedula_persona, estado, id_credencial) VALUES (?, ?, ?)";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("sss", $cedula, $estado, $credencial);
        }

        if ($stmt3->execute()) {
          header("Location: ../index.php");
            exit();
        } else {
            echo "Error al registrar usuario o propietario: " . $stmt3->error;
        }
        $stmt3->close();
    } else {
        echo "Error al registrar credencial: " . $stmt2->error;
    }
    $stmt2->close();
} else {
    echo "Error al registrar persona: " . $stmt1->error;
}





$stmt1->close();
$conn->close();
?>
