<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include '../logica/conectar.php';

$email = $_POST['correoi'];

$query = "SELECT cedula_persona FROM persona WHERE correo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cedula = $row['cedula_persona'];

    // Generar código aleatorio
    $codigo = rand(100000, 999999);

    // Insertar código en la tabla recuperacion_cuenta
    $insertQuery = "INSERT INTO recuperacion_cuenta (identificacion, codigo, fecha) VALUES (?, ?, NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ss", $cedula, $codigo);
    $insertStmt->execute();

    // Envío de correo usando PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tucorreo@gmail.com'; // 🔴 Cambiar
        $mail->Password   = 'tu_contraseña_app';   // 🔴 Contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('tucorreo@gmail.com', 'SportRent');
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Código de recuperación';
        $mail->Body    = "
            <h3>Recuperación de contraseña</h3>
            <p>Tu código de recuperación es: <strong>$codigo</strong></p>
            <p>Ingresa este código en la plataforma para continuar.</p>
        ";

        $mail->send();
        echo "<script>alert('Correo de recuperación enviado.'); window.location.href='../index.html';</script>";
    } catch (Exception $e) {
        echo "<script>alert('No se pudo enviar el correo. Error: {$mail->ErrorInfo}');</script>";
    }

    $insertStmt->close();
} else {
    echo "<script>alert('El correo electrónico no está registrado.');</script>";
}

$stmt->close();
$conn->close();
?>
