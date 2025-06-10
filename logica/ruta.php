<?php

// Definir rutas base
$rutas = [
    'cliente' => '../vistas/home.php',
    'proveedor' => '../vistas/proveedor.php'
];
$rutaHome = $rutas['cliente'] ?? '../index.php'; 

// Si hay sesión y el rol está definido, sobreescribir
if (isset($_SESSION['tipo_usuario']) && array_key_exists($_SESSION['tipo_usuario'], $rutas)) {
    $rutaHome = $rutas[$_SESSION['tipo_usuario']];
}


?>