<?php
require 'config.php';
require 'correo.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$cedula = $data['cedula'] ?? '';
if (!$cedula) { echo json_encode(['error'=>'Cédula requerida']); exit; }

$conn = conectar();

// 1. Buscar correo en persona
$stmt = $conn->prepare("SELECT correo FROM persona WHERE cedula_persona=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$stmt->bind_result($correo);
if (!$stmt->fetch()) { 
    echo json_encode(['error'=>'Cédula no existe']); 
    exit; 
}
$stmt->close();

// 2. Buscar id_credencial en credencial usando el correo como usuario
$stmt = $conn->prepare("SELECT id_credencial FROM credencial WHERE usuario=?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($id_credencial);
if (!$stmt->fetch()) { 
    echo json_encode(['error'=>'No se encontró credencial para este correo']); 
    exit; 
}
$stmt->close();

// 3. Generar código y expiración
$codigo = rand(100000, 999999);
$expira = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// 4. Insertar en recuperacion
$stmt = $conn->prepare("INSERT INTO recuperacion (id_credencial, codigo, expiracion) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $id_credencial, $codigo, $expira);
$stmt->execute();
$stmt->close();

// 5. Enviar correo
if (enviarCodigo($correo, $codigo)) {
    echo json_encode(['ok'=>true, 'msg'=>'Código enviado al correo']);
} else {
    echo json_encode(['error'=>'Error enviando correo']);
}
?>