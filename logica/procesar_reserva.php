<?php
include '../logica/conectar.php';

session_start();
if (!isset($_SESSION['cedula_usuario'])) {
    die("Error: Usuario no autenticado.");
}
$cedula_persona = $_SESSION['cedula_usuario'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function enviarCorreoPropietario($correo, $datos_reserva, $datos_cancha) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soportesportrent@gmail.com';
        $mail->Password = 'tqac gfat jagt uvzm';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('soportesportrent@gmail.com', 'Soporte SoportRent');
        $mail->addAddress($correo);

        $mail->isHTML(true);
$mail->Subject = 'Nueva reserva - ' . $datos_cancha['nombre_cancha'] . ' - Codigo: ' . $datos_reserva['id_reserva'];
       $mail->Body = "
    <h2>Nueva reserva recibida</h2>
    <p>Un usuario ha realizado una reserva en su cancha. Detalles:</p>
    <ul>
        <li><strong>ID Reserva:</strong> {$datos_reserva['id_reserva']}</li>
        <li><strong>Fecha reserva:</strong> {$datos_reserva['fecha_reserva']}</li>
        <li><strong>Hora inicio:</strong> {$datos_reserva['hora_inicio']}</li>
        <li><strong>Hora final:</strong> {$datos_reserva['hora_final']}</li>
        <li><strong>Cancha:</strong> {$datos_cancha['nombre_cancha']}</li>
        <li><strong>Dirección cancha:</strong> {$datos_cancha['direccion_cancha']}</li>
    </ul>
    <p>Por favor, gestione esta reserva desde el panel de administración.</p>
    <p>Visite la página: <a href='https://sportrent.byethost31.com/sportrent-main/'>https://sportrent.byethost31.com/sportrent-main/</a></p>
    <p>Atentamente,<br>Soporte de SoportRent</p>
";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_cancha = $_POST["id_cancha"];
    $fecha_reserva = $_POST["fecha_reserva"];
    $horario = $_POST["horario"]; // Recibimos el valor "hora_inicio - hora_final"
    list($hora_inicio, $hora_final) = explode(" - ", $horario);

    // Generar un ID único para la reserva
    $id_reserva = uniqid("res_");
    $estado = "pendiente";

    $sql = "INSERT INTO reserva (id_reserva, fecha_reserva, hora_inicio, hora_final, estado, cedula_persona, id_cancha) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $id_reserva, $fecha_reserva, $hora_inicio, $hora_final, $estado, $cedula_persona, $id_cancha);

    if ($stmt->execute()) {
        // Solo si registro fue exitoso, buscar propietario y enviar correo
        $sql_administra = "SELECT cedula_propietario FROM administra WHERE cod_cancha = ?";
        $stmt_administra = $conn->prepare($sql_administra);
        $stmt_administra->bind_param("s", $id_cancha);
        $stmt_administra->execute();
        $res_administra = $stmt_administra->get_result();
        $cedula_propietario = ($res_administra->num_rows > 0) ? $res_administra->fetch_assoc()['cedula_propietario'] : null;
        $stmt_administra->close();

        $correo_propietario = null;
        if ($cedula_propietario) {
            $sql_persona = "SELECT correo FROM persona WHERE cedula_persona = ?";
            $stmt_persona = $conn->prepare($sql_persona);
            $stmt_persona->bind_param("s", $cedula_propietario);
            $stmt_persona->execute();
            $res_persona = $stmt_persona->get_result();
            $correo_propietario = ($res_persona->num_rows > 0) ? $res_persona->fetch_assoc()['correo'] : null;
            $stmt_persona->close();
        }

        $sql_cancha = "SELECT nombre_cancha, direccion_cancha FROM cancha WHERE id_cancha = ?";
        $stmt_cancha = $conn->prepare($sql_cancha);
        $stmt_cancha->bind_param("s", $id_cancha);
        $stmt_cancha->execute();
        $res_cancha = $stmt_cancha->get_result();
        $cancha = ($res_cancha->num_rows > 0) ? $res_cancha->fetch_assoc() : array("nombre_cancha" => "-", "direccion_cancha" => "-");
        $stmt_cancha->close();

        $datos_reserva = [
            'id_reserva' => $id_reserva,
            'fecha_reserva' => $fecha_reserva,
            'hora_inicio' => $hora_inicio,
            'hora_final' => $hora_final
        ];
        if ($correo_propietario && enviarCorreoPropietario($correo_propietario, $datos_reserva, $cancha)) {
            echo "Reserva Confirmada: notificación enviada al propietario.";
        } else {
            echo "Reserva Confirmada: pero NO se pudo notificar al propietario.";
        }
    } else {
        echo "Error al registrar la reserva. Inténtalo nuevamente.";
    }
    $stmt->close();
    $conn->close();
}
?>