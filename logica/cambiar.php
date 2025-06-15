<?php
require 'config.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $cedula = $data['cedula'] ?? '';
    $codigo = $data['codigo'] ?? '';
    $nueva = $data['nueva'] ?? '';
    if (!$cedula || !$codigo || !$nueva) { 
        throw new Exception('Datos requeridos'); 
    }

    $conn = conectar();

    // 1. Buscar correo en persona
    $stmt = $conn->prepare("SELECT correo FROM persona WHERE cedula_persona=?");
    if (!$stmt) throw new Exception("Error prepare persona: " . $conn->error);
    $stmt->bind_param("s", $cedula);
    if (!$stmt->execute()) throw new Exception("Error execute persona: " . $stmt->error);
    $stmt->bind_result($correo);
    if (!$stmt->fetch()) { 
        $stmt->close();
        throw new Exception('No se encontró persona con esa cédula'); 
    }
    $stmt->close();

    // 2. Buscar id_credencial en credencial usando el correo como usuario
    $stmt = $conn->prepare("SELECT id_credencial FROM credencial WHERE usuario=?");
    if (!$stmt) throw new Exception("Error prepare credencial: " . $conn->error);
    $stmt->bind_param("s", $correo);
    if (!$stmt->execute()) throw new Exception("Error execute credencial: " . $stmt->error);
    $stmt->bind_result($id_credencial);
    if (!$stmt->fetch()) { 
        $stmt->close();
        throw new Exception('No se encontró credencial para este correo'); 
    }
    $stmt->close();

    // 3. Validar código en recuperacion (vigente, no usado)
    $stmt = $conn->prepare("SELECT id_recuperacion, expiracion, usado FROM recuperacion WHERE id_credencial=? AND codigo=? ORDER BY id_recuperacion DESC LIMIT 1");
    if (!$stmt) throw new Exception("Error prepare recuperacion: " . $conn->error);
    $stmt->bind_param("ss", $id_credencial, $codigo);
    if (!$stmt->execute()) throw new Exception("Error execute recuperacion: " . $stmt->error);
    $stmt->bind_result($id_recuperacion, $expiracion, $usado);
    if (!$stmt->fetch()) { 
        $stmt->close();
        throw new Exception('Código incorrecto'); 
    }
    $stmt->close();

    if ($usado) { throw new Exception('Código ya utilizado'); }
    if (strtotime($expiracion) < time()) { throw new Exception('Código expirado'); }

    // 4. Cambiar la contrasena en credencial
    $hash = password_hash($nueva, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE credencial SET contrasena=? WHERE id_credencial=?");
    if (!$stmt) throw new Exception("Error prepare update contrasena: " . $conn->error);
    $stmt->bind_param("ss", $hash, $id_credencial);
    if (!$stmt->execute()) { 
        $msg = "Error actualizando contrasena: " . $stmt->error;
        $stmt->close();
        throw new Exception($msg); 
    }
    $stmt->close();

    // 5. Marcar código como usado
    $stmt = $conn->prepare("UPDATE recuperacion SET usado=1 WHERE id_recuperacion=?");
    if (!$stmt) throw new Exception("Error prepare update usado: " . $conn->error);
    $stmt->bind_param("i", $id_recuperacion);
    if (!$stmt->execute()) {
        $msg = "Error marcando código como usado: " . $stmt->error;
        $stmt->close();
        throw new Exception($msg);
    }
    $stmt->close();

    echo json_encode(['ok'=>true, 'msg'=>'Contraseña cambiada, ahora puede iniciar sesión.']);
} catch (Exception $e) {
    // Devuelve el mensaje exacto de error
    echo json_encode(['error'=>'ERROR: '.$e->getMessage()]);
}
?>