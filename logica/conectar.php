<?php
$servername = "sql211.byethost31.com";  // Host de ByetHost
$username = "b31_39215483";             // Usuario MySQL
$password = "123456789100n";            // Tu contraseña
$dbname = "b31_39215483_sportsRent";    // Nombre de la base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>