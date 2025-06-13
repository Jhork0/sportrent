<?php
session_start();
include '../logica/informacion_pago.php'; 
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'cliente') {
    header("Location: ../index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Reserva</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">
    <nav class="bg-white shadow-md py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="../vistas/home.php" class="text-2xl font-bold text-gray-800">SportRent</a>
        <div class="space-x-4">
            <a href="../vistas/home.php" class="text-gray-600 hover:text-gray-900">Regresar</a>
            <a href="#" class="text-gray-600 hover:text-gray-900">Mis Reservas</a>
        </div>
    </div>
</nav>


    <h2 class="text-3xl font-bold text-blue-700 text-center mb-8">Información de la Reserva</h2>

    <?php if ($reservation_found): ?>
        <div class="flex flex-col md:flex-row justify-center gap-6 mt-8 max-w-4xl mx-auto">
            <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 flex-1 min-w-[300px]">
                <h3 class="text-2xl font-semibold text-blue-600 text-center mb-5">Pago Virtual</h3>
                <p class="mb-4 text-gray-700">Si deseas realizar el pago virtual, puedes contactar al dueño de la cancha con la siguiente información:</p>
                <p class="mb-2"><strong class="font-medium text-gray-900">Teléfono del dueño:</strong> <?php echo $telefono; ?></p>
                <p><strong class="font-medium text-gray-900">Nombre del dueño:</strong> <?php echo $nombre_completo_dueno; ?></p>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 flex-1 min-w-[300px]">
                <h3 class="text-2xl font-semibold text-blue-600 text-center mb-5">Pago Presencial</h3>
                <p class="mb-4 text-gray-700">Si prefieres realizar el pago de forma presencial, dirígete a la siguiente dirección:</p>
                <div class="bg-green-50 border border-green-200 p-5 text-center rounded-md mt-4">
                    <p><strong class="font-medium text-gray-900">Dirección de la cancha:</strong> <?php echo $direccion; ?></p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p class="text-center text-red-600 font-semibold text-lg mt-10"><?php echo $error_message; ?></p>
    <?php endif; ?>

</body>
</html>