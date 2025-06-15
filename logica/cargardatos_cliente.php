<?php
include '../logica/conectar.php';

session_start();

if (!isset($_SESSION['cedula_usuario']) || $_SESSION['tipo_usuario'] != 'cliente') {
    header("Location: ../index.php");
    exit();
}



$cedula_cliente = $_SESSION['cedula_usuario'];

try {
    $query = "SELECT p.*, c.usuario, c.contrasena 
              FROM persona p
              JOIN usuario u ON p.cedula_persona = u.cedula_persona
              JOIN credencial c ON u.id_credencial = c.id_credencial
              WHERE p.cedula_persona = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $cedula_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        throw new Exception("Cliente no encontrado");
    }

    $stmt->close();
} catch (Exception $e) {
    $error = "Error al cargar los datos: " . $e->getMessage();
}
?>
