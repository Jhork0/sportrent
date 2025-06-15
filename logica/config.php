<?php
$DB_HOST = 'sql211.byethost31.com';          // Host para MySQL
$DB_USER = 'b31_39215483';                   // Usuario MySQL
$DB_PASS = '123456789100n';        // Contraseña: es la misma que usas para entrar al vPanel
$DB_NAME = 'b31_39215483_sportsRent';       

function conectar() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    if ($conn->connect_error) die('Error de conexión: ' . $conn->connect_error);
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>