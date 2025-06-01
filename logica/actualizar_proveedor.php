<?php
session_start();
include '../logica/conectar.php';

if (!isset($_SESSION['cedula_usuario'])) {
    die("Error: No se ha iniciado sesión.");
}

$cedula_usuario = $_SESSION['cedula_usuario'];
$updates = [];
$params = [];
$correo_nuevo = null;

// Obtener el correo actual del usuario
$queryCorreoActual = "SELECT correo FROM persona WHERE cedula_persona = ?";
$stmtCorreoActual = $conn->prepare($queryCorreoActual);
$stmtCorreoActual->bind_param("s", $cedula_usuario);
$stmtCorreoActual->execute();
$stmtCorreoActual->bind_result($correo_actual);
$stmtCorreoActual->fetch();
$stmtCorreoActual->close();

// Construimos el UPDATE para la tabla persona
foreach ($_POST as $campo => $valor) {
    if (!empty($valor)) {
        if ($campo === 'correo' && $valor !== $correo_actual) { // Verifica si realmente se cambió
            $correo_nuevo = $valor;
        }
        $updates[] = "$campo = ?";
        $params[] = $valor;
    }
}

// Solo verificar si el usuario intenta cambiar el correo
if ($correo_nuevo !== null) {
    $verifica = "SELECT id_credencial FROM credencial WHERE usuario = ?";
    $stmtVerifica = $conn->prepare($verifica);
    $stmtVerifica->bind_param("s", $correo_nuevo);
    $stmtVerifica->execute();
    $resVerifica = $stmtVerifica->get_result();

    if ($resVerifica->num_rows > 0) {
        // Ya hay otra cuenta con ese correo, mostrar alerta y redireccionar
      header("Location: ../vistas/editar_datos_proveedor.php?error=El+correo+ya+está+en+uso+por+otra+cuenta");
      exit();

      
    }

    $stmtVerifica->close();
}

if (!empty($updates)) {
    try {
        // Actualizar persona
        $query = "UPDATE persona SET " . implode(", ", $updates) . " WHERE cedula_persona = ?";
        $stmt = $conn->prepare($query);
        $params[] = $cedula_usuario;
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        $stmt->execute();
        $stmt->close();

        // Si se actualizó el correo, también lo hacemos en credencial
        if ($correo_nuevo !== null) {
            // Obtener el id_credencial del proveedor
            $queryCred = "SELECT p.id_credencial FROM proveedor p WHERE p.cedula_propietario = ?";
            $stmtCred = $conn->prepare($queryCred);
            $stmtCred->bind_param("s", $cedula_usuario);
            $stmtCred->execute();
            $resultCred = $stmtCred->get_result();

            if ($resultCred->num_rows > 0) {
                $rowCred = $resultCred->fetch_assoc();
                $id_credencial = $rowCred['id_credencial'];

                // Actualizar correo en credencial
                $updateCred = "UPDATE credencial SET usuario = ? WHERE id_credencial = ?";
                $stmtUpdateCred = $conn->prepare($updateCred);
                $stmtUpdateCred->bind_param("ss", $correo_nuevo, $id_credencial);
                $stmtUpdateCred->execute();
                $stmtUpdateCred->close();
            }

            $stmtCred->close();
        }

        header("Location: ../vistas/proveedor.php?mensaje=Perfil actualizado");
        exit();
    } catch (Exception $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
} else {
    header("Location: ../vistas/proveedor.php?mensaje=No se realizaron cambios");
    exit();
}
?>