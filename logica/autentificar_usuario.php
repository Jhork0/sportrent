<?php
include '../logica/conectar.php';
session_start();

$id_usuario = $_SESSION['cedula_usuario'] ?? null;
if (!$id_usuario) {
    header("Location: ../vistas/login.php");
    exit;
}

// Base de la consulta
$sql = "SELECT * FROM reserva WHERE cedula_persona = ?";

// Agregar filtro si se seleccionaron estados
$filtros = [];
$tipos = "s"; // el primer parámetro siempre es el id_usuario
$params = [$id_usuario];

if (isset($_GET['estado']) && is_array($_GET['estado']) && count($_GET['estado']) > 0) {
    $placeholders = implode(',', array_fill(0, count($_GET['estado']), '?'));
    $sql .= " AND estado IN ($placeholders)";
    foreach ($_GET['estado'] as $estado) {
        $params[] = $estado;
        $tipos .= "s";
    }
}

// Preparar y ejecutar consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param($tipos, ...$params);
$stmt->execute();
$result = $stmt->get_result();
