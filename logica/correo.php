<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
function enviarCodigo($para, $codigo) {
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
        $mail->addAddress($para);

        $mail->isHTML(false);
        $mail->Subject = 'Codigo de recuperacion: ' . $codigo . ' - ' . date('Y-m-d H:i:s');
$mail->Body = "Hola,\n\nHas solicitado restablecer tu contraseña. Por favor, ingresa el siguiente código en el sistema y cambia tu contraseña:\n\nCódigo de recuperación: $codigo\n\nRecuerde que este código tiene una duración de 15 minutos; luego de esto el código no será válido y deberá solicitar otro.\n\nSi no solicitaste este código puedes ignorar este mensaje.\n\nAtentamente,\nSoporte de SoportRent";        $mail->MessageID = '<' . uniqid() . '@soportesportrent.com>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>