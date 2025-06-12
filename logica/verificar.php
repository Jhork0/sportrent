<?php
require 'config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$cedula = $data['cedula'] ?? '';
$codigo = $data['codigo'] ?? '';
if (!$cedula || !$codigo) { echo json_encode(['error'=>'Datos requeridos']); exit; }

$conn = conectar();

// 1. Verificar que la cedula existe en persona
$stmt = $conn->prepare("SELECT cedula_persona FROM persona WHERE cedula_persona=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
if (!$stmt->fetch()) { echo json_encode(['error'=>'Cédula no existe']); exit; }
$stmt->close();

// 2. Buscar id_credencial en usuario
$stmt = $conn->prepare("SELECT id_credencial FROM usuario WHERE cedula_persona=?");
$stmt->bind_param("s", $cedula);
$stmt->execute();
$stmt->bind_result($id_credencial);
if (!$stmt->fetch()) { echo json_encode(['error'=>'No se encontró credencial para esta persona']); exit; }
$stmt->close();

// 3. Buscar codigo en recuperacion (vigente y no usado)
$stmt = $conn->prepare("SELECT expiracion, usado FROM recuperacion WHERE id_credencial=? AND codigo=? ORDER BY id_recuperacion DESC LIMIT 1");
$stmt->bind_param("ss", $id_credencial, $codigo);
$stmt->execute();
$stmt->bind_result($expiracion, $usado);
if (!$stmt->fetch()) { echo json_encode(['error'=>'Código incorrecto']); exit; }
$stmt->close();

if ($usado) { echo json_encode(['error'=>'Código ya utilizado']); exit; }
if (strtotime($expiracion) < time()) { echo json_encode(['error'=>'Código expirado']); exit; }

echo json_encode(['ok'=>true, 'msg'=>'Código válido']);
?>