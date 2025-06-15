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

function enviarCorreoPropietario($correo, $reserva, $cancha) {
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
        $mail->Subject = 'Reserva cancelada en su cancha #' . $cancha['id_cancha'];
        $mail->Body = "
            <h2>Una reserva ha sido cancelada</h2>
            <p>Le informamos que un cliente ha cancelado una reserva en su cancha. Detalles:</p>
            <ul>
                <li><strong>ID Reserva:</strong> {$reserva['id_reserva']}</li>
                <li><strong>Fecha reserva:</strong> {$reserva['fecha_reserva']}</li>
                <li><strong>Hora inicio:</strong> {$reserva['hora_inicio']}</li>
                <li><strong>Hora final:</strong> {$reserva['hora_final']}</li>
                <li><strong>Cancha:</strong> {$cancha['nombre_cancha']}</li>
                <li><strong>Dirección cancha:</strong> {$cancha['direccion_cancha']}</li>
            </ul>
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
        $id_cancha = $reserva['id_cancha'];

        // 2. Buscar cedula_propietario desde administra
        $sql_administra = "SELECT cedula_propietario FROM administra WHERE cod_cancha = ?";
        $stmt_administra = $conn->prepare($sql_administra);
        $stmt_administra->bind_param("s", $id_cancha);
        $stmt_administra->execute();
        $res_administra = $stmt_administra->get_result();
        $cedula_propietario = ($res_administra->num_rows > 0) ? $res_administra->fetch_assoc()['cedula_propietario'] : null;

        // 3. Buscar correo del propietario en persona
        $correo = null;
        if ($cedula_propietario) {
            $sql_persona = "SELECT correo FROM persona WHERE cedula_persona = ?";
            $stmt_persona = $conn->prepare($sql_persona);
            $stmt_persona->bind_param("s", $cedula_propietario);
            $stmt_persona->execute();
            $res_persona = $stmt_persona->get_result();
            $correo = ($res_persona->num_rows > 0) ? $res_persona->fetch_assoc()['correo'] : null;
            $stmt_persona->close();
        }

        // 4. Buscar datos de la cancha
        $sql_cancha = "SELECT id_cancha, nombre_cancha, direccion_cancha FROM cancha WHERE id_cancha = ?";
        $stmt_cancha = $conn->prepare($sql_cancha);
        $stmt_cancha->bind_param("s", $id_cancha);
        $stmt_cancha->execute();
        $res_cancha = $stmt_cancha->get_result();
        $cancha = ($res_cancha->num_rows > 0) ? $res_cancha->fetch_assoc() : array("id_cancha" => $id_cancha, "nombre_cancha" => "-", "direccion_cancha" => "-");
        $stmt_cancha->close();

        // 5. Cancelar la reserva
        $sql_update = "UPDATE reserva SET estado = 'cancelada' WHERE id_reserva = ?";
        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("s", $id_reserva);
            if ($stmt_update->execute()) {
                // 6. Enviar correo si se encontró el correo
                if ($correo && enviarCorreoPropietario($correo, $reserva, $cancha)) {
                    echo "<script>alert('Reserva cancelada y propietario notificado.'); window.location.href='../vistas/vista_reservas.php';</script>";
                } else {
                    echo "<script>alert('Reserva cancelada, pero no se pudo notificar al propietario.'); window.location.href='../vistas/vista_reservas.php';</script>";
                }
            } else {
                echo "<script>alert('Error al cancelar la reserva.'); window.location.href='../vistas/vista_reservas.php';</script>";
            }
            $stmt_update->close();
        } else {
            echo "<script>alert('Error en la consulta SQL.'); window.location.href='../vistas/vista_reservas.php';</script>";
        }
        $stmt_administra->close();
    } else {
        echo "<script>alert('Reserva no encontrada.'); window.location.href='../vistas/vista_reservas.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Solicitud inválida.'); window.location.href='../vistas/vista_reservas.php';</script>";
}
?>