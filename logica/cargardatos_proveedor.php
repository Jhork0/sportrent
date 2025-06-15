<?php
session_start();

if (!isset($_SESSION['cedula_usuario']) || $_SESSION['tipo_usuario'] != 'proveedor') {
    header("Location: ../index.php");
    exit();
}

include '../logica/conectar.php';

$cedula_propietario = $_SESSION['cedula_usuario'];

try {
    $query = "SELECT p.*, pr.tipo_documento, c.usuario, c.contrasena 
              FROM persona p
              JOIN proveedor pr ON p.cedula_persona = pr.cedula_propietario
              JOIN credencial c ON pr.id_credencial = c.id_credencial
              WHERE p.cedula_persona = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cedula_propietario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $proveedor = $result->fetch_assoc();
    } else {
        throw new Exception("Proveedor no encontrado");
    }

    $stmt->close();
} catch (Exception $e) {
    $error = "Error al cargar los datos: " . $e->getMessage();
}
?>