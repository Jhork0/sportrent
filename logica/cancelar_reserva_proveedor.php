<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-error.log');
error_reporting(E_ALL);

session_start();
include '../logica/conectar.php'; // Conectar a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SESSION['tipo_usuario'] === 'cliente') {
     header("Location: ../index.php");
     exit();
}

function enviarCorreoCancelacion($correo, $reserva, $cancha) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soportesportrent@gmail.com';
        $mail->Password = 'tqac gfat jagt uvzm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('soportesportrent@gmail.com', 'Soporte');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Cancelacion de su reserva #' . $reserva['id_reserva'];
        $mail->Body = "
            <h2>Reserva Cancelada</h2>
            <p>Su reserva ha sido <strong>cancelada</strong>. Aquí están los detalles:</p>
            <ul>
                <li><strong>ID Reserva:</strong> {$reserva['id_reserva']}</li>
                <li><strong>Fecha reserva:</strong> {$reserva['fecha_reserva']}</li>
                <li><strong>Hora inicio:</strong> {$reserva['hora_inicio']}</li>
                <li><strong>Hora final:</strong> {$reserva['hora_final']}</li>
                <li><strong>Cancha:</strong> {$cancha['nombre_cancha']}</li>
                <li><strong>Dirección cancha:</strong> {$cancha['direccion_cancha']}</li>
            </ul>
            <p>Si tiene dudas, comuníquese con soporte.</p>
            <p>Atentamente,<br>Soporte de SoportRent</p>
        ";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];

    // 1. Buscar datos de la reserva antes de actualizar
    $sql = "SELECT * FROM reserva WHERE id_reserva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_reserva);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $reserva = $res->fetch_assoc();
        $cedula_persona = $reserva['cedula_persona'];
        $id_cancha = $reserva['id_cancha'];

        // 2. Buscar correo de la persona
        $sql_persona = "SELECT correo FROM persona WHERE cedula_persona = ?";
        $stmt_persona = $conn->prepare($sql_persona);
        $stmt_persona->bind_param("s", $cedula_persona);
        $stmt_persona->execute();
        $res_persona = $stmt_persona->get_result();
        $correo = ($res_persona->num_rows > 0) ? $res_persona->fetch_assoc()['correo'] : null;

        // 3. Buscar datos de la cancha
        $sql_cancha = "SELECT nombre_cancha, direccion_cancha FROM cancha WHERE id_cancha = ?";
        $stmt_cancha = $conn->prepare($sql_cancha);
        $stmt_cancha->bind_param("s", $id_cancha);
        $stmt_cancha->execute();
        $res_cancha = $stmt_cancha->get_result();
        $cancha = ($res_cancha->num_rows > 0) ? $res_cancha->fetch_assoc() : array("nombre_cancha" => "-", "direccion_cancha" => "-");

        // 4. Cancelar la reserva
        $sql_update = "UPDATE reserva SET estado = 'cancelada' WHERE id_reserva = ?";
        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("s", $id_reserva);
            if ($stmt_update->execute()) {
                // 5. Enviar correo si se encontró el correo
                if ($correo && enviarCorreoCancelacion($correo, $reserva, $cancha)) {
                    echo "<script>alert('Reserva cancelada y correo enviado.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
                } else {
                    echo "<script>alert('Reserva cancelada, pero no se pudo enviar el correo.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
                }
            } else {
                echo "<script>alert('Error al cancelar la reserva.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
            }
            $stmt_update->close();
        } else {
            echo "<script>alert('Error en la consulta SQL.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
        }
        $stmt_persona->close();
        $stmt_cancha->close();
    } else {
        echo "<script>alert('Reserva no encontrada.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Solicitud inválida.'); window.location.href='../vistas/vistareservasproveedor.php';</script>";
}
?>