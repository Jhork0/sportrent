<?php


session_start();

if (isset($_SESSION['cedula_usuario']) || isset($_SESSION['correo_usuario'])) {
    echo "<script>
        if (confirm('¿Seguro que quieres cerrar sesión?')) {
            window.location.href = '../logica/logout.php';
        } else {
            window.history.back();
        }
    </script>";
    exit();
} else{
    session_unset(); // Eliminar todas las variables de sesión
    session_destroy(); // Destruir la sesión 
    header("Location: ../index.php"); // Redirigir al inicio
}





?>