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

    // 1. Buscar id_credencial en usuario
    $stmt = $conn->prepare("SELECT id_credencial FROM usuario WHERE cedula_persona=?");
    if (!$stmt) throw new Exception("Error prepare usuario: " . $conn->error);
    $stmt->bind_param("s", $cedula);
    if (!$stmt->execute()) throw new Exception("Error execute usuario: " . $stmt->error);
    $stmt->bind_result($id_credencial);
    if (!$stmt->fetch()) { 
        $stmt->close();
        throw new Exception('No se encontró credencial para esta persona'); 
    }
    $stmt->close();

    // 2. Validar código en recuperacion (vigente, no usado)
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

    // 3. Cambiar la contraseña en credencial
    $hash = password_hash($nueva, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE credencial SET contraseña=? WHERE id_credencial=?");
    if (!$stmt) throw new Exception("Error prepare credencial: " . $conn->error);
    $stmt->bind_param("ss", $hash, $id_credencial);
    if (!$stmt->execute()) { 
        $msg = "Error actualizando contraseña: " . $stmt->error;
        $stmt->close();
        throw new Exception($msg); 
    }
    $stmt->close();

    // 4. Marcar código como usado
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